<?php
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); // Proxies.

    require_once __DIR__. '/validator/AccessValidator.php';
    require_once __DIR__. '/utils/Logging.php';
    require_once __DIR__. '/dao/SystemEntityDAO.php';
    require_once __DIR__.'/dao/SystemSupportDAO.php';
    require_once  __DIR__.'/dao/FinancialQuotesDAO.php';
    require_once  __DIR__.'/dao/FinancialPaymentDAO.php';
    
    
    class AccessValidatorBean {
        const DEFAULT_ERROR_MESSAGE = "System was not setup properly. Please contact support <a href='mailto:info@waxbill.co.za'>info@waxbill.co.za</a>";
        const USER_TYPE_ERROR_MESSAGE = "User type was not setup. Please contact support <a href='mailto:info@waxbill.co.za'>info@waxbill.co.za</a>";
        
        const CLIENT        = "Client";
        const BUSINESS      = "Consultant";
        const DEVELOPER     = "Developer";
        const ADMIN         = "Administrator";
        
        public $accessValidator = null;
        
        public function AccessValidatorBean() {
            $this->logging = new Logging(self::class);
            $this->logging->setLogFilePath(__DIR__."/../logging/syslogs.txt");
            
            $this->accessValidator = new AccessValidator();
        }
        public function hasAccess($param) {
            $this->logging->startMethod("hasAccess");
            $this->logging->debugObject("Page Object",$param);
           
            $aReturn = $this->accessValidator->hasAccess($param[pageName]);

            $this->logging->debugPHPObject("Current User",$this->accessValidator->getSystemUser());
            $this->logging->debugBoolean("Access",$aReturn);
             
            $this->logging->exitMethod("hasAccess");
            return array(status=> $aReturn, message=>"Location");
        }
        
        public function retrieveUserRole() {
           $this->logging->startMethod("retrieveUserRole");
           
           $aRoleName = $this->accessValidator->getSystemUser()->getRoleName();
           
           $this->logging->exitMethod("retrieveUserRole");
           return array(status=> true, message=>$aRoleName);
        }
        
        public function hasAccessToInvoice($param) {
            $this->logging->startMethod("hasAccessToInvoice");
            $aFinancialPaymentDAO = new FinancialPaymentDAO();
            
            $aInvoiceResults = $aFinancialPaymentDAO->findRecordByPaymentCode($param[param]);
            if(!$aInvoiceResults['status']) {
                $this->logging->exitMethod("hasAccessToInvoice");
                return array(status=> false, message=>"Page not found");
            }
            
            $aRoleName = $this->accessValidator->getSystemUser()->getRoleName();
            
            if($aRoleName === "Client") {
                $auserId = $aInvoiceResults[resultsArray][user_id];
                if($auserId !== $this->accessValidator->getSystemUser()->getUserID()){
                    return array(status=> false, message=>"User does not have access");
                }
            }
            
            $this->logging->exitMethod("hasAccessToInvoice");
            return array(status=> true, message=>"User has access");
        }
        
        public function hasAccessToTicket($param) {
            $this->logging->startMethod("hasAccessToTicket");
            $aSystemSupportDAO = new SystemSupportDAO();
            
            $aSupportTicket = $aSystemSupportDAO->retrieveSystemSupportTicket($param[param]);
            if(!$aSupportTicket['status']) {
                $this->logging->exitMethod("hasAccessToTicket");
                return array(status=> false, message=>"Page not found");
            }
            
            $aRoleName = $this->accessValidator->getSystemUser()->getRoleName();
            
            if($aRoleName === "Client") {
                $auserId = $aSupportTicket[resultsArray][user_id];
                if($auserId !== $this->accessValidator->getSystemUser()->getUserID()){
                    return array(status=> false, message=>"User does not have access");
                }
            }
            
            $this->logging->exitMethod("hasAccessToTicket");
            return array(status=> true, message=>"User has access");
        }
        
        public function hasAccessToResource($param) {
            $this->logging->startMethod("hasAccessToResource");
            $aSystemEntityDAO = new SystemEntityDAO();
            
            $aResults = $aSystemEntityDAO->findRecordWithName($param[param]);
            if(!$aResults['status']) {
                $this->logging->exitMethod("hasAccessToResource");
                return array(status=> false, message=>"Page not found");
            }
            
            if(strtolower($aResults[resultsArray][property_name]) !== strtolower($param[pageName])) {
                $this->logging->exitMethod("hasAccessToResource");
                return array(status=> false, message=>"Page not found");
            }
            
            $aRoleName = $this->accessValidator->getSystemUser()->getRoleName();
            
            if($aRoleName === "Client") {
                $auserId = $aResults[resultsArray][user_id];
                if($auserId !== $this->accessValidator->getSystemUser()->getUserID()){
                    return array(status=> false, message=>"User does not have access");
                }
            }
            
            $this->logging->exitMethod("hasAccessToResource");
            return array(status=> true, message=>"User has access");
        }
    }