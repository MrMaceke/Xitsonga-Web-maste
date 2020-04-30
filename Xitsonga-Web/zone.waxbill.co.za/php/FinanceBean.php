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
     
    
    
    $aLocalBeanCall = TRUE;
    require_once './DocumentCreatorBean.php';
    $aLocalBeanCall = FALSE;
    
    $aFunction = $_REQUEST['type'];
    $aJSONData = $_REQUEST['data'];
    
    if($_REQUEST['data'] != null){
        $aFinanceBean = new FinanceBean();
        if(true){
            if(method_exists($aFinanceBean, $aFunction)){
                echo $aFinanceBean->dynamicFunction($aFunction,$aJSONData);
            }else {
                echo $aFinanceBean->jsonFeedback->feedback("Function is not supported", FeedbackConstants::FAILED);
            }
        }else{
            echo $aFinanceBean->jsonFeedback->feedback("System error occured. Failed to start session", FeedbackConstants::FAILED);
        }
    }
    
    class FinanceBean {
        public $logging = null;
        public $jsonFeedback = null;
        public $accessValidator = null;
        
        public function FinanceBean() {
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
            if($param == null){
                $this->$functionName();
            }else{
                $param = json_decode($param);
                return $this->$functionName($param);
            }
        }
         /**
         * Adds a new payment
         *
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewPayment($param) {
            $this->logging->startMethod("addNewPayment");
            $this->logging->debugObject("Payment Object",$param);
            
            $aFinancialPaymentDAO = new FinancialPaymentDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aInputValidator = new InputValidator();
            $aPHPToJSONArray = new PHPToJSONArray();
            
            $aValidation = $aInputValidator->validateAddPayment($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewPayment");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aProjectResults[status]) {
                $this->logging->exitMethod("addNewPayment");
                return $this->jsonFeedback->feedback("Project ID not found in system", FeedbackConstants::FAILED);
            }
            
            if($aProjectResults[resultsArray][user_id] !== $param->clientId) {
                $this->logging->exitMethod("addNewPayment");
                return $this->jsonFeedback->feedback("Project ID doesn't belong to client", FeedbackConstants::FAILED);
            }
            
            $paymentDescription = "Payment allocated successfully";
            $paymentData = $aPHPToJSONArray->newPaymentJSON($param->clientId, $param->projectId, $param->paymentReference, $paymentDescription, $param->paymentAmount, $param->paymentDate);
                    
            $aPaymentResults = $aFinancialPaymentDAO->addNewPayment($paymentData);
            if(!$aPaymentResults[status]) {
                $this->logging->exitMethod("addNewPayment");
                return $this->jsonFeedback->feedback("System failed to add payment", FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("addNewPayment");
            return $this->jsonFeedback->feedback("Payment added successfully", FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Retrieve development deals.
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveDevelomentDeals($param) {
            $this->logging->startMethod("retrieveDevelomentDeals");
            $this->logging->debugObject("Project Object",$param);
            
            $aFinancialDealsDAO = new FinancialDealsDAO();
            $aDealsResults = $aFinancialDealsDAO->retrieveDevelopmentDeals();
            if(!$aDealsResults[status]) {
                $this->logging->exitMethod("retrieveDevelomentDeals");
                return $this->jsonFeedback->feedback("There are deals active deals on system.", FeedbackConstants::FAILED);
            }
        
            $this->logging->exitMethod("retrieveDevelomentDeals");
            return $this->jsonFeedback->deals($aDealsResults[resultsArray],"Development deals successfully retrieved.");
        }
        /**
         * Genrates a quote.
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function generateQuoteAndDownload($param) {
            $this->logging->startMethod("generateQuoteAndDownload");
            $this->logging->debugObject("Quote Object",$param); 

            $aSystemEntityDAO = new SystemEntityDAO();
            $aFinancialDealsDAO = new FinancialDealsDAO();
            $aFinancialQuotesDAO = new FinancialQuotesDAO();
            $aInputValidator = new InputValidator();
            $aDocumentCreatorBean = new DocumentCreatorBean();

            $aValidationResults = $aInputValidator->validateGenerateQuote($param->dealCodes);
            if(!$aValidationResults[status]){
                 $this->logging->exitMethod("generateQuoteAndDownload");
                 return $this->jsonFeedback->feedback($aValidationResults[message], FeedbackConstants::FAILED); 
            }
                       
            $ProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$ProjectResults[status]) {
                $this->logging->exitMethod("generateQuoteAndDownload");
                return $this->jsonFeedback->feedback("Project ID not found in system.", FeedbackConstants::FAILED);
            }
            
            $aDeleteQuotesResults = $aFinancialQuotesDAO->deleteQuoteForProject($param->projectId);
            if(!$aDeleteQuotesResults[status]) {
                $this->logging->exitMethod("generateQuoteAndDownload");
                return $this->jsonFeedback->feedback($aDeleteQuotesResults[message], FeedbackConstants::FAILED);
            }
            
            foreach ($param->dealCodes as $key => $value) {
                $aDealCode = $value->dealCode;
                $aDealResults = $aFinancialDealsDAO->findRecordByDealCode($aDealCode);
                if(!$aDealResults[status]){
                   $this->logging->exitMethod("generateQuoteAndDownload");
                   return $this->jsonFeedback->feedback("Deal ".$aDealCode." not found in system.", FeedbackConstants::FAILED); 
                }
                
                $aResult = $aDealResults[resultsArray];
                
                $aDeals = $aDeals."{"
                    ."\"dealCode\":\"" .  $aResult['deal_code'] ."\","
                    ."\"dealPrice\":\"" . $aResult['deal_price'] ."\""       
                ."}";
                
                $aDeals = $aDeals.",";
            }
            
            $aDeals = substr_replace($aDeals, "", -1);
            
            $aQuoteJson =  "{"
                            ."\"projectId\":"."\"".$param->projectId ."\","
                            ."\"deals\":["
                               .$aDeals 
                            ."]"
                        ."}";
           
            $aQuoteResults = $aFinancialQuotesDAO->addNewQuote(json_decode($aQuoteJson));
            if(!$aQuoteResults[status]) {
                $this->logging->exitMethod("generateQuoteAndDownload");
                return $this->jsonFeedback->feedback($aQuoteResults[message], FeedbackConstants::FAILED);
            }
            
            $aDocumentResults = $aDocumentCreatorBean->dynamicFunction("downloadProjectQuotePDF", ("{\"quoteNumber\":"."\"".$aQuoteResults[resultsArray][quote_name]."\"}"));
            $aDocumentResultsJSON = json_decode($aDocumentResults);
                        
            if($aDocumentResultsJSON->status === FeedbackConstants::FAILED){
                $this->logging->exitMethod("generateQuoteAndDownload");
                return $this->jsonFeedback->feedback($aDocumentResultsJSON->message, FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("generateQuoteAndDownload");
            return $this->jsonFeedback->feedback($aDocumentResultsJSON->message, FeedbackConstants::SUCCESSFUL);
        }
        
        /**
         * Genrates an external quote.
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function generateExternalQuoteAndDownload($param) {
            $this->logging->startMethod("generateExternalQuoteAndDownload");
            $this->logging->debugObject("Quote Object",$param); 

            $aFinancialDealsDAO = new FinancialDealsDAO();
            $aFinancialQuotesDAO = new FinancialQuotesDAO();
            $aInputValidator = new InputValidator();
            $aDocumentCreatorBean = new DocumentCreatorBean();

            $aExternalQuoteProject = "P000001";
            
            $aValidationResults = $aInputValidator->validateGenerateExternalQuote($param);
            if(!$aValidationResults[status]){
                $this->logging->exitMethod("generateExternalQuoteAndDownload");
                return $this->jsonFeedback->feedback($aValidationResults[message], FeedbackConstants::FAILED); 
            }    
            
            foreach ($param->dealCodes as $key => $value) {
                $aDealCode = $value->dealCode;
                $aDealResults = $aFinancialDealsDAO->findRecordByDealCode($aDealCode);
                if(!$aDealResults[status]){
                   $this->logging->exitMethod("generateExternalQuoteAndDownload");
                   return $this->jsonFeedback->feedback("Deal ".$aDealCode." not found in system.", FeedbackConstants::FAILED); 
                }
                
                $aResult = $aDealResults[resultsArray];
                
                $aDeals = $aDeals."{"
                    ."\"dealCode\":\"" .  $aResult['deal_code'] ."\","
                    ."\"dealPrice\":\"" . $aResult['deal_price'] ."\""       
                ."}";
                
                $aDeals = $aDeals.",";
            }
            
            $aDeals = substr_replace($aDeals, "", -1);
            
            $aQuoteJson =  "{"
                            ."\"projectId\":"."\"".$aExternalQuoteProject."\","
                            ."\"deals\":["
                               .$aDeals 
                            ."]"
                        ."}";
           
            $aQuoteResults = $aFinancialQuotesDAO->addNewQuote(json_decode($aQuoteJson));
            if(!$aQuoteResults[status]) {
                $this->logging->exitMethod("generateExternalQuoteAndDownload");
                return $this->jsonFeedback->feedback($aQuoteResults[message], FeedbackConstants::FAILED);
            }
            
            $aDocumentJSON = "{"
                            ."\"quoteNumber\":"."\"".$aQuoteResults[resultsArray][quote_name]."\","
                            ."\"firstName\":"."\"".$param->firstName."\","
                            ."\"lastName\":"."\"".$param->lastName."\","
                            ."\"email\":"."\"".$param->email."\","
                            ."\"phoneNumber\":"."\"".$param->phoneNumber."\""
                        ."}";
            $aDocumentResults = $aDocumentCreatorBean->dynamicFunction("downloadExternalQuotePDF", $aDocumentJSON);
            $aDocumentResultsJSON = json_decode($aDocumentResults);
                        
            if($aDocumentResultsJSON->status === FeedbackConstants::FAILED){
                $this->logging->exitMethod("generateExternalQuoteAndDownload");
                return $this->jsonFeedback->feedback($aDocumentResultsJSON->message, FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("generateExternalQuoteAndDownload");
            return $this->jsonFeedback->feedback($aDocumentResultsJSON->message, FeedbackConstants::SUCCESSFUL);
        }
    }
