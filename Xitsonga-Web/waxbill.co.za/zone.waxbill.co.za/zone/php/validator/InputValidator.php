<?php
    /**
     * Input Validator
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0  
     */
    class InputValidator {
        // System User
        const MAX_EMAIL_LENGTH = 100;
        const MIN_PASSWORD_LENGTH = 4;
        const MIN_CLIENT_ID = 8;
        
        // System properties
        const MIN_GROUP_NAME_LENGTH = 3;
        
        // Files
        const MAX_FILE_SIZE = 10485760;
        private $SUPPORTED_TYPES = array("application/pdf","application/zip","application/octet-stream","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        private $SUPPORTED_PDF_TYPES = array("application/pdf");
        private $DEPLOYMENT_SUPPORTED_TYPES = array("application/x-rar-compressed", "application/zip", "application/x-zip", "application/octet-stream", "application/x-zip-compressed");
         
        public function validateAddSystemUser($data){
            if($this->isEmpty($data->email) || $this->minLengthRequirement($data->email, self::MAX_EMAIL_LENGTH)) { 
                return array(status=> false, message=>"Email address is mandatory"); 
            }elseif($this->isEmpty($data->systemRole) || !$this->minLengthRequirement($data->systemRole, 1)) { 
                return array(status=> false, message=>"System role is mandatory"); 
            }elseif(!$this->isValidEmailFormat($data->email)){
                return array(status=> false, message=>"Email address is invalid"); 
            }
            return array(status=> true);
        }
        
        public function validateGenerateExternalQuote($param) {
            if($this->isEmpty($param->firstName) || !$this->minLengthRequirement($param->firstName, self::MIN_GROUP_NAME_LENGTH)) { 
                return array(status=> false, message=>"First name is too short. It requires at least 3 characters"); 
            }elseif($this->isEmpty($param->lastName) || !$this->minLengthRequirement($param->lastName, self::MIN_GROUP_NAME_LENGTH)) { 
                return array(status=> false, message=>"Last name is too short. It requires at least 3 characters"); 
            }elseif($this->isEmpty($param->phoneNumber) || !$this->minLengthRequirement($param->phoneNumber, 10)) { 
                return array(status=> false, message=>"Phone number is too short. It requires at least 10 characters"); 
            }elseif($this->isEmpty($param->email) || $this->minLengthRequirement($param->email, self::MAX_EMAIL_LENGTH)) { 
                return array(status=> false, message=>"Email address is mandatory"); 
            }elseif(!$this->isValidEmailFormat($param->email)){
                return array(status=> false, message=>"Email address is invalid"); 
            }
            
            $aCount = 0;
            foreach ($param->dealCodes as $key => $value) {
                $aCount ++;
                if($value->dealCode === "0"){
                    $aError = FALSE;
                    $aMessage = "You cannot specify default on deals. Please update or remove the deal.";

                    return array(status=> $aError, message=>$aMessage);
                }
            } 
            
            if($aCount === 0){
                $aError = FALSE;
                $aMessage = "Please specify at least one deal on the quote.";

                return array(status=> $aError, message=>$aMessage);
            }else if($aCount > 5){
                $aError = FALSE;
                $aMessage = "You can only be quoted on less than 5 deals. Please reduce number of deals.";

                return array(status=> $aError, message=>$aMessage);
            }
            
            return array(status=> true);
        }
        
        public function validateGenerateQuote($param) {
            $aCount = 0;
            foreach ($param as $key => $value) {
                $aCount ++;
                if($value->dealCode === "0"){
                    $aError = FALSE;
                    $aMessage = "You cannot specify default on deals. Please update or remove the deal.";

                    return array(status=> $aError, message=>$aMessage);
                }
            } 
            
            if($aCount === 0){
                $aError = FALSE;
                $aMessage = "Please specify at least one deal on the quote.";

                return array(status=> $aError, message=>$aMessage);
            }else if($aCount > 5){
                $aError = FALSE;
                $aMessage = "You can only be quoted on less than 5 deals. Please reduce number of deals.";

                return array(status=> $aError, message=>$aMessage);
            }
            
            return array(status=> true);
        }
        
        public function validateClientBasicInformation($param) {
            foreach ($param as $key => $value) {
                if($this->isEmpty($value->entityContent)){
                    $aError = FALSE;
                    $aMessage = ucfirst(strtolower($value->propertyName))." is a compulsory field";

                    return array(status=> $aError, message=>$aMessage);
                }
            } 
            return array(status=> true);
        }
        
        public function validateUpdateSystemUser($data){
            if(!$this->minLengthRequirement($data->userId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Data was not populated correctly"); 
            }elseif($this->isEmpty($data->email) || $this->minLengthRequirement($data->email, self::MAX_EMAIL_LENGTH)) { 
                return array(status=> false, message=>"Email address is mandatory"); 
            }elseif($this->isEmpty($data->systemRole) || !$this->minLengthRequirement($data->systemRole, 1)) { 
                return array(status=> false, message=>"System role is mandatory"); 
            }elseif(!$this->isValidEmailFormat($data->email)){
                return array(status=> false, message=>"Email address is invalid"); 
            }
            return array(status=> true);
        }
        
        public function validateUpdateClient($data){
            if(!$this->minLengthRequirement($data->clientId,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }elseif($this->isEmpty($data->email) || $this->minLengthRequirement($data->email, self::MAX_EMAIL_LENGTH)) { 
                return array(status=> false, message=>"Email address is mandatory"); 
            }elseif(!$this->isValidEmailFormat($data->email)){
                return array(status=> false, message=>"Email address is invalid"); 
            }
            return $this->validateUpdateEntityDetails($data->userDetails);
        }
        
        public function validateUpdateProject($data){
            if(!$this->minLengthRequirement($data->projectId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Project ID data was not populated correctly"); 
            }
            return $this->validateUpdateEntityDetails($data->projectDetails);
        }
        
        public function validateUpdateClientDetails($data){
            if(!$this->minLengthRequirement($data->clientId,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }
            
            return $this->validateClientBasicInformation($data->userDetails);
        }
        
        public function validateUpdateItemDetails($data){
            if(!$this->minLengthRequirement($data->itemId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }
            
            return $this->validateClientBasicInformation($data->itemDetails);
        }
        
        public function validateAddProject($data){
            if(!$this->minLengthRequirement($data->clientId,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->entityType,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Entity type data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->projectStage,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Project stage data was not populated correctly"); 
            }
            
            return $this->validateNewEntityDetails($data->projectDetails);
        }
        
        public function validateDeploymentFileSizeAndExtension($aFileType, $aFileSize){
            if($this->isEmpty($aFileType)){
                return array(status=> false, message=>"Project ZIP is mandatory");
            }else if(!in_array($aFileType, $this->DEPLOYMENT_SUPPORTED_TYPES)){
                return array(status=> false, message=>"File type is not supported. Only ZIP files allowed"); 
            }
            return array(status=> true); 
        }
        
        public function validateFileSizeAndExtension($aFileType, $aFileSize){
            if($this->isEmpty($aFileType)){
                return array(status=> false, message=>"File is mandatory");
            }else if($aFileSize > self::MAX_FILE_SIZE){
                return array(status=> false, message=>"File is too big. Maximum is 5MB"); 
            }else if(!in_array($aFileType, $this->SUPPORTED_TYPES)){
                return array(status=> false, message=>"File type is not supported."); 
            }
            return array(status=> true); 
        }
        
         public function validateFileSizeAndPDFExtension($aFileType, $aFileSize){
            if($this->isEmpty($aFileType)){
                return array(status=> false, message=>"File is mandatory");
            }else if($aFileSize > self::MAX_FILE_SIZE){
                return array(status=> false, message=>"File is too big. Maximum is 5MB"); 
            }else if(!in_array($aFileType, $this->SUPPORTED_PDF_TYPES)){
                return array(status=> false, message=>"File type is not supported."); 
            }
            return array(status=> true); 
        }
        
        public function validateDeployProject($data){
            if(!$this->minLengthRequirement($data->projectId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Project ID is mandotory field"); 
            }
            
            return array(status=> true); 
        }
        
        public function validateAddStageItem($data){
            if(!$this->minLengthRequirement($data->projectId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Project ID data was not populated correctly"); 
            }else if(!$this->minLengthRequirement($data->clientId,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->entityType,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Entity type data was not populated correctly"); 
            }
            
            return $this->validateNewEntityDetails($data->itemDetails);
        }
        
        public function validateNewEntityDetails($param) {
            foreach ($param as $key => $value) {
                if($this->isEmpty($value->entityContent)){
                    $aError = FALSE;
                    $aMessage = ucfirst(strtolower($value->propertyName))." is a compulsory field";

                    return array(status=> $aError, message=>$aMessage);
                }
            } 
            return array(status=> true);
        }
        
        public function validateUpdateEntityDetails($param) {
            foreach ($param as $key => $value) {
                if($this->isEmpty($value->entityContent)){
                    $aError = FALSE;
                    $aMessage = ucfirst(strtolower($value->propertyName))." is a compulsory field";

                    return array(status=> $aError, message=>$aMessage);
                }else if($this->isEmpty($value->entityDetailId)){
                    $aError = FALSE;
                    $aMessage = ucfirst(strtolower($value->propertyName))." data was not populated correctly";

                    return array(status=> $aError, message=>$aMessage);
                }
            } 
            return array(status=> true);
        }
        
        public function validateUpdateCredentials($data){
            if(!$this->minLengthRequirement($data->currentPassword,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Current password is too short"); 
            }elseif(!$this->minLengthRequirement($data->newPassword,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"New password is too short"); 
            }elseif($data->newPassword != $data->confirmPassword){
              return array(status=> false, message=>"Passwords don't match"); 
            }
            return array(status=> true); 
        }
        
        public function validateAddSystemGroup($data){
            if(!$this->minLengthRequirement($data->groupName,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Group name is too short"); 
            }elseif(!$this->minLengthRequirement($data->groupValue,1)){ 
              return array(status=> false, message=>"Group value is too short"); 
            }elseif(!$this->minLengthRequirement($data->groupDescription,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Group description is too short"); 
            }elseif($this->hasSpecialCharacters($data->groupName)){ 
              return array(status=> false, message=>"Group name contains special characters"); 
            }elseif(!is_numeric($data->groupValue)){ 
              return array(status=> false, message=>"Group priority must be a number"); 
            }
            return array(status=> true); 
        }
        
        public function validateAddSystemTicket($data){
            $aIsValidDate = DateTime::createFromFormat("d-m-Y", $data->dueDate);
            if(!$this->minLengthRequirement($data->clientId,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->ticketDescription,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Description is too short. It needs a minimum of 8 characters"); 
            }elseif(!$this->minLengthRequirement($data->dueDate,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Due date is a mandatory field"); 
            }elseif($aIsValidDate === false){ 
              return array(status=> false, message=>"Due date must be specified in the correct format."); 
            }
            
            return array(status=> true); 
        }
        
        public function validateAddDeal($data){
            $aIsValidDueDate = DateTime::createFromFormat("d-m-Y", $data->dueDate);
            $aIsValidStartDate = DateTime::createFromFormat("d-m-Y", $data->startDate);
            if(!$this->minLengthRequirement($data->dealName,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Deal name is too short. It needs a minimum of 8 characters"); 
            }elseif(!$this->minLengthRequirement($data->description,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Description is too short. It needs a minimum of 8 characters"); 
            }elseif(!is_numeric($data->dealPrice)){ 
              return array(status=> false, message=>"Deal price must be a number"); 
            } elseif(!$this->minLengthRequirement($data->startDate,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Start date is a mandatory field"); 
            }elseif(!$this->minLengthRequirement($data->dueDate,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Due date is a mandatory field"); 
            }elseif($aIsValidDueDate === false){ 
              return array(status=> false, message=>"Due date must be specified in the correct format."); 
            }elseif($aIsValidStartDate === false){ 
              return array(status=> false, message=>"Start date must be specified in the correct format."); 
            }
            
            return array(status=> true); 
        }
        
        public function validateUpdateDeal($data){
            $aIsValidDueDate = DateTime::createFromFormat("d-m-Y", $data->dueDate);
            $aIsValidStartDate = DateTime::createFromFormat("d-m-Y", $data->startDate);
            if(!$this->minLengthRequirement($data->dealCode,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Deal code data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->dealName,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Deal name is too short. It needs a minimum of 8 characters"); 
            }elseif(!$this->minLengthRequirement($data->description,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Description is too short. It needs a minimum of 8 characters"); 
            }elseif(!is_numeric($data->dealPrice)){ 
              return array(status=> false, message=>"Deal price must be a number"); 
            } elseif(!$this->minLengthRequirement($data->startDate,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Start date is a mandatory field"); 
            }elseif(!$this->minLengthRequirement($data->dueDate,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Due date is a mandatory field"); 
            }elseif($aIsValidDueDate === false){ 
              return array(status=> false, message=>"Due date must be specified in the correct format."); 
            }elseif($aIsValidStartDate === false){ 
              return array(status=> false, message=>"Start date must be specified in the correct format."); 
            }
            
            return array(status=> true); 
        }
        
        public function validateAddPayment($data){
            $aIsValidDate = DateTime::createFromFormat("d-m-Y", $data->paymentDate);
            if(!$this->minLengthRequirement($data->clientId,self::MIN_CLIENT_ID)){ 
              return array(status=> false, message=>"Client ID data was not populated correctly"); 
            }else if(!$this->minLengthRequirement($data->projectId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Project ID is a mandatory field"); 
            }elseif(!$this->minLengthRequirement($data->paymentReference,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Reference is too short. It needs a minimum of 4 characters"); 
            }elseif(!$this->minLengthRequirement($data->paymentReference,self::MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Reference is too short. It needs a minimum of 4 characters"); 
            }elseif(!$this->minLengthRequirement($data->paymentAmount,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Payment amout is a mandatory field"); 
            }elseif($aIsValidDate === false){ 
              return array(status=> false, message=>"Payment date must be specified in the correct format."); 
            }
            
            return array(status=> true); 
        }
        
        public function validateAddSystemProperty($data){
            if(!$this->minLengthRequirement($data->propertyName,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Property name is too short"); 
            }elseif(!$this->minLengthRequirement($data->propertyValue,1)){ 
              return array(status=> false, message=>"Property priority is too short"); 
            }elseif(!$this->minLengthRequirement($data->propertyDescription,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Property description is too short"); 
            }elseif(!$this->minLengthRequirement($data->propertyGroup,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Group not specified"); 
            }elseif($this->hasSpecialCharacters($data->propertyName)){ 
              return array(status=> false, message=>"Property name contains special characters"); 
            }elseif(!is_numeric($data->propertyValue)){ 
              return array(status=> false, message=>"Property priority must be a number"); 
            }
            return array(status=> true); 
        }
        
         public function validateUpdateSystemProperty($data){
            if(!$this->minLengthRequirement($data->propertyId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->propertyName,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Property name is too short"); 
            }elseif(!$this->minLengthRequirement($data->propertyValue,1)){ 
              return array(status=> false, message=>"Property priority is too short"); 
            }elseif(!$this->minLengthRequirement($data->propertyDescription,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Property description is too short"); 
            }elseif(!$this->minLengthRequirement($data->groupId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Group not specified"); 
            }elseif($this->hasSpecialCharacters($data->propertyName)){ 
              return array(status=> false, message=>"Property name contains special characters"); 
            }elseif(!is_numeric($data->propertyValue)){ 
              return array(status=> false, message=>"Property priority must be a number"); 
            }
            return array(status=> true); 
        }
        
        public function validateUpdateSystemGroup($data){
            if(!$this->minLengthRequirement($data->groupId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Data was not populated correctly"); 
            }elseif(!$this->minLengthRequirement($data->groupName,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Group name is too short"); 
            }elseif(!$this->minLengthRequirement($data->groupValue,1)){ 
              return array(status=> false, message=>"Group value is too short"); 
            }elseif(!$this->minLengthRequirement($data->groupDescription,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Group description is too short"); 
            }elseif($this->hasSpecialCharacters($data->groupName)){ 
              return array(status=> false, message=>"Group name contains special characters"); 
            }elseif(!is_numeric($data->groupValue)){ 
              return array(status=> false, message=>"Group priority must be a number"); 
            }
            return array(status=> true); 
        }
        
        public function validateDeleteSystemGroup($data){
            if(!$this->minLengthRequirement($data->groupId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Data was not populated correctly"); 
            }
            return array(status=> true); 
        }
        
        public function validateDeleteSystemProperty($data){
            if(!$this->minLengthRequirement($data->propertyId,self::MIN_GROUP_NAME_LENGTH)){ 
              return array(status=> false, message=>"Data was not populated correctly"); 
            }
            return array(status=> true); 
        }
        
        public function maxLengthRequirement($aString,$aLength){
            $aString = trim($aString);
            if(strlen($aString) <= $aLength){
                return TRUE;
            }
            return FALSE;
        }
        
        public function minLengthRequirement($aString,$aLength){
            $aString = trim($aString);
            if(strlen($aString) >= $aLength){
                return TRUE;
            }
            return FALSE;
        }
        /**
         * Validates email address
         * 
         * @param String $aEmail
         * @return oolean
         */
        public function isValidEmailFormat($aEmail) {
            if(filter_var($aEmail, FILTER_VALIDATE_EMAIL)) {
                return TRUE;
            }
            return FALSE;
        }
        /**
         * Checks if string has special characters
         * 
         * @param String $param
         * @return boolean
         */
        public function hasSpecialCharacters($param) {
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $param)) {
                return TRUE;
            }
            return FALSE;
        }
        /**
         * Checks if string is empty
         * 
         * @param String aString
         * @return boolean
         */
        public function isEmpty($aString) {
            $aString = trim($aString);
            if(strlen($aString) == 0){
                return TRUE;
            }
            return FALSE;
        }
    }
?>