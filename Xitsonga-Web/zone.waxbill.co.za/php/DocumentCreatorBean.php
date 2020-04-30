<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
    require_once './constants/FeedbackConstants.php';
    require_once './constants/GroupsConstants.php';
    
    require_once './dao/SystemUserDAO.php';
    require_once './dao/SystemGroupDAO.php';
    require_once './dao/SystemPropertyDAO.php';
    require_once './dao/SystemRoleDAO.php';
    require_once './dao/SystemEntityDAO.php';
    require_once './dao/SystemEntityDetailsDAO.php';
    require_once './dao/SystemEntityLinkDAO.php';
    
    require_once './dao/FinancialDealsDAO.php';
    require_once './dao/FinancialQuotesDAO.php';
    require_once './dao/FinancialPaymentDAO.php';
    
    require_once './validator/InputValidator.php';
    require_once './validator/BusinessDataValidator.php';
    require_once './validator/AccessValidator.php';
    
    require_once './utils/Logging.php';
    require_once './utils/JsonUtils.php';
    require_once './utils/PHPToJSONArray.php';
    require_once './utils/SendEmail.php';
    
     require_once './utils/FPDF.php';
    
    $aFunction = $_REQUEST['type'];
    $aJSONData = $_REQUEST['data'];
    
    if($aLocalBeanCall === TRUE){
        $aDocumentCreatorBean = new DocumentCreatorBean();
        if(true){
            if(method_exists($aDocumentCreatorBean, $aFunction)){
                return $aDocumentCreatorBean->dynamicFunction($aFunction,$aJSONData);
            }else {
                return $aDocumentCreatorBean->jsonFeedback->feedback("Function is not supported", FeedbackConstants::FAILED);
            }
        }else{
            return $aDocumentCreatorBean->jsonFeedback->feedback("System error occured. Failed to start session", FeedbackConstants::FAILED);
        }
    }
    else if($_REQUEST['data'] != null){
        $aDocumentCreatorBean = new DocumentCreatorBean();
        if(true){
            if(method_exists($aDocumentCreatorBean, $aFunction)){
                echo $aDocumentCreatorBean->dynamicFunction($aFunction,$aJSONData);
            }else {
                echo $aDocumentCreatorBean->jsonFeedback->feedback("Function is not supported", FeedbackConstants::FAILED);
            }
        }else{
            echo $aDocumentCreatorBean->jsonFeedback->feedback("System error occured. Failed to start session", FeedbackConstants::FAILED);
        }
    }
    
    class DocumentCreatorBean {
        public $logging = null;
        public $jsonFeedback = null;
        public $accessValidator = null;
        
        public function DocumentCreatorBean() {
            $this->logging = new Logging(self::class);
            $this->jsonFeedback = new JSONUtils();
            $this->accessValidator = new AccessValidator();
            
            $this->accessValidator->startSession();
        }
        /**
         * Dynamically calls a function
         * 
         * @param String functionName
         * @param JSONObject param
         * @return JSONObject
         */
        public function dynamicFunction($functionName, $param = null) {
            if(!method_exists($this,$functionName)){
                return $this->jsonFeedback->feedback("Function is not supported", FeedbackConstants::FAILED);
            }
            
            if($param == null){
                $this->$functionName();
            }else{
                $param = json_decode($param);
                return $this->$functionName($param);
            }
        }
        /**
         * Download a PDF with project contract
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function downloadProjectContract($param) {
            $this->logging->startMethod("downloadProjectContract");
            $this->logging->debugObject("Project Object",$param);
        
            $aFileLocation = strtoupper($param->projectId)." - Contract of agreement.pdf";
            
            if(!file_exists("../contracts/".$aFileLocation)) {
                $this->logging->exitMethod("downloadProjectContract");
                return $this->jsonFeedback->feedback("There is no contract attached to project.", FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("downloadProjectContract");
            return $this->jsonFeedback->feedback("contracts/".$aFileLocation, FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Create a PDF with quote
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function downloadQuote($param) {
            $aFinancialQuotesDAO = new FinancialQuotesDAO(); 
            
            $aQuoteResults = $aFinancialQuotesDAO->findRecordByProjectId($param->projectId);
            if(!$aQuoteResults[status]){
                $this->logging->exitMethod("downloadQuote");
                return $this->jsonFeedback->feedback("Project has no quote attached.", FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("downloadQuote");
            return $this->downloadProjectQuotePDF(json_decode("{\"quoteNumber\":"."\"".$aQuoteResults[resultsArray][quote_name]."\"}"));
        }
        /**
         * Create a PDF with quote
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function downloadInvoice($param) {
            $this->logging->startMethod("downloadInvoice");
            $this->logging->debugObject("Invoice Object",$param);
            
            $aPDF = new FPDF();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aFinancialPaymentDAO = new FinancialPaymentDAO();
            
            $aInvoiceResults = $aFinancialPaymentDAO->findRecordByPaymentCode($param->invoiceId);
            
            if(!$aInvoiceResults['status']) {
                $this->logging->exitMethod("downloadInvoice");
                return $this->jsonFeedback->feedback("Invoice not found in system.", FeedbackConstants::FAILED);
            }
            
            $aUserResults = $aSystemUserDAO->findRecordWithUserID($aInvoiceResults[resultsArray][created_by]);
            if(!$aUserResults[status]){
                $this->logging->exitMethod("downloadInvoice");
                return $this->jsonFeedback->feedback("User associated with invoice not found in system.", FeedbackConstants::FAILED);
            }
            
            $aClientResults = $aSystemUserDAO->findRecordWithUserID($aInvoiceResults[resultsArray][user_id]);
            if(!$aClientResults[status]){
                $this->logging->exitMethod("downloadInvoice");
                return $this->jsonFeedback->feedback("Client associated with invoice not found in system.", FeedbackConstants::FAILED);
            }
            
            $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserResults[resultsArray][user_key]);
            if(!$aUserDetails[status]){
                $this->logging->exitMethod("downloadInvoice");
                return $this->jsonFeedback->feedback("Created user details were not found on system", FeedbackConstants::FAILED);  
            }
           
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aUserFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aUserLastName = $value[entity_detail_content];
                }else if($value[property_name] === "Phone Number"){
                    $aUserPhoneNumber = $value[entity_detail_content];
                }
            }
            
            $aClientDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aClientResults[resultsArray][user_key]);
            if(!$aClientDetails[status]){
                $this->logging->exitMethod("downloadInvoice");
                return $this->jsonFeedback->feedback("Client details were not found on system", FeedbackConstants::FAILED);  
            }
            
            $aProjectDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aInvoiceResults[resultsArray][project_id]);
             if(!$aProjectDetails[status]){
                $this->logging->exitMethod("downloadInvoice");
                return $this->jsonFeedback->feedback("Project details were not found on system", FeedbackConstants::FAILED);  
            }
            
            $aClientEmailAddress = $aClientResults[resultsArray][email];
            foreach ($aClientDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aClientFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aClientLastName = $value[entity_detail_content];
                }else if($value[property_name] === "Phone Number"){
                    $aClientPhoneNumber = $value[entity_detail_content];
                }
            }
            
             foreach ($aProjectDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "Project Name"){
                    $aProjectName = $value[entity_detail_content];
                }
            }
            
            $aDateObject = date_create($aInvoiceResults[resultsArray][payment_date]);
            $aInvoicePaymentDate = date_format($aDateObject,"D, d M Y");
            
            $aFileName = $aClientFirstName." ".$aClientLastName." - Payment ".$param->invoiceId."";
                        
            if(file_exists("../generated_documents/".$aFileName.".pdf")) {
                //return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);                
            }
            
            $aPDF->AddPage();
            $aPDF->SetFont('Times');
            $aPDF->SetRightMargin(20);
            $aPDF->SetLeftMargin(20);
            $aPDF->AliasNbPages();
            
            $aPDF->SetFont('Times',"B",14);
            $aPDF->Cell(0,60,"Invoice",0,0,"R");
            
            $aPDF->Cell(0,35,"",0,1);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Invoice # ".$param->invoiceId,0,1,'R');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Payment Date:",0,1,'R');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aInvoicePaymentDate,0,1,'R');
            $aPDF->SetFont('Times',"B",10);
            $aPDF->Cell(0,5,"Payment By",0,1,"R");
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aClientFirstName." ".$aClientLastName,0,1,'R');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Customer ID: ".$aClientResults[resultsArray][user_key],0,1,'R');
            
            $aPDF->SetFont('Times',"U",10);
            $aPDF->SetTextColor(255,127,0);
            $aPDF->Cell(0,5,  strtolower($aClientEmailAddress),0,1,'R');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aClientPhoneNumber,0,1,'R');
            
            $aTopMargin = -85;
            
            $aPDF->SetFont('Times',"B",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5 + $aTopMargin,"Payment Details",0,1,'L');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Payment To             : Waxbill Africa, FNB Account",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Payment Reference : ".$aInvoiceResults[resultsArray][reference],0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Payment Amount    : "."R".number_format($aInvoiceResults[resultsArray][amount],2),0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Payment Date          : ".$aInvoicePaymentDate,0,1,'L');
            $aPDF->SetFont('Times',"B",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Allocated to            : ".$aInvoiceResults[resultsArray][project_id],0,1,'L');
            
            $aPDF->SetY(105);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(60,6,"Authorised By",1,1,'',true);
             
            $aPDF->Cell(60);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(60,-6,"Designation",1,1,"",true);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(49,6,"E-Signature",1,1,"",true);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(60,6,"Faith Khosa",1,1,'',false);
            
            $aPDF->Cell(60);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(60,-6,"Business Director",1,1,'',false);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Courier',"U",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"YF KHOSA",1,1,'',false);
            
            $aPDF->SetY(122);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(30,6,"Project Code",1,1,'',true);
             
            $aPDF->Cell(30);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(90,-6,"Description",1,1,"",true);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(49,6,"Amount Paid",1,1,"",true);
            
            $aYPositon = 122;
            $aSignaturePostion = 0;
            $aTotalPrice = 0;
            $aYPositon = $aYPositon + 6;
            $aPDF->SetY($aYPositon);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);

            $aPDF->Cell(30,6,$aInvoiceResults[resultsArray][project_id],1,1,'',false);

            $aPDF->Cell(30);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->MultiCell(90,-6,$aProjectName,1,1,"",false);

            $aPDF->Cell(120);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"R".number_format($aInvoiceResults[resultsArray][amount],2),1,1,"",false);

            $aTotalPrice += $aInvoiceResults[resultsArray][amount];
            $aSignaturePostion = $aSignaturePostion + 6;
            
            $aYPositon = $aYPositon + 12;
            $aPDF->SetY($aYPositon);

            $aPDF->Cell(30);

            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(90,-6,"Total ",0,1,"R",false);

            $aPDF->Cell(120);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"R".number_format($aTotalPrice,2),1,1,"",false);
            
            $aYPositon = $aYPositon + 12;
            $aPDF->SetY($aYPositon);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"Invoice prepared by: ".$aUserFirstName." ".$aUserLastName,0,1,"L",false);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"Waxbill acknowledges to have received your payment. ",0,1,"C",false);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Thank you for business!",0,1,"C",false);
            
            $aPDF->Output("../generated_documents/".$aFileName.".pdf","F");
            
            $this->logging->exitMethod("downloadInvoice");
            return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);
        }
        
        /**
         * Create a PDF with quote
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function downloadExternalQuotePDF($param) {
            $this->logging->startMethod("downloadExternalQuotePDF");
            $this->logging->debugObject("Quote Object",$param);
            
            $aPDF = new FPDF();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aFinancialQuotesDAO = new FinancialQuotesDAO();            
                        
            $aQuoteResults = $aFinancialQuotesDAO->findRecordByQuoteNumber($param->quoteNumber);
            if(!$aQuoteResults[status]){
                $this->logging->exitMethod("downloadExternalQuotePDF");
                return $this->jsonFeedback->feedback("Quote number not found in system.", FeedbackConstants::FAILED);
            }
            
            $aQuoteDetailsResults = $aFinancialQuotesDAO->findQuoteDetailsByQuoteNumber($param->quoteNumber);
            if(!$aQuoteDetailsResults[status]){
                $this->logging->exitMethod("downloadExternalQuotePDF");
                return $this->jsonFeedback->feedback("Quote details not found in system.", FeedbackConstants::FAILED);
            }
            
            $aUserResults = $aSystemUserDAO->findRecordWithUserID($aQuoteResults[resultsArray][user_id]);
            if(!$aUserResults[status]){
                $this->logging->exitMethod("downloadExternalQuotePDF");
                return $this->jsonFeedback->feedback("User associated with quote not found in system.", FeedbackConstants::FAILED);
            }

            $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserResults[resultsArray][user_key]);
            if(!$aUserDetails[status]){
                $this->logging->exitMethod("downloadExternalQuotePDF");
                return $this->jsonFeedback->feedback("Created user details were not found on system", FeedbackConstants::FAILED);  
            }
            
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aUserFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aUserLastName = $value[entity_detail_content];
                }else if($value[property_name] === "Phone Number"){
                    $aUserPhoneNumber = $value[entity_detail_content];
                }
            }
           
            
            $aClientEmailAddress = $param->email;
            $aClientFirstName = $param->firstName;
            $aClientLastName = $param->lastName;
            $aClientPhoneNumber = $param->phoneNumber;
            
            $aDateObject = date_create($aQuoteResults[resultsArray][end_date]);
            $aQuoteExpiryDate = date_format($aDateObject,"D, d M Y");
            
            $aExternalQuoteProject = "P000001";
            if($aQuoteResults[resultsArray][project_id] !== $aExternalQuoteProject){
                return $this->downloadProjectQuotePDF($param);
            }
            
            $aFileName = "External - Quotation ".$param->quoteNumber."";
                        
            if(file_exists("../generated_documents/".$aFileName.".pdf")) {
                return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);                
            }
            
            if($aClientPhoneNumber == ""){
                return $this->jsonFeedback->feedback("Quote document not found in system. Please contact administrator.", FeedbackConstants::FAILED);   
            }
            
            $aPDF->AddPage();
            $aPDF->SetFont('Times');
            $aPDF->SetRightMargin(20);
            $aPDF->SetLeftMargin(20);
            $aPDF->AliasNbPages();
            
            $aPDF->SetFont('Times',"B",14);
            $aPDF->Cell(0,60,"Quotation",0,0,"R");
            
            $aPDF->Cell(0,35,"",0,1);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Quote # ".$param->quoteNumber,0,1,'R');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Expiration Date:",0,1,'R');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aQuoteExpiryDate,0,1,'R');
            
            $aPDF->Cell(0,5,"To",0,1,"R");
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aClientFirstName." ".$aClientLastName,0,1,'R');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Phone Number: ".$aClientPhoneNumber,0,1,'R');
            
            $aPDF->SetFont('Times',"U",10);
            $aPDF->SetTextColor(255,127,0);
            $aPDF->Cell(0,5,  strtolower($aClientEmailAddress),0,1,'R');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"",0,1,'R');
            
            $aTopMargin = -85;
            
            $aPDF->SetFont('Times',"B",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5 + $aTopMargin,"Banking Details",0,1,'L');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Account Name         : M.S Dumela",0,1,'L');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Bank Name              : Standard Bank",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Account Number     : 240173856",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Branch Name           : Global",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Branch Number       : 051001",0,1,'L');
            $aPDF->SetFont('Times',"B",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Reference Number	: ".$param->quoteNumber,0,1,'L');
            
            $aPDF->SetY(105);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(60,6,"Authorised By",1,1,'',true);
             
            $aPDF->Cell(60);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(60,-6,"Designation",1,1,"",true);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(49,6,"E-Signature",1,1,"",true);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(60,6,"Faith Khosa",1,1,'',false);
            
            $aPDF->Cell(60);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(60,-6,"Business Director",1,1,'',false);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Courier',"U",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"YF KHOSA",1,1,'',false);
            
            $aPDF->SetY(122);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(30,6,"Deal code",1,1,'',true);
             
            $aPDF->Cell(30);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(90,-6,"Description",1,1,"",true);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(49,6,"Line Price",1,1,"",true);
            
            $aYPositon = 122;
            $aSignaturePostion = 0;
            $aTotalPrice = 0;
            $array = array("","","","");
            foreach ($aQuoteDetailsResults[resultsArray] as $key => $value) {
                $aYPositon = $aYPositon + 6;
                $aPDF->SetY($aYPositon);

                $aPDF->SetFont('Times',"",11);
                $aPDF->SetTextColor(0);

                $aPDF->Cell(30,6,$value[deal_code],1,1,'',false);

                $aPDF->Cell(30);

                $aPDF->SetFont('Times',"",11);
                $aPDF->SetTextColor(0);
                $aPDF->MultiCell(90,-6,$value[deal_name],1,1,"",false);

                $aPDF->Cell(120);

                $aPDF->SetFont('Times',"",11);
                $aPDF->SetTextColor(0);
                $aPDF->Cell(49,6,"R".number_format($value[detail_price],2),1,1,"",false);
                
                $aTotalPrice += $value[detail_price];
                $aSignaturePostion = $aSignaturePostion + 6;
            }
            
            
            $aYPositon = $aYPositon + 12;
            $aPDF->SetY($aYPositon);

            $aPDF->Cell(30);

            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(90,-6,"Total ",0,1,"R",false);

            $aPDF->Cell(120);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"R".number_format($aTotalPrice,2),1,1,"",false);
            
            $aYPositon = $aYPositon + 12;
            $aPDF->SetY($aYPositon);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"Quotation prepared by: ".$aUserFirstName." ".$aUserLastName,0,1,"L",false);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"This quotation on the above mentioned deals is subject to the conditions noted below:",0,1,"L",false);
            
            $aPDF->Cell(5);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(40,6,"- Upon acceptance of the quote, a 50% deposit is required in order to commence with the project",0,1,"L",false);
            
            $aPDF->Cell(5);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(40,6,"- The remainder shall be payable upon delivery of the final output.",0,1,"L",false);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(40,10,"To accept this quotation, sign here and return: ",0,1,"L",false);
            
            
            $aPDF->SetLineWidth(0.2);
            $aPDF->SetDrawColor(0);
            $aPDF->Line(95, $aSignaturePostion + 180, 189, $aSignaturePostion + 180);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Thank you for business!",0,1,"C",false);
            
            $aPDF->Output("../generated_documents/".$aFileName.".pdf","F");
            
            $this->logging->exitMethod("downloadExternalQuotePDF");
            return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Create a PDF with quote
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function downloadProjectQuotePDF($param) {
            $this->logging->startMethod("downloadProjectQuotePDF");
            $this->logging->debugObject("Quote Object",$param);
            
            $aPDF = new FPDF();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aFinancialQuotesDAO = new FinancialQuotesDAO();            
                        
            $aQuoteResults = $aFinancialQuotesDAO->findRecordByQuoteNumber($param->quoteNumber);
            if(!$aQuoteResults[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("Quote number not found in system.", FeedbackConstants::FAILED);
            }
            
            $aQuoteDetailsResults = $aFinancialQuotesDAO->findQuoteDetailsByQuoteNumber($param->quoteNumber);
            if(!$aQuoteDetailsResults[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("Quote details not found in system.", FeedbackConstants::FAILED);
            }
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($aQuoteResults[resultsArray][project_id]);
            if(!$aProjectResults[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("Project associated with quote not found in system.", FeedbackConstants::FAILED);
            }
            
            $aUserResults = $aSystemUserDAO->findRecordWithUserID($aQuoteResults[resultsArray][user_id]);
            if(!$aUserResults[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("User associated with quote not found in system.", FeedbackConstants::FAILED);
            }
            
            $aClientResults = $aSystemUserDAO->findRecordWithUserID($aProjectResults[resultsArray][user_id]);
            if(!$aClientResults[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("Client associated with quote not found in system.", FeedbackConstants::FAILED);
            }
            
            $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserResults[resultsArray][user_key]);
            if(!$aUserDetails[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("Created user details were not found on system", FeedbackConstants::FAILED);  
            }
           
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aUserFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aUserLastName = $value[entity_detail_content];
                }else if($value[property_name] === "Phone Number"){
                    $aUserPhoneNumber = $value[entity_detail_content];
                }
            }
            
            $aClientDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aClientResults[resultsArray][user_key]);
            if(!$aClientDetails[status]){
                $this->logging->exitMethod("downloadProjectQuotePDF");
                return $this->jsonFeedback->feedback("Client details were not found on system", FeedbackConstants::FAILED);  
            }
            
            $aClientEmailAddress = $aClientResults[resultsArray][email];
            foreach ($aClientDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aClientFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aClientLastName = $value[entity_detail_content];
                }else if($value[property_name] === "Phone Number"){
                    $aClientPhoneNumber = $value[entity_detail_content];
                }
            }
            
            $aDateObject = date_create($aQuoteResults[resultsArray][end_date]);
            $aQuoteExpiryDate = date_format($aDateObject,"D, d M Y");
            
            $aFileName = $aClientFirstName." ".$aClientLastName." - Quotation ".$param->quoteNumber."";
                        
            if(file_exists("../generated_documents/".$aFileName.".pdf")) {
                return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);                
            }
            
            $aPDF->AddPage();
            $aPDF->SetFont('Times');
            $aPDF->SetRightMargin(20);
            $aPDF->SetLeftMargin(20);
            $aPDF->AliasNbPages();
            
            $aPDF->SetFont('Times',"B",14);
            $aPDF->Cell(0,60,"Quotation",0,0,"R");
            
            $aPDF->Cell(0,35,"",0,1);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Quote # ".$param->quoteNumber,0,1,'R');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Expiration Date:",0,1,'R');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aQuoteExpiryDate,0,1,'R');
            
            $aPDF->Cell(0,5,"To",0,1,"R");
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aClientFirstName." ".$aClientLastName,0,1,'R');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Customer ID: ".$aClientResults[resultsArray][user_key],0,1,'R');
            
            $aPDF->SetFont('Times',"U",10);
            $aPDF->SetTextColor(255,127,0);
            $aPDF->Cell(0,5,  strtolower($aClientEmailAddress),0,1,'R');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,$aClientPhoneNumber,0,1,'R');
            
            $aTopMargin = -85;
            
            $aPDF->SetFont('Times',"B",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5 + $aTopMargin,"Banking Details",0,1,'L');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Account Name         : M.S Dumela",0,1,'L');
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Bank Name              : Standard Bank",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Account Number     : 240173856",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Branch Name           : MYBRANCH",0,1,'L');
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,-$aTopMargin + 5,"Branch Number       : 051001",0,1,'L');
            $aPDF->SetFont('Times',"B",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,$aTopMargin + 5,"Reference Number	: ".$aClientResults[resultsArray][user_key],0,1,'L');
            
            $aPDF->SetY(105);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(60,6,"Authorised By",1,1,'',true);
             
            $aPDF->Cell(60);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(60,-6,"Designation",1,1,"",true);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(49,6,"E-Signature",1,1,"",true);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(60,6,"Faith Khosa",1,1,'',false);
            
            $aPDF->Cell(60);
            
            $aPDF->SetFont('Times',"",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(60,-6,"Business Director",1,1,'',false);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Courier',"U",10);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"YF KHOSA",1,1,'',false);
            
            $aPDF->SetY(122);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(30,6,"Deal code",1,1,'',true);
             
            $aPDF->Cell(30);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(90,-6,"Description",1,1,"",true);
            
            $aPDF->Cell(120);
            
            $aPDF->SetFont('Helvetica',"B",9);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(49,6,"Line Price",1,1,"",true);
            
            $aYPositon = 122;
            $aSignaturePostion = 0;
            $aTotalPrice = 0;
            $array = array("","","","");
            foreach ($aQuoteDetailsResults[resultsArray] as $key => $value) {
                $aYPositon = $aYPositon + 6;
                $aPDF->SetY($aYPositon);

                $aPDF->SetFont('Times',"",11);
                $aPDF->SetTextColor(0);

                $aPDF->Cell(30,6,$value[deal_code],1,1,'',false);

                $aPDF->Cell(30);

                $aPDF->SetFont('Times',"",11);
                $aPDF->SetTextColor(0);
                $aPDF->MultiCell(90,-6,$value[deal_name],1,1,"",false);

                $aPDF->Cell(120);

                $aPDF->SetFont('Times',"",11);
                $aPDF->SetTextColor(0);
                $aPDF->Cell(49,6,"R".number_format($value[detail_price],2),1,1,"",false);
                
                $aTotalPrice += $value[detail_price];
                $aSignaturePostion = $aSignaturePostion + 6;
            }
            
            
            $aYPositon = $aYPositon + 12;
            $aPDF->SetY($aYPositon);

            $aPDF->Cell(30);

            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(90,-6,"Total ",0,1,"R",false);

            $aPDF->Cell(120);

            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(49,6,"R".number_format($aTotalPrice,2),1,1,"",false);
            
            $aYPositon = $aYPositon + 12;
            $aPDF->SetY($aYPositon);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"Quotation prepared by: ".$aUserFirstName." ".$aUserLastName,0,1,"L",false);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"This quotation on the above mentioned deals is subject to the conditions noted below:",0,1,"L",false);
            
            $aPDF->Cell(5);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(40,6,"- Upon acceptance of the quote, a 50% deposit is required in order to commence with the project",0,1,"L",false);
            
            $aPDF->Cell(5);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(40,6,"- The remainder shall be payable upon delivery of the final output.",0,1,"L",false);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(40,10,"To accept this quotation, sign here and return: ",0,1,"L",false);
            
            
            $aPDF->SetLineWidth(0.2);
            $aPDF->SetDrawColor(0);
            $aPDF->Line(95, $aSignaturePostion + 180, 189, $aSignaturePostion + 180);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Thank you for business!",0,1,"C",false);
            
            $aPDF->Output("../generated_documents/".$aFileName.".pdf","F");
            
            $this->logging->exitMethod("downloadProjectQuotePDF");
            return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Create a PDF with client credentials
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function downloadClientCredentialsPackPDF($param) {
            $this->logging->startMethod("downloadClientCredentialsPackPDF");
            $this->logging->debugObject("Client Object",$param);
            
            $aPDF = new FPDF();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityDetailsDAO= new SystemEntityDetailsDAO();
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->clientId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethod("downloadClientCredentialsPackPDF");
                return $this->jsonFeedback->feedback("Client ID not found in system", FeedbackConstants::FAILED);
            }
            
            $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($param->clientId);
            if(!$aUserDetails[status]){
                $this->logging->exitMethod("downloadClientCredentialsPackPDF");
                return $this->jsonFeedback->feedback("Client details were not found on system", FeedbackConstants::FAILED);  
            }
            
            $aUserRecordsResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserRecordsResults[status]){
                $this->logging->exitMethod("downloadClientCredentialsPackPDF");
                return $this->jsonFeedback->feedback("User record were not found on system", FeedbackConstants::FAILED);  
            }
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aLastName = $value[entity_detail_content];
                }
            }
            
            $aUserPasswordResults = $aSystemUserDAO->findPasswordRecordWithClientID($param->clientId);
            if(!$aUserPasswordResults[status]){
                $this->logging->exitMethod("downloadClientCredentialsPackPDF");
                return $this->jsonFeedback->feedback("Password record were not found on system", FeedbackConstants::FAILED);  
            }
            
            $aDecryptedPassword = GeneralUtils::decryptPassword($aUserPasswordResults[resultsArray][password], $aUserPasswordResults[resultsArray][salt]);
            
            $aFileName = "Credentials - ".$aFirstName."_".$aLastName." ".date('D, d M Y His');
            
            $aPDF->AddPage();
            $aPDF->SetFont('Times');
            $aPDF->SetRightMargin(20);
            $aPDF->SetLeftMargin(20);
            $aPDF->AliasNbPages();
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->Cell(0,70,"Hello ".$aFirstName." ".$aLastName.",",0,0,"L");
            
            $aPDF->Cell(0,40,"",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Here are your credentials to Waxbill systems. Please remember to update your password.",0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8,"1.  Client Zone",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"ClientZone is a platform for Waxbill clients to track progress to their projects.",0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"URL: ",0,1,"L");
            
            $aPDF->SetFont('Times',"U",11);
            $aPDF->SetTextColor(255,127,0);
            
            $aPDF->Cell(0,0,"http://zone.waxbill.co.za",0,1,"L");
            
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Username: ",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,0,$param->clientId." or ".$aUserRecordsResults[resultsArray][email],0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Password: ",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,0,$aDecryptedPassword,0,1);
            
            $aPDF->Cell(0,10,"",0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"2.  Server Links",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->MultiCell(0,6,"Server Links is Waxbill's Quality assurance environment. Server Links provides you will links to appropriate environments to test projects.",0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"URL: ",0,1,"L");
            
            $aPDF->SetFont('Times',"U",11);
            $aPDF->SetTextColor(255,127,0);
            
            $aPDF->Cell(0,0,"http://qa.waxbill.co.za",0,1,"L");
            
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Username: ",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,0,$param->clientId." or ".$aUserRecordsResults[resultsArray][email],0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Password: ",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,0,$aDecryptedPassword,0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,20,"Don't hesitate to contact us if you have any questions",0,1);
            
            $aPDF->SetFont('Times',"B",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,10,"Kind Regards",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"Waxbill Team",0,1);
            
            $aPDF->SetFont('Times',"U",11);
            $aPDF->SetTextColor(255,127,0);
            $aPDF->Cell(0,5,"info@waxbill.co.za",0,1);
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,5,"+27 71 011 2950",0,1);
            
            $aPDF->Output("../generated_documents/".$aFileName.".pdf","F");
            
            $this->logging->exitMethod("downloadClientCredentialsPackPDF");
            return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Create a PDF with client basic information
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function createClientBasicInformationPDF($param) {
            $this->logging->startMethod("createClientBasicInformationPDF");
            $this->logging->debugObject("Client Object",$param);
            
            $aPDF = new FPDF();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityDetailsDAO= new SystemEntityDetailsDAO();
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->clientId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethod("createClientBasicInformationPDF");
                return $this->jsonFeedback->feedback("Client ID not found in system", FeedbackConstants::FAILED);
            }
            
            $aPDF->AddPage();
            $aPDF->SetFont('Times');
            $aPDF->SetRightMargin(20);
            $aPDF->SetLeftMargin(20);
            $aPDF->AliasNbPages();
            
            $aPDF->SetFont('Helvetica',"B",11);
            $aPDF->Cell(0,60,"PERSONAL",0,0,"C");
            
            $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($param->clientId);
            if(!$aUserDetails[status]){
                $this->logging->exitMethod("createClientBasicInformationPDF");
                return $this->jsonFeedback->feedback("Client details were not found on system", FeedbackConstants::FAILED);  
            }
            
            $aUserRecordsResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserRecordsResults[status]){
                $this->logging->exitMethod("createClientBasicInformationPDF");
                return $this->jsonFeedback->feedback("User record were not found on system", FeedbackConstants::FAILED);  
            }
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[property_name] === "First Name"){
                    $aFirstName = $value[entity_detail_content];
                }else if($value[property_name] === "Last Name"){
                    $aLastName = $value[entity_detail_content];
                }
            }
            
            $aPDF->Cell(0,40,"",0,1);
            $aPDF->SetFont('Helvetica',"B",11);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(0,8,"Basic Information",1,1,"",true);
            
            $aFileName = "Client - ".$aFirstName."_".$aLastName." ".date('D, d M Y His');
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8, "Client ID: ".$param->clientId,1,1);
            
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[group_name] === "Client Basic"){
                    $aValue = $value[entity_detail_content];
                    $aTitle = $value[property_name];
                    
                    $aPDF->SetFont('Times',"",11);
                    $aPDF->SetTextColor(0);
                    $aPDF->Cell(0,8, ucfirst(strtolower($aTitle)).": ".$aValue,1,1);
                }
            }
            
            $aPDF->SetFont('Times',"",11);
            $aPDF->SetTextColor(0);
            $aPDF->Cell(0,8, "Email Address: ".strtolower($aUserRecordsResults[resultsArray][email]),1,1);
            
            $aPDF->Cell(0,20,"",0,1);
            $aPDF->SetFont('Helvetica',"B",11);
            $aPDF->SetTextColor(255);
            $aPDF->SetFillColor(255,128,0);
            $aPDF->Cell(0,8,"Physical Address",1,1,"",true);
            
            foreach ($aUserDetails[resultsArray] as $key => $value) {
                if($value[group_name] === "Client Address"){
                    $aValue = $value[entity_detail_content];
                    $aTitle = $value[property_name];
                    
                    $aPDF->SetFont('Times',"",11);
                    $aPDF->SetTextColor(0);
                    $aPDF->Cell(0,8, ucfirst(strtolower($aTitle)).": ".$aValue,1,1);
                }
            }
            
            $aPDF->Output("../generated_documents/".$aFileName.".pdf","F");
            
            $this->logging->exitMethod("createClientBasicInformationPDF");
            return $this->jsonFeedback->feedback("generated_documents/".$aFileName.".pdf", FeedbackConstants::SUCCESSFUL);
        }
    }
