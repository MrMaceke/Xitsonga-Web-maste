<?php
    date_default_timezone_set('Africa/Johannesburg');


    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

    
    require_once './constants/FeedbackConstants.php';
    require_once './constants/GroupsConstants.php';
    
    require_once './dao/SystemUserDAO.php';
    require_once './dao/SystemGroupDAO.php';
    require_once './dao/SystemPropertyDAO.php';
    require_once './dao/SystemRoleDAO.php';
    require_once './dao/SystemSupportDAO.php';
    require_once './dao/SystemEntityDAO.php';
    require_once './dao/SystemEntityDetailsDAO.php';
    require_once './dao/SystemEntityLinkDAO.php';
    require_once './dao/FinancialPaymentDAO.php';
    require_once './dao/FinancialDealsDAO.php';
    require_once './dao/FinancialQuotesDAO.php';
    
    require_once './validator/InputValidator.php';
    require_once './validator/BusinessDataValidator.php';
    require_once './validator/AccessValidator.php';

    require_once './utils/Logging.php';
    require_once './utils/JsonUtils.php';
    require_once './utils/PHPToJSONArray.php';
    require_once './utils/SendEmail.php';
    
    $aFunction = $_REQUEST['type'];
    $aJSONData = $_REQUEST['data'];
    
    if($_REQUEST['data'] != null){
        $aClientZoneBean = new ClientZoneBean();
        if(true){
            if(method_exists($aClientZoneBean, $aFunction)){
                echo $aClientZoneBean->dynamicFunction($aFunction,$aJSONData);
            }else {
                echo $aClientZoneBean->jsonFeedback->feedback("Function is not supported", FeedbackConstants::FAILED);
            }
        }else{
            echo $aClientZoneBean->jsonFeedback->feedback("System error occured. Failed to start session", FeedbackConstants::FAILED);
        }
    }
    
    class ClientZoneBean {
        public $logging = null;
        public $jsonFeedback = null;
        public $accessValidator = null;
        
        public function ClientZoneBean() {
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
         * Adds a new system user
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewSystemUser($param) {
            $this->logging->startMethod("addNewSystemUser");
            $this->logging->debugObject("User Object",$param);
             
            $aInputValidator = new InputValidator();
            $aBusinessDataValidator = new BusinessDataValidator();
            $aSystemUserDAO = new SystemUserDAO();
            
            $aValidation = $aInputValidator->validateAddSystemUser($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewSystemUser");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aClientInfoValidation = $aInputValidator->validateClientBasicInformation($param->userDetails);
            if(!$aClientInfoValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aClientInfoValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }

            $aUserResults = $aSystemUserDAO->addNewSystemUser($param);
            if($aUserResults['status']){
                $this->logging->debug("addNewClient","Client succesfully added");
             
                $aPHPToJSONArray = new PHPToJSONArray();
                $aSystemEntityDAO = new SystemEntityDAO();
                $data = $aPHPToJSONArray->newEntityJSON($aUserResults[resultsArray][user_id], $param->entityType, $aUserResults[resultsArray][user_key]);
                $this->logging->debugObject("Client Object",$data);

                $aEntityResults = $aSystemEntityDAO->addNewEntity($data);
                if(!$aEntityResults['status']){
                    $this->logging->exitMethod("addNewClient");
                    return $this->jsonFeedback->feedback($aEntityResults['message'], FeedbackConstants::FAILED);
                }

                $this->logging->debug("addNewClient","Entity succesfully added");

                foreach ($param->userDetails as $key => $value) {
                    $detailData = $aPHPToJSONArray->newEntityDetailJSON($data->userId, $aEntityResults[resultsArray][entity_id], $value->propertyId, $value->entityContent);

                    $this->logging->debugObject("Entity Detail Object",$detailData);

                    $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                    $aEntityDetailResults = $aSystemEntityDetailsDAO->addNewEntityDetail($detailData);
                    if(!$aEntityDetailResults['status']){
                        $this->logging->exitMethodWithError("addNewClient",$aEntityDetailResults['message']);
                        return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                    }
                }
                
                $aSendEmail = new SendEmail();
                
                $decryptedPassword = GeneralUtils::decryptPassword($aUserResults['resultsArray']['password'], $aUserResults['resultsArray']['salt']);
                
                $this->logging->debug("Password sent via email: ",$decryptedPassword);
                
                $aSendEmail->sendUserRegisteringEmail($aUserResults['resultsArray'], $decryptedPassword);
                
                $this->logging->exitMethod("addNewSystemUser");
                return $this->jsonFeedback->systemUser($aUserResults['resultsArray'], "System user successfully added");
            }
            $this->logging->exitMethod("addNewSystemUser");
            return $this->jsonFeedback->feedback($aUserResults['message'], FeedbackConstants::FAILED);
        }
        /**
         * Adds a new client
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewClient($param) {
            $this->logging->startMethod("addNewClient");
            $this->logging->debugObject("Client Object",$param);
            
            $aInputValidator = new InputValidator();
            
            $aUserValidation = $aInputValidator->validateAddSystemUser($param);
            if(!$aUserValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aUserValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aClientInfoValidation = $aInputValidator->validateClientBasicInformation($param->userDetails);
            if(!$aClientInfoValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aClientInfoValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemUserDAO = new SystemUserDAO();
            
            $aUserResults = $aSystemUserDAO->addNewSystemUser($param);
            if(!$aUserResults['status']){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aUserResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->debug("addNewClient","Client succesfully added");
             
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $data = $aPHPToJSONArray->newEntityJSON($aUserResults[resultsArray][user_id], $param->entityType, $aUserResults[resultsArray][user_key]);
            $this->logging->debugObject("Client Object",$data);
            
            $aEntityResults = $aSystemEntityDAO->addNewEntity($data);
            if(!$aEntityResults['status']){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aEntityResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->debug("addNewClient","Entity succesfully added");
            
            foreach ($param->userDetails as $key => $value) {
                $detailData = $aPHPToJSONArray->newEntityDetailJSON($data->userId, $aEntityResults[resultsArray][entity_id], $value->propertyId, $value->entityContent);

                $this->logging->debugObject("Entity Detail Object",$detailData);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->addNewEntityDetail($detailData);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("addNewClient",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
            }
            
            $aSendEmail = new SendEmail(); 
            
            $decryptedPassword = GeneralUtils::decryptPassword($aUserResults['resultsArray']['password'], $aUserResults['resultsArray']['salt']);
            $this->logging->debug("password sent via email: ",$decryptedPassword);
            $aSendEmail->sendUserRegisteringEmail($aUserResults['resultsArray'], $decryptedPassword);  

            $this->logging->exitMethod("addNewClient");
            return $this->jsonFeedback->client($aEntityResults['resultsArray'],"Client succesfully added"); 
        }
        /**
         * Adds a new stag item
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function addNewStageItem($param) {
            $this->logging->startMethod("addNewStageItem");
            $this->logging->debugObject("Stage Item Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateAddStageItem($param);
            if(!$aValidation[status]){
                $this->logging->exitMethodWithError("addNewStageItem",$aValidation['message']);
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemUserDAO = new SystemUserDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
             
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserResults['status']) {
                $this->logging->exitMethodWithError("addNewStageItem","Client ID was not found on system");
                return $this->jsonFeedback->feedback("Client ID was not found on system", FeedbackConstants::FAILED);
            }
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aProjectResults['status']) {
                $this->logging->exitMethodWithError("addNewStageItem","Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED);
            }
            
            $StageData = $aPHPToJSONArray->entityLinkQueryJSON($aProjectResults[resultsArray][entity_id], $param->projectStage);
            
            $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkType($StageData);
            if(!$aProjectStageResults['status']) {
                $this->logging->exitMethodWithError("addNewStageItem","Project stage was not found on system");
                return $this->jsonFeedback->feedback("Project stage was not found on system", FeedbackConstants::FAILED);
            }
            
            /**
            *  Project stage item inserts
            */
            $aItemKey = GeneralUtils::generateItemID();
            
            $data = $aPHPToJSONArray->newEntityJSON($aUserResults[resultsArray][user_id], $param->entityType, $aItemKey);
            $this->logging->debugObject("Project stage item entity object",$data);
            
            $aItemEntityResults = $aSystemEntityDAO->addNewEntity($data);
            if(!$aItemEntityResults['status']){
                $this->logging->exitMethod("addNewStageItem");
                return $this->jsonFeedback->feedback($aItemEntityResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->debug("addNewStageItem","Project stage item succesfully added");
            
            foreach ($param->itemDetails as $key => $value) {
                $detailData = $aPHPToJSONArray->newEntityDetailJSON($data->userId, $aItemEntityResults[resultsArray][entity_id], $value->propertyId, $value->entityContent);

                $this->logging->debugObject("Project Entity Detail Object",$detailData);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->addNewEntityDetail($detailData);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("addNewProject",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
            }
            
            $this->logging->debug("addNewStageItem","Project stage item details succesfully added");
            
            /**
             *  Entity Link Inserts
             */
            $aProjectStageResult = null;
            foreach($aProjectStageResults[resultsArray] as $aResult){
                $aProjectStageResult = $aResult;
            }
                        
            $aLinkName = $aProjectStageResult[entity_link_name]."-".$data->entityName;
            
            $linkData = $aPHPToJSONArray->newEntityLinkJSON($aUserResults[resultsArray][user_id], $aProjectStageResult[sub_entity], $aItemEntityResults[resultsArray][entity_id], $param->entityType, $aLinkName);
            $aLinkResults = $aSystemEntityLinkDAO->addNewEntityLink($linkData);
            if(!$aLinkResults['status']){
                $this->logging->exitMethod("addNewStageItem");
                return $this->jsonFeedback->feedback($aLinkResults['message'], FeedbackConstants::FAILED);
            }
            
            /**
             * Notify asigned user
             */
            $aAssignedUserKey = "";
            $aItemName = "";
            foreach ($param->itemDetails as $key => $value) {
                $this->logging->debugObject("Entity Detail Object",$value);
                if($value->propertyName === "Assigned Person"){
                    $aAssignedUserKey = $value->entityContent;
                }else if($value->propertyName === "Item Name"){
                    $aItemName = $value->entityContent;
                }
            }
            
            $aAssignedUserResults = $aSystemUserDAO->findRecordWithClientID($aAssignedUserKey);
            if($aAssignedUserResults['status']) {
                $aFirstName = "";
                $aAssignedUserDetailResults = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aAssignedUserKey);
                foreach($aAssignedUserDetailResults[resultsArray] as $aResult){
                   if($aResult[property_name] === "First Name") {
                       $aFirstName = $aResult[entity_detail_content];
                   }elseif($aResult[property_name] === "Last Name") {
                       $aFirstName = $aFirstName." ".$aResult[entity_detail_content];
                   }
                }
                $aSendEmail = new SendEmail();
               
               $aSendEmail->sendAssignedPersonEmail($aFirstName, $aAssignedUserResults[resultsArray], $aItemKey, $aItemName, $param->projectId, $aProjectName, $aProjectStageResult[property_name]);
            }
            
            $this->logging->exitMethod("addNewStageItem");
            return $this->jsonFeedback->stageItem($aItemEntityResults['resultsArray'], "Project stage item successfully added");
        }
        /**
         * upload project contract
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function uploadProjectContractDocument($param) {
            $this->logging->startMethod("uploadStageItemDocument");
            $this->logging->debugObject("Stage Item Object",$param);
            
            $aInputValidator = new InputValidator();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            
            $aFileType  = $_FILES["file"]["type"];
            $aFileSize  = $_FILES["file"]["size"];
            
            $this->logging->debug("File type",$aFileType);
            
            $aFileValidation = $aInputValidator->validateFileSizeAndPDFExtension($aFileType, $aFileSize);
            if(!$aFileValidation[status]){
                $this->logging->exitMethodWithError("uploadStageItemDocument",$aFileValidation['message']);
                return $this->jsonFeedback->feedback($aFileValidation['message'], FeedbackConstants::FAILED);
            }
            
            if ($_FILES["file"]["error"] > 0) {
                return $this->jsonFeedback->feedback("A critical occured while uploading file", FeedbackConstants::FAILED);
            }
            
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserResults[status]){
                $this->logging->exitMethodWithError("uploadStageItemDocument",$aFileValidation['message']);
                return $this->jsonFeedback->feedback("Client ID data was not populated correctly", FeedbackConstants::FAILED);
            }
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aProjectResults[status]){
                $this->logging->exitMethodWithError("uploadStageItemDocument",$aFileValidation['message']);
                return $this->jsonFeedback->feedback("Project ID was not found in system", FeedbackConstants::FAILED);
            }
            
            $aFileName = strtoupper($param->projectId)." - Contract of agreement.pdf";
                    
            // Move file to specified directory
            move_uploaded_file($_FILES["file"]["tmp_name"], "../contracts/" . $aFileName);
            
            $this->logging->exitMethod("uploadProjectContractDocument");
            return $this->jsonFeedback->feedback("Project contract successfully uploaded.", FeedbackConstants::SUCCESSFUL);
        }
        
        /**
         * Adds a new stag item
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function uploadStageItemDocument($param) {
            $this->logging->startMethod("uploadStageItemDocument");
            $this->logging->debugObject("Stage Item Object",$param);
            
            $aItemKey = GeneralUtils::generateItemID();
            $aInputValidator = new InputValidator();
            
            $aFileName  = $aItemKey."_".$_FILES["file"]["name"];
            $aFileType  = $_FILES["file"]["type"];
            $aFileSize  = $_FILES["file"]["size"];
            
            $this->logging->debug("File type",$aFileType);
            
            $aFileValidation = $aInputValidator->validateFileSizeAndExtension($aFileType, $aFileSize);
            if(!$aFileValidation[status]){
                $this->logging->exitMethodWithError("uploadStageItemDocument",$aFileValidation['message']);
                return $this->jsonFeedback->feedback($aFileValidation['message'], FeedbackConstants::FAILED);
            }
            
            if ($_FILES["file"]["error"] > 0) {
                return $this->jsonFeedback->feedback("A critical occured while uploading file", FeedbackConstants::FAILED);
            }
            
            $aValidation = $aInputValidator->validateAddStageItem($param);
            if(!$aValidation[status]){
                $this->logging->exitMethodWithError("uploadStageItemDocument",$aValidation['message']);
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            // Move file to specified directory
            move_uploaded_file($_FILES["file"]["tmp_name"], "../documents/" . $aFileName);
            
            $aSystemUserDAO = new SystemUserDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
             
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserResults['status']) {
                $this->logging->exitMethodWithError("uploadStageItemDocument","Client ID was not found on system");
                return $this->jsonFeedback->feedback("Client ID was not found on system", FeedbackConstants::FAILED);
            }
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aProjectResults['status']) {
                $this->logging->exitMethodWithError("uploadStageItemDocument","Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED);
            }
            
            $StageData = $aPHPToJSONArray->entityLinkQueryJSON($aProjectResults[resultsArray][entity_id], $param->projectStage);
            
            $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkType($StageData);
            if(!$aProjectStageResults['status']) {
                $this->logging->exitMethodWithError("uploadStageItemDocument","Project stage was not found on system");
                return $this->jsonFeedback->feedback("Project stage was not found on system", FeedbackConstants::FAILED);
            }
            
            /**
            *  Project stage item inserts
            */
            
            $data = $aPHPToJSONArray->newEntityJSON($aUserResults[resultsArray][user_id], $param->entityType, $aItemKey);
            $this->logging->debugObject("Project stage item entity object",$data);
            
            $aItemEntityResults = $aSystemEntityDAO->addNewEntity($data);
            if(!$aItemEntityResults['status']){
                $this->logging->exitMethod("uploadStageItemDocument");
                return $this->jsonFeedback->feedback($aItemEntityResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->debug("addNewStageItem","Project stage item succesfully added");
            
            foreach ($param->itemDetails as $key => $value) {
                $aContent = $value->entityContent;
                if($value->propertyName === "Item Description"){
                    $aContent = $aFileName;
                }
                
                $detailData = $aPHPToJSONArray->newEntityDetailJSON($data->userId, $aItemEntityResults[resultsArray][entity_id], $value->propertyId, $aContent);

                $this->logging->debugObject("Project Entity Detail Object",$detailData);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->addNewEntityDetail($detailData);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("uploadStageItemDocument",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
            }
            
            $this->logging->debug("uploadStageItemDocument","Project stage item details succesfully added");
            
            /**
             *  Entity Link Inserts
             */
            $aProjectStageResult = null;
            foreach($aProjectStageResults[resultsArray] as $aResult){
                $aProjectStageResult = $aResult;
            }
                        
            $aLinkName = $aProjectStageResult[entity_link_name]."-".$data->entityName;
            
            $linkData = $aPHPToJSONArray->newEntityLinkJSON($aUserResults[resultsArray][user_id], $aProjectStageResult[sub_entity], $aItemEntityResults[resultsArray][entity_id], $param->entityType, $aLinkName);
            $aLinkResults = $aSystemEntityLinkDAO->addNewEntityLink($linkData);
            if(!$aLinkResults['status']){
                $this->logging->exitMethod("uploadStageItemDocument");
                return $this->jsonFeedback->feedback($aLinkResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("uploadStageItemDocument");
            return $this->jsonFeedback->stageItem($aItemEntityResults['resultsArray'], "Document successfully uploaded");
        }
        /**
         * Deploy Project To QA
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function deployProjectToQA($param) {
            $this->logging->startMethod("deployProjectToQA");
            $this->logging->debugObject("Deployment Object",$param);
            
            $aDeploymentKey = GeneralUtils::generateDeploymentID();
            $aInputValidator = new InputValidator();
            
            $aFileName  = $aDeploymentKey.".zip";
            $aFileType  = $_FILES["file"]["type"];
            $aFileSize  = $_FILES["file"]["size"];
            
            $this->logging->debug("File type",$aFileType);
            
            $aFileValidation = $aInputValidator->validateDeploymentFileSizeAndExtension($aFileType, $aFileSize);
            if(!$aFileValidation[status]){
                $this->logging->exitMethodWithError("deployProjectToQA",$aFileValidation['message']);
                return $this->jsonFeedback->feedback($aFileValidation['message'], FeedbackConstants::FAILED);
            }
            
            if ($_FILES["file"]["error"] > 0) {
                return $this->jsonFeedback->feedback("A critical occured while uploading file", FeedbackConstants::FAILED);
            }
            
            $aValidation = $aInputValidator->validateDeployProject($param);
            if(!$aValidation[status]){
                $this->logging->exitMethodWithError("deployProjectToQA",$aValidation['message']);
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
             
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aProjectResults['status']) {
                $this->logging->exitMethodWithError("deployProjectToQA","Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED);
            }
            
            $aEnvironmentReslts = $aSystemPropertyDAO->findRecordByPropertyId($param->environment);
            if(!$aEnvironmentReslts['status']) {
                $this->logging->exitMethodWithError("deployProjectToQA","Environment data not poplated correctly");
                return $this->jsonFeedback->feedback("Environment data not poplated correctly", FeedbackConstants::FAILED);
            }
            
            $aDeploymentPropertyResults = $aSystemPropertyDAO->findRecordByPropertyName("Deployment");
            if(!$aDeploymentPropertyResults['status']) {
                $this->logging->exitMethodWithError("deployProjectToQA","Environment data not poplated correctly");
                return $this->jsonFeedback->feedback("Environment data not poplated correctly", FeedbackConstants::FAILED);
            }
            
            // Move file to specified directory
            $aZIPFile = strtolower($_SERVER['DOCUMENT_ROOT'].$aEnvironmentReslts['resultsArray']['property_description'])."/" . $aFileName;
            
            if(!move_uploaded_file($_FILES["file"]["tmp_name"], $aZIPFile)) {
                $this->logging->exitMethodWithError("deployProjectToQA","System failed to unpack zip file");
                return $this->jsonFeedback->feedback("System failed to move file to deploynator. Please try again", FeedbackConstants::FAILED);
            }
            
            $aZIPObject = new ZipArchive();
            if(!$aZIPObject->open($aZIPFile)) {
                $this->logging->exitMethodWithError("deployProjectToQA","System failed to unpack zip file");
                return $this->jsonFeedback->feedback("System failed to unpack zip file. Please try again", FeedbackConstants::FAILED);
            }
            
            $aZIPObject->extractTo($_SERVER['DOCUMENT_ROOT']."/../qa/".strtolower($aEnvironmentReslts['resultsArray']['property_name'])."/".strtolower($aProjectResults['resultsArray']['entity_name'])); 
            
            $aZIPObject->close();
            
            /**
            *  Project deployment inserts
            */
            $data = $aPHPToJSONArray->newEntityJSON($aProjectResults[resultsArray][user_id], $aEnvironmentReslts[resultsArray][property_id], $aDeploymentKey);
            $this->logging->debugObject("Project deployment entity object",$data);
            
            $aDeploymentEntityResults = $aSystemEntityDAO->addNewEntity($data);
            if(!$aDeploymentEntityResults['status']){
                $this->logging->exitMethod("deployProjectToQA");
                return $this->jsonFeedback->feedback($aDeploymentEntityResults['message'], FeedbackConstants::FAILED);
            }
            
            $aLinkName = $aProjectResults[resultsArray][entity_name]."-".$aDeploymentKey;
            
            $linkData = $aPHPToJSONArray->newEntityLinkJSON($aProjectResults[resultsArray][user_id], $aProjectResults[resultsArray][entity_id], $aDeploymentEntityResults[resultsArray][entity_id], $aDeploymentPropertyResults[resultsArray][property_id], $aLinkName);
            $aLinkResults = $aSystemEntityLinkDAO->addNewEntityLink($linkData);
            if(!$aLinkResults['status']){
                $this->logging->exitMethod("deployProjectToQA");
                return $this->jsonFeedback->feedback($aLinkResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("deployProjectToQA");
            return $this->jsonFeedback->feedback("Deployment succesfully", FeedbackConstants::SUCCESSFUL);
        }
        
        private function deployProjectToQAJSONP($param) {
            header("Access-Control-Allow-Origin: " ."*");
            header('Content-Type: application/json; charset=utf-8');
            return $this->deployProjectToQA($param);
        }
        /**
         * Clears system logfile
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function clearLogs($param) {
            /**
            * Doesn't make sent to log this method
            */
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
           
            $this->logging->clearLogs();
            
            return $this->jsonFeedback->feedback("Logs successfully cleared", FeedbackConstants::SUCCESSFUL);
        }
        /**
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function checkIfCurrentStageHasPendingItems($param) {
            $this->logging->startMethod("checkIfCurrentStageHasPendingItems");
            $this->logging->debugObject("Project Object",$param);
            
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemPropertyDAO = new SystemPropertyDAO();
             
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethodWithError("checkIfCurrentStageHasPendingItems", "Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED); 
            }
            
            $StageData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aEntityResults[resultsArray][entity_id], GroupsConstants::PROJECT_STAGES);
            
            $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($StageData);
            if(!$aProjectStageResults['status']) {
                $this->logging->exitMethodWithError("progressProjectToNextStage","Project stage was not found on system");
                return $this->jsonFeedback->feedback("Project was not initiated correctly. No stage was found for project", FeedbackConstants::WARNING); 
            }

            $aProjectStageResult = null;
            foreach($aProjectStageResults[resultsArray] as $aResult){
                $aProjectStageResult = $aResult;
            }
            
            $aItemsData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aProjectStageResult[sub_entity], GroupsConstants::ITEM_TYPES);
            
            $aStageItemsResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($aItemsData);
            if($aStageItemsResults['status']) {
                foreach($aStageItemsResults[resultsArray] as $pLink){
                    $aEntityId    = $pLink['sub_entity'];
                    $aEntityResults = $aSystemEntityDAO->findRecordWithID($aEntityId);
                    if($aEntityResults['status']) {
                        $aStageId = $aEntityResults['resultsArray']['entity_name'];
                        $aItemType = $aEntityResults['resultsArray']['property_name'];
                        $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                        if($aDetailsResults['status']) {
                            $aDetailsArray = $aDetailsResults[resultsArray];
                            foreach($aDetailsArray as $aDetail){
                                $aContent = $aDetail['entity_detail_content'];
                                if(is_numeric($aContent)){
                                    $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                                    if($aPropertyResults['status']){
                                        $aContent = $aPropertyResults['resultsArray']['property_name'];
                                    }
                                }
                                
                                if($aDetail['property_name'] === "Item Status" && $aContent === "Task Pending") {
                                    $this->logging->exitMethod("checkIfCurrentStageHasPendingItems");
                                    return $this->jsonFeedback->feedback("Current project stage has task $aStageId still pending", FeedbackConstants::FAILED); 
                                }
                            }
                        }
                    }
                }
            }
            
            $this->logging->exitMethod("checkIfCurrentStageHasPendingItems");
            return $this->jsonFeedback->feedback("Current project stage has no pending tasks", FeedbackConstants::SUCCESSFUL); 
        }
         /**
         * Reassigns quote to project
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function reassignQuoteToProject($param) {
            $this->logging->startMethod("reassignQuoteToProject");
            $this->logging->debugObject("Ticket bject",$param);

            $aSystemEntityDAO = new SystemEntityDAO();
            $aFinancialQuotesDAO = new FinancialQuotesDAO();            
                        
            $aQuoteResults = $aFinancialQuotesDAO->findRecordByQuoteNumber($param->quoteNumber);
            if(!$aQuoteResults[status]){
                $this->logging->exitMethod("reassignQuoteToProject");
                return $this->jsonFeedback->feedback("Quote number not found in system.", FeedbackConstants::FAILED);
            }
            
            $aExternalQuoteProject = "P000001";
            if($aQuoteResults[resultsArray][project_id] != $aExternalQuoteProject) {
                return $this->jsonFeedback->feedback("Quote already assigned to project ".$aQuoteResults[resultsArray][project_id], FeedbackConstants::FAILED);
            }
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aProjectResults[status]){
                $this->logging->exitMethod("reassignQuoteToProject");
                return $this->jsonFeedback->feedback("Project ID not found in system.", FeedbackConstants::FAILED);
            }
            
            $aRemoveResults = $aFinancialQuotesDAO->deleteQuoteForProject($param->projectId);
            if($aRemoveResults[status]){
                $aUpdateResults = $aFinancialQuotesDAO->updateQuoteProjectId($param);
                if($aUpdateResults[status]){
                    $this->logging->exitMethod("reassignQuoteToProject");
                    return $this->jsonFeedback->feedback("Quote successfully reassigned to ".$param->projectId, FeedbackConstants::SUCCESSFUL);
                }
            }
            
            $this->logging->exitMethod("reassignQuoteToProject");
            return $this->jsonFeedback->feedback("System failed ti reassign quote. Please try again later", FeedbackConstants::FAILED);
        }
        /**
         * Progresses project ticket to next stage
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function progressSupportTicket($param) {
            $this->logging->startMethod("progressSupportTicket");
            $this->logging->debugObject("Ticket bject",$param);
            
            $aSystemSupportDAO = new SystemSupportDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            
            $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTicket($param->supportId);
            if(!$aSupportResults['status']) {
                $this->logging->exitMethod("retrieveSystemSupportTicket");
                return $this->jsonFeedback->feedback("Support ticket not found in system.", FeedbackConstants::FAILED);  
            }
            
            $aCurrentStatus = $aSupportResults[resultsArray][support_status];
            
            if($aCurrentStatus === "3") {
                $this->logging->exitMethod("retrieveSystemSupportTicket");
                return $this->jsonFeedback->feedback("Support ticket already completed.", FeedbackConstants::FAILED); 
            } 
            
            $aCurrentStatus = $aCurrentStatus + 1;
            $aTicketData = $aPHPToJSONArray->updateTicketStatusJSON($aCurrentStatus, $param->supportId);
            
            $aUpdateResults  = $aSystemSupportDAO->updateSystemSupportTicketStatusByTicketId($aTicketData);
            if(!$aUpdateResults['status']) {
                $this->logging->exitMethod("retrieveSystemSupportTicket");
                return $this->jsonFeedback->feedback("System failed to progress ticket to next status. Please try again later", FeedbackConstants::FAILED);  
            
                
            }
            $aStatusDescription = "inititated";
            
            if($aCurrentStatus === 2){
                $aStatusDescription = "assigned";
            }else if($aCurrentStatus === 3){
                $aStatusDescription = "completed";
            }
            
            $this->logging->exitMethod("progressSupportTicket");
            return $this->jsonFeedback->feedback("Ticket progressed to ".$aStatusDescription, FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Progresses project to next stage
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function progressProjectToNextStage($param) {
            $this->logging->startMethod("progressProjectToNextStage");
            $this->logging->debugObject("Project Object",$param);
            
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aSystemEntityDetails = new SystemEntityDetailsDAO();
            $aBusinessDataValidator = new BusinessDataValidator();
            
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("progressProjectToNextStage");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }

            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethod("progressProjectToNextStage");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED);
            }
            
            /**
             * Check if stage has pending items
             */
            $aHasPendingTasksJSON = json_decode($this->checkIfCurrentStageHasPendingItems($param));
            if($aHasPendingTasksJSON->status === FeedbackConstants::FAILED) {
                return $this->jsonFeedback->feedback($aHasPendingTasksJSON->message, FeedbackConstants::FAILED);
            }
            
            /**
             * Find current stage
             */
            $StageData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aEntityResults[resultsArray][entity_id], GroupsConstants::PROJECT_STAGES);
            
            $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($StageData);
            if(!$aProjectStageResults['status']) {
                $this->logging->exitMethodWithError("progressProjectToNextStage","Project stage was not found on system");
                return $this->jsonFeedback->feedback("Project was not initiated correctly. No stage was found for project", FeedbackConstants::WARNING); 
            }
            
            $aCurrentStageId = "";
            foreach ($aProjectStageResults[resultsArray] as $key => $value) {
                $aCurrentStageId = $value["entity_link_type"];
            }
            
            $aPropertiesResults = $aSystemPropertyDAO->retrieveSystemPropertiesByGroupName(GroupsConstants::PROJECT_STAGES);
            if(!$aPropertiesResults['status']) {
                $this->logging->exitMethodWithError("progressProjectToNextStage","System Properties not found");
                return $this->jsonFeedback->feedback("System was not initiated correctly. System Properties not found", FeedbackConstants::WARNING); 
            }
            
            $aNextStageId = "";
            $aStageName = "";
            $aEmailMessage = "";
            $aCurrentStageFound = FALSE;
            foreach ($aPropertiesResults[resultsArray] as $key => $value) {
                if($aCurrentStageFound){
                    $aStageName = $value["property_name"];
                    $aEmailMessage = $value["property_description"];
                    $aNextStageId = $value["property_id"];
                    break;
                }elseif($value[property_id] === $aCurrentStageId){
                    $aCurrentStageFound = TRUE;
                }
            }
            
            /**
             * Check if last stage
             */
            $aLastStage = FALSE;
            if($aCurrentStageFound && $aNextStageId === ""){
               $aLastStage = TRUE;
            }
            
            if($aLastStage) {
                $aReleaseResults = $aSystemPropertyDAO->findRecordByPropertyName("Project Status");
                if(!$aReleaseResults['status']) {
                    $this->logging->exitMethodWithError("progressProjectToNextStage","System Properties not found");
                    return $this->jsonFeedback->feedback("System was not initiated correctly. System Properties not found", FeedbackConstants::FAILED); 
                }
                
                $aStatusResults = $aSystemPropertyDAO->findRecordByPropertyName("Project Completed");
                if(!$aStatusResults['status']) {
                    $this->logging->exitMethodWithError("progressProjectToNextStage","System Properties not found");
                    return $this->jsonFeedback->feedback("System was not initiated correctly. System Properties not found", FeedbackConstants::FAILED); 
                }
                
                $detailData = $aPHPToJSONArray->newEntityDetailJSON($aEntityResults[resultsArray][user_id], $aEntityResults[resultsArray][entity_id],$aReleaseResults[resultsArray][property_id], $aStatusResults[resultsArray][property_id]);

                $this->logging->debugObject("Project Entity Detail Object",$detailData);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->addNewEntityDetail($detailData);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("progressProjectToNextStage",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback("System failed to complete project. Please try again later", FeedbackConstants::FAILED);
                }
            }
            
            if(!$aLastStage) {
                /**
                 *  Project stage Inserts
                 */
                $aProjectStageKey = GeneralUtils::generateProjectStageID();

                $projectData = $aPHPToJSONArray->newEntityJSON($aEntityResults[resultsArray][user_id], $aNextStageId, $aProjectStageKey);
                $this->logging->debugObject("Project stage entity object",$projectData);

                $aStageEntityResults = $aSystemEntityDAO->addNewEntity($projectData);
                if(!$aStageEntityResults['status']){
                    $this->logging->exitMethod("progressProjectToNextStage");
                    return $this->jsonFeedback->feedback($aStageEntityResults['message'], FeedbackConstants::FAILED);
                }

                /**
                 *  Entity Link Inserts
                 */ 
                $aLinkName = $aEntityResults[resultsArray][entity_name]."-".$projectData->entityName;

                $linkData = $aPHPToJSONArray->newEntityLinkJSON($aEntityResults[resultsArray][user_id], $aEntityResults[resultsArray][entity_id], $aStageEntityResults[resultsArray][entity_id], $aNextStageId, $aLinkName);
                $aLinkResults = $aSystemEntityLinkDAO->addNewEntityLink($linkData);
                if(!$aLinkResults['status']){
                    $this->logging->exitMethod("progressProjectToNextStage");
                    return $this->jsonFeedback->feedback($aLinkResults['message'], FeedbackConstants::FAILED);
                }
            }
            /**
             *  Send an email to the user
             */
            $aSendEmail = new SendEmail();
            
            $aProjectName = "";
            $aProjectRelease = "";
            $aProjectDetails = $aSystemEntityDetails->findRecordsForEntityByEntityName($aEntityResults[resultsArray][entity_name]);
            if($aProjectDetails[status]) {
                foreach ($aProjectDetails[resultsArray] as $key => $value) {
                    
                    if($value[property_name] === "Project Name"){
                        $aProjectName = $value[entity_detail_content];
                    }else if($value[property_name] === "Release Forecast"){
                        $aProjectRelease = $value[entity_detail_content];
                    }
                }
            } 
            
            $aUserKey = "";
            $aUserResults = $aSystemUserDAO->findRecordWithUserID($aEntityResults[resultsArray][user_id]);
            if($aUserResults[status]) {
                $aUserKey = $aUserResults[resultsArray][user_key];
            }
            
            $aFirstName = "";
            $aLastName = "";
            $aUserArray = $aSystemEntityDetails->findRecordsForEntityByEntityName($aUserKey);
            if($aUserArray[status]) {
                foreach ($aUserArray[resultsArray] as $key => $value) {
                    if($value[property_name] === "First Name"){
                        $aFirstName = $value[entity_detail_content];
                    }else if($value[property_name] === "Last Name"){
                        $aLastName = $value[entity_detail_content];
                    }
                }
            }
            
            if($aLastStage) {
                $aSendEmail->sendCompletedProjectEmail($aFirstName." ".$aLastName, $aUserResults[resultsArray], $aEntityResults[resultsArray][entity_name], $aProjectName, $aProjectRelease, $aStatusResults[resultsArray][property_name]);

                $this->logging->exitMethod("progressProjectToNextStage");
                return $this->jsonFeedback->feedback("Project successfully completed", FeedbackConstants::SUCCESSFUL);
            } else {
                $aSendEmail->sendProgressProjectEmail($aFirstName." ".$aLastName, $aUserResults[resultsArray], $aEmailMessage, $aStageName, $aEntityResults[resultsArray][entity_name], $aProjectName, $aProjectRelease);

                $this->logging->exitMethod("progressProjectToNextStage");
                return $this->jsonFeedback->feedback("Project successfully progressed to next stage", FeedbackConstants::SUCCESSFUL);
            }  
        }
        /**
         * Adds a new project
         *
         * @param JSONObject param
         * @return JSONObject
         */
        private function addNewProject($param) {
            $this->logging->startMethod("addNewProject");
            $this->logging->debugObject("Project Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateAddProject($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewProject");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemUserDAO = new SystemUserDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
             
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserResults['status']) {
                $this->logging->exitMethod("addNewProject");
                return $this->jsonFeedback->feedback("Client ID was not found on system", FeedbackConstants::FAILED);
            }
            
            /**
             *  Project Inserts
             */
            $aProjectKey = GeneralUtils::generateProjectID();
            
            $data = $aPHPToJSONArray->newEntityJSON($aUserResults[resultsArray][user_id], $param->entityType, $aProjectKey);
            $this->logging->debugObject("Project entity object",$data);
            
            $aEntityResults = $aSystemEntityDAO->addNewEntity($data);
            if(!$aEntityResults['status']){
                $this->logging->exitMethod("addNewProject");
                return $this->jsonFeedback->feedback($aEntityResults['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->debug("addNewProject","Project entity succesfully added");
            $aProjectName = "";
            $aProjectRelease = "";
            foreach ($param->projectDetails as $key => $value) {
                $detailData = $aPHPToJSONArray->newEntityDetailJSON($data->userId, $aEntityResults[resultsArray][entity_id], $value->propertyId, $value->entityContent);

                $this->logging->debugObject("Project Entity Detail Object",$detailData);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->addNewEntityDetail($detailData);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("addNewProject",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
                
                if($value->propertyName === "Project Name"){
                    $aProjectName = $value->entityContent;
                }else{
                    if($value->propertyName === "Release Forecast"){
                        $aProjectRelease = $value->entityContent;
                    }
                }
            }
            
            /**
             *  Project stage Inserts
             */
            $aProjectStageKey = GeneralUtils::generateProjectStageID();
            
            $projectData = $aPHPToJSONArray->newEntityJSON($aUserResults[resultsArray][user_id], $param->projectStage, $aProjectStageKey);
            $this->logging->debugObject("Project stage entity object",$projectData);
            
            $aStageEntityResults = $aSystemEntityDAO->addNewEntity($projectData);
            if(!$aStageEntityResults['status']){
                $this->logging->exitMethod("addNewProject");
                return $this->jsonFeedback->feedback($aStageEntityResults['message'], FeedbackConstants::FAILED);
            }
            
            /**
             *  Entity Link Inserts
             */ 
            $aLinkName = $data->entityName."-".$projectData->entityName;
            
            $linkData = $aPHPToJSONArray->newEntityLinkJSON($aUserResults[resultsArray][user_id], $aEntityResults[resultsArray][entity_id], $aStageEntityResults[resultsArray][entity_id], $param->projectStage, $aLinkName);
            $aLinkResults = $aSystemEntityLinkDAO->addNewEntityLink($linkData);
            if(!$aLinkResults['status']){
                $this->logging->exitMethod("addNewProject");
                return $this->jsonFeedback->feedback($aLinkResults['message'], FeedbackConstants::FAILED);
            }
            
            /**
             * Send an email to client
            */
            $aSendEmail = new SendEmail();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            
            $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserResults[resultsArray][user_key]);
            $aFirstName ="";
            $aLastName ="";
            if($aUserDetails['status']){
                foreach ($aUserDetails[resultsArray] as $key => $value) {
                    
                    if($value[property_name] === "First Name"){
                        $aFirstName = $value[entity_detail_content];
                    }else if($value[property_name] === "Last Name"){
                        $aLastName = $value[entity_detail_content];
                    }
                }
            }
            
            $aSendEmail->sendProjectInitiatedEmail($aFirstName. " ".$aLastName, $aUserResults[resultsArray], $aProjectKey, $aProjectName, $aProjectRelease);
            
            $this->logging->exitMethod("addNewProject");
            return $this->jsonFeedback->feedback("Project successfully added", FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Adds a new system ticket
         *
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewTicket($param) {
            $this->logging->startMethod("addNewTicket");
            $this->logging->debugObject("Ticket Object",$param);
            
            $aSystemSupportDAO = new SystemSupportDAO();
            $aInputValidator = new InputValidator();
            
            $aValidation = $aInputValidator->validateAddSystemTicket($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewTicket");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSupportResults = $aSystemSupportDAO->addSupportTicket($param);
            if($aSupportResults['status']){
                $this->logging->exitMethod("addNewTicket");
                return $this->jsonFeedback->supportTicket($aSupportResults['resultsArray'], "Support ticket successfully added");
            }
            
            $this->logging->exitMethod("addNewTicket");
            return $this->jsonFeedback->feedback($aSupportResults['message'], FeedbackConstants::FAILED);
        }
        /**
         * Adds a new system group
         *
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewSystemGroup($param) {
            $this->logging->startMethod("addNewSystemGroup");
            $this->logging->debugObject("Group Object",$param);
            
            $aInputValidator = new InputValidator();

            $aValidation = $aInputValidator->validateAddSystemGroup($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewSystemGroup");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewSystemGroup");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemGroupDAO = new SystemGroupDAO();
            $aGroupResults = $aSystemGroupDAO->addNewSystemGroup($param);
            if($aGroupResults['status']){
                $this->logging->exitMethod("addNewSystemGroup");
                return $this->jsonFeedback->systemGroup($aGroupResults['resultsArray'], "System group successfully added");
            }
            $this->logging->exitMethod("addNewSystemGroup");
            return $this->jsonFeedback->feedback($aGroupResults['message'], FeedbackConstants::FAILED);
        }
        /**
         * Updates deal
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateDeal($param) {
            $this->logging->startMethod("updateDeal");
            $this->logging->debugObject("Deal Object",$param);
            
            $aFinancialDealsDAO = new FinancialDealsDAO();
            $aBusinessDataValidator = new BusinessDataValidator();
            $aInputValidator = new InputValidator();
            
            $aValidation = $aInputValidator->validateUpdateDeal($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateDeal");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("updateDeal");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aDealResults = $aFinancialDealsDAO->updateDeal($param);
            if($aDealResults['status']){
                $this->logging->exitMethod("updateDeal");
                return $this->jsonFeedback->deal($aDealResults['resultsArray'], "Financial deal successfully updated");
            }
            $this->logging->exitMethod("addNewDeal");
            return $this->jsonFeedback->feedback($aDealResults[message], FeedbackConstants::FAILED);
        }
        /**
         * Adds a new deal
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewDeal($param) {
            $this->logging->startMethod("addNewDeal");
            $this->logging->debugObject("Deal Object",$param);
            
            $aFinancialDealsDAO = new FinancialDealsDAO();
            $aBusinessDataValidator = new BusinessDataValidator();
            $aInputValidator = new InputValidator();
            
            $aValidation = $aInputValidator->validateAddDeal($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewDeal");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewDeal");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aDealResults = $aFinancialDealsDAO->addNewDeal($param);
            if($aDealResults['status']){
                $this->logging->exitMethod("addNewDeal");
                return $this->jsonFeedback->deal($aDealResults['resultsArray'], "Financial deal successfully added");
            }
            $this->logging->exitMethod("addNewDeal");
            return $this->jsonFeedback->feedback("System failed to add deal. Please try again later", FeedbackConstants::FAILED);
        }
        /**
         * Adds a new system property
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function addNewSystemProperty($param) {
            $this->logging->startMethod("addNewSystemProperty");
            $this->logging->debugObject("Property Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateAddSystemProperty($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("addNewSystemProperty");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewSystemProperty");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aPropertyResults = $aSystemPropertyDAO->addNewSystemProperty($param);
            if($aPropertyResults['status']){
                $this->logging->exitMethod("addNewSystemProperty");
                return $this->jsonFeedback->systemProperty($aPropertyResults['resultsArray'], "System property successfully added");
            }
            $this->logging->exitMethod("addNewSystemProperty");
            return $this->jsonFeedback->feedback($aPropertyResults['message'], FeedbackConstants::FAILED);
        }
        /**
         * Updates a new system group
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateSystemGroup($param) {
            $this->logging->startMethod("updateSystemGroup");
            $this->logging->debugObject("Group Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateSystemGroup($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateSystemGroup");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("updateSystemGroup");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemGroupDAO = new SystemGroupDAO();
            $aGroupResults = $aSystemGroupDAO->updateSystemGroup($param);
            if($aGroupResults['status']){
                $this->logging->exitMethod("updateSystemGroup");
                return $this->jsonFeedback->feedback("System group successfully updated", FeedbackConstants::SUCCESSFUL);
            }
            $this->logging->exitMethod("updateSystemGroup");
            return $this->jsonFeedback->feedback($aGroupResults['message'], FeedbackConstants::FAILED);
        }
        /**
         * Updates a system property
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateSystemProperty($param) {
            $this->logging->startMethod("updateSystemProperty");
            $this->logging->debugObject("Property Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateSystemProperty($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateSystemProperty");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("updateSystemProperty");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aPropertyResults = $aSystemPropertyDAO->updateSystemProperty($param);
            if($aPropertyResults['status']){
                $this->logging->exitMethod("updateSystemProperty");
                return $this->jsonFeedback->feedback("System property successfully updated", FeedbackConstants::SUCCESSFUL);
            }
            $this->logging->exitMethod("updateSystemProperty");
            return $this->jsonFeedback->feedback($aPropertyResults['message'], FeedbackConstants::FAILED);
        }
        /**
         * Removes a system group
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function deleteSystemGroup($param) {
            $this->logging->startMethod("deleteSystemGroup");
            $this->logging->debugObject("Group Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateDeleteSystemGroup($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("deleteSystemGroup");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("deleteSystemGroup");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemGroupDAO = new SystemGroupDAO();
            $aGroupResults = $aSystemGroupDAO->deleteSystemGroup($param);
            if($aGroupResults['status']){
                $this->logging->exitMethod("deleteSystemGroup");
                return $this->jsonFeedback->feedback("System group successfully deleted", FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("deleteSystemGroup");
            return $this->jsonFeedback->feedback("System failed to deleted system group", FeedbackConstants::FAILED);
        }
        /**
         * Removes a system property
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function deleteSystemProperty($param) {
            $this->logging->startMethod("deleteSystemProperty");
            $this->logging->debugObject("Property Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateDeleteSystemProperty($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("deleteSystemProperty");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("deleteSystemProperty");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aPropertyResults = $aSystemPropertyDAO->deleteSystemProperty($param);
            if($aPropertyResults['status']){
                $this->logging->exitMethod("deleteSystemProperty");
                return $this->jsonFeedback->feedback("System property successfully deleted", FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("deleteSystemProperty");
            return $this->jsonFeedback->feedback("System failed to deleted system property", FeedbackConstants::FAILED);
        }
        /**
         * Validates user credentials
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function loginSystemUser($param) {
            $this->logging->startMethod("loginSystemUser");
            $this->logging->debugObject("Login Credentials",$param);
            
            $aSystemUserDAO = new SystemUserDAO();
            $aResult = $aSystemUserDAO->validateSystemUserCredentials($param);
            
            if($aResult['status'] == true){
                $aUserRecord = $aResult['resultsArray'];
                
                $DTOSystemUser = new DTOSystemUser();
                
                $DTOSystemUser->setUserID($aUserRecord[user_id]);
                $DTOSystemUser->setUserKey($aUserRecord[user_key]);
                $DTOSystemUser->setEmail($aUserRecord[email]);
                $DTOSystemUser->setRoleName($aUserRecord[role_name]);
                $DTOSystemUser->setIsActiveSession(true);
                
                $_SESSION['SystemUserSession'] = serialize($DTOSystemUser);

                $this->logging->debugObject("Session (User Information)",serialize($DTOSystemUser));
                $this->logging->exitMethod("loginSystemUser");
                return $this->jsonFeedback->feedback("resources/", FeedbackConstants::SUCCESSFUL); 
            }
            
            $this->logging->exitMethod("loginSystemUser");
            return $this->jsonFeedback->feedback($aResult['message'], FeedbackConstants::FAILED); 
        }
        /**
         * Resets user password
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function resetUserPassword($param) {
            $this->logging->startMethod("resetUserPassword");
            $this->logging->debugObject("User Object",$param);
             
            $aSystemUserDAO = new SystemUserDAO();
            $aSendEmail = new SendEmail();
            $aPasswordRecord = $aSystemUserDAO->findPasswordRecordWithClientID($param->clientId);
            if($aPasswordRecord['status']){
                $aUserRecord = $aSystemUserDAO->findRecordWithClientID($param->clientId);
                if(!$aUserRecord['status']){
                    return $this->jsonFeedback->feedback("Password not reset. User was not found on system", FeedbackConstants::FAILED);
                }
                $decryptedPassword = GeneralUtils::decryptPassword($aPasswordRecord['resultsArray']['password'], $aPasswordRecord['resultsArray']['salt']);
                $aSendEmail->sendUserResetPasswordEmail($aUserRecord['resultsArray'], $decryptedPassword);
                
                $this->logging->exitMethod("resetUserPassword");
                return $this->jsonFeedback->feedback("Password sent to user", FeedbackConstants::SUCCESSFUL);
            }
            $this->logging->exitMethod("resetUserPassword");
            return $this->jsonFeedback->feedback("Password not reset. User was not found on system", FeedbackConstants::FAILED);
        }
        /**
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemUserInformation($param) {
            $this->logging->startMethod("retrieveSystemUserInformation");
            
            $aSystemUser = $this->accessValidator->getSystemUser();
            if($aSystemUser->isActiveSession()){
                $this->logging->exitMethod("retrieveSystemUserInformation");
                return $this->jsonFeedback->systemDefaultUser($aSystemUser, "System user information Succesfully retrieved");
            }
            $this->logging->exitMethod("retrieveSystemUserInformation");
            return $this->jsonFeedback->feedback("Failed to load user information", FeedbackConstants::FAILED); 
        }
        /**
         * Updates system user
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateSystemUser($param) {
            $this->logging->startMethod("updateSystemUser");
            $this->logging->debugObject("User Object",$param);
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateSystemUser($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateSystemUser");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aClientInfoValidation = $aInputValidator->validateClientBasicInformation($param->userDetails);
            if(!$aClientInfoValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aClientInfoValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemUserDAO = new SystemUserDAO();
            
            $aUserResults = $aSystemUserDAO->findRecordWithUserID($param->userId);
            if(!$aUserResults[status]){
                return $this->jsonFeedback->feedback("User was not found on system", FeedbackConstants::FAILED);
            }
            
            $aResults = $aSystemUserDAO->updateSystemUser($param);
            if(!$aResults['status']) {
                $this->logging->exitMethod("updateSystemUser");
                return $this->jsonFeedback->feedback($aResults['message'], FeedbackConstants::FAILED);
            }
            
            $aFirstName = "";
            $aLastName = "";
             
            foreach ($param->userDetails as $key => $value) {
                $this->logging->debugObject("Entity Detail Object",$value);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->updateEntityDetailWithID($value);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("updateSystemUser",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
                
                if($value->propertyName === "First Name"){
                    $aFirstName = $value->entityContent;
                }else if($value->propertyName === "Last Name"){
                    $aLastName = $value->entityContent;
                }
            }
            
            /**
             * Send an email to new email address
             */
            if(trim($aUserResults[resultsArray][email]) !== $param->email){
                $aSendEmail = new SendEmail();
                
                $aUserResults[resultsArray][email] = $param->email;
                
                $aSendEmail->sendUpdateContactEmail($aFirstName." ".$aLastName, $aUserResults[resultsArray]);
                
                return $this->jsonFeedback->feedback("System user sucessfully updated. Email address has been changed", FeedbackConstants::SUCCESSFUL); 
            }
            
            $this->logging->exitMethod("updateSystemUser");
            return $this->jsonFeedback->feedback("System user sucessfully updated", FeedbackConstants::SUCCESSFUL); 
        }
        /**
         * Updates projects
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateProject($param) {
            $this->logging->startMethod("updateProject");
            $this->logging->debugObject("Project Object",$param); 
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateProject($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateClient");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }

            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("updateClient");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            foreach ($param->projectDetails as $key => $value) {
                $this->logging->debugObject("Entity Detail Object",$value);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->updateEntityDetailWithID($value);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("updateProject",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
            }
            
            $this->logging->exitMethod("updateProject");
            return $this->jsonFeedback->feedback("Project sucessfully updated", FeedbackConstants::SUCCESSFUL); 
        }
        /**
         * Updates client
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateClient($param) {
            $this->logging->startMethod("updateClient");
            $this->logging->debugObject("Client Object",$param); 
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateClient($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateClient");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemUserDAO = new SystemUserDAO();
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($param->clientId);
            if(!$aUserResults[status]){
                return $this->jsonFeedback->feedback("User was not found on system", FeedbackConstants::FAILED);
            }
            
            $aUserResult = $aSystemUserDAO->updateSystemUserEmailAddress($param);
            if(!$aUserResult['status']) {
                $this->logging->exitMethodWithError("updateClient",$aUserResult['message']);
               return $this->jsonFeedback->feedback($aUserResult['message'], FeedbackConstants::FAILED);
            }
            
            $this->logging->debug("updateClient","Email successfully updated");
            $aFirstName = "";
            $aLastName = "";
            foreach ($param->userDetails as $key => $value) {
                $this->logging->debugObject("Entity Detail Object",$value);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->updateEntityDetailWithID($value);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("updateClient",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
                
                if($value->propertyName === "First Name"){
                    $aFirstName = $value->entityContent;
                }else if($value->propertyName === "Last Name"){
                    $aLastName = $value->entityContent;
                }
            }
            
            /**
             * Send an email to new email address
             */
            if(trim($aUserResults[resultsArray][email]) !== $param->email){
                $aSendEmail = new SendEmail();
                
                $aUserResults[resultsArray][email] = $param->email;
                
                $aSendEmail->sendUpdateContactEmail($aFirstName." ".$aLastName, $aUserResults[resultsArray]);
                
                return $this->jsonFeedback->feedback("System user sucessfully updated. Email address has been changed", FeedbackConstants::SUCCESSFUL); 
            }
            
            $this->logging->exitMethod("updateClient");
            return $this->jsonFeedback->feedback("Client sucessfully updated", FeedbackConstants::SUCCESSFUL); 
        }
        /**
         * Updates client details
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateClientDetails($param) {
            $this->logging->startMethod("updateClientDetails");
            $this->logging->debugObject("Client Object",$param); 
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateClientDetails($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateClientDetails");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("updateClientDetails");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aPHPToJSONArray = new PHPToJSONArray(); 
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->clientId);
            if(!$aEntityResults[status]){
                $this->logging->exitMethod("updateClientDetails");
                return $this->jsonFeedback->feedback("Client ID data was setup properly", FeedbackConstants::FAILED);
            }
            
            foreach ($param->userDetails as $key => $value) {
                $detailData = $aPHPToJSONArray->addOrUpdateEntityDetailJSON($aEntityResults[resultsArray][user_id], $aEntityResults[resultsArray][entity_id],$value->entityDetailId, $value->propertyId, $value->entityContent);
                
                $this->logging->debugObject("Entity Detail Object",$detailData);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->addOrUpdateEntityDetail($detailData);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("updateClientDetails",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
            }
            
            $this->logging->exitMethod("updateClientDetails");
            return $this->jsonFeedback->feedback("Client sucessfully updated", FeedbackConstants::SUCCESSFUL); 
        }
        /**
         * Updates log flag status
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateLogFlagStatus($param) {
            $this->logging->startMethod("updateLogFlagStatus");
            $this->logging->debugObject("Flag Object",$param);
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->administratorAccess();
            if(!$aFunctionAccessValidation[status]){
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            
            $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyName("Logs");
            if(!$aPropertyResults['status']) {
                return $this->jsonFeedback->feedback("System not is setup properly. Logs property not found", FeedbackConstants::FAILED); 
            }
             
            $aPHPToJSONArray = new PHPToJSONArray(); 
            
            $aData = $aPHPToJSONArray->updatePropertyDescriptionJSON($aPropertyResults['resultsArray']['property_id'], ucfirst($param->flag));
            
            $aUpdateResults = $aSystemPropertyDAO->updateSystemPropertyDescription($aData);
            
            if(!$aUpdateResults['status']) {
                return $this->jsonFeedback->feedback($aUpdateResults["message"], FeedbackConstants::FAILED); 
            }
            
            /**
             * Invalidate flag session
             */
            unset($_SESSION["system_log"]);
            
            $this->logging->exitMethod("updateLogFlagStatus"); 
            return $this->jsonFeedback->feedback("Log flag turned ".$param->flag, FeedbackConstants::SUCCESSFUL);
        }
        /**
         * Updates client details
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateStageItem($param) {
            $this->logging->startMethod("updateStageItem");
            $this->logging->debugObject("Item Object",$param); 
            
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->validateUpdateItemDetails($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateStageItem");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("addNewClient");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aPHPToJSONArray = new PHPToJSONArray(); 
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->itemId);
            if(!$aEntityResults[status]){
                $this->logging->exitMethod("updateStageItem");
                return $this->jsonFeedback->feedback("Item ID data was setup properly", FeedbackConstants::FAILED);
            }
            
            foreach ($param->itemDetails as $key => $value) {                
                $this->logging->debugObject("Entity Detail Object",$value);

                $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
                $aEntityDetailResults = $aSystemEntityDetailsDAO->updateEntityDetailWithID($value);
                if(!$aEntityDetailResults['status']){
                    $this->logging->exitMethodWithError("updateStageItem",$aEntityDetailResults['message']);
                    return $this->jsonFeedback->feedback($aEntityDetailResults['message'], FeedbackConstants::FAILED);
                }
            }
            
            $aUpdateEntityResults = $aSystemEntityDAO->findRecordWithName($param->itemId);
            if(!$aUpdateEntityResults[status]){
                $this->logging->exitMethod("updateStageItem");
                return $this->jsonFeedback->feedback("Item ID data was not setup properly", FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("updateStageItem");
            return $this->jsonFeedback->stageItem($aUpdateEntityResults['resultsArray'], "Item successfully updated");
        }
        /**
         * Updates credentials for system user
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function updateSystemUserCredentials($param) {
            $this->logging->startMethod("updateSystemUserCredentials");
            $this->logging->debugObject("User Object",$param);
            
            $aInputValidator = new InputValidator();
            $aSystemUserDAO = new SystemUserDAO();

            $aValidation = $aInputValidator->validateUpdateCredentials($param);
            if(!$aValidation[status]){
                $this->logging->exitMethod("updateSystemUserCredentials");
                return $this->jsonFeedback->feedback($aValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aSystemUser = $this->accessValidator->getSystemUser();
            $aUserResult = $aSystemUserDAO->retrieveSystemUserActivePassword($aSystemUser->getUserID());
            if($aUserResult['status']){
                $password = $aSystemUserDAO->getDecryptedPassword($aUserResult[resultsArray]);
                if($param->currentPassword == $password[message]){
                    $aResult =  $aSystemUserDAO->updateSystemUserCredentials($param,$aSystemUser->getUserID());
                    if($aResult['status']){
                        $this->logging->exitMethod("updateSystemUserCredentials");
                        return $this->jsonFeedback->feedback("Credentials sucessfully updated", FeedbackConstants::SUCCESSFUL);
                    }
                    $this->logging->exitMethod("updateSystemUserCredentials");
                    return $this->jsonFeedback->feedback($aResult['message'], FeedbackConstants::FAILED);
                }else{
                    $this->logging->exitMethod("updateSystemUserCredentials");
                    return $this->jsonFeedback->feedback("Current password is incorrect", FeedbackConstants::FAILED);
                }
            }
            $this->logging->exitMethod("updateSystemUserCredentials");
            return $this->jsonFeedback->feedback("System failed to update credentials", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves system groups
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemGroups($param) {
            $this->logging->startMethod("retrieveSystemGroups");
            $this->logging->debugObject("Page Object",$param);

            $aSystemGroupDAO = new SystemGroupDAO();
            $aGroupResults = $aSystemGroupDAO->retrieveSystemGroups();
            if($aGroupResults['status']) {
                $this->logging->exitMethod("retrieveSystemGroups");
                return $this->jsonFeedback->systemGroups($aGroupResults['resultsArray'], "System groups retrieved successfully"); 
            }
            
            $this->logging->exitMethod("retrieveSystemGroups");
            return $this->jsonFeedback->feedback($aGroupResults['message'], FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves system users
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemUsers($param) {
            $this->logging->startMethod("retrieveSystemUsers");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemUserDAO = new SystemUserDAO();
            
            $aUserResults = $aSystemUserDAO->retrieveSystemUsers();
            if($aUserResults['status']) {
                $this->logging->exitMethod("retrieveSystemUsers");
                return $this->jsonFeedback->systemUsers($aUserResults['resultsArray'], "System users retrieved successfully"); 
            }
            $this->logging->exitMethod("retrieveSystemUsers");
            return $this->jsonFeedback->feedback($aUserResults['message'], FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves clients
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveClients($param) {
            $this->logging->startMethod("retrieveClients");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityResults = $aSystemEntityDAO->retrieveEntityWithTypeName($param);
            if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveClients");
                return $this->jsonFeedback->clients($aEntityResults['resultsArray'], "Clients successfully retrieved");
            }
            
            $this->logging->exitMethod("retrieveClients");
            return $this->jsonFeedback->feedback("No clients found on the system", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves clients
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveProjects($param) {
            $this->logging->startMethod("retrieveProjects");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aAccessValidator = new AccessValidator();
            $aPHPToJSONArray = new PHPToJSONArray();
            
            $aEntityResults = null;
            if($aAccessValidator->getSystemUser()->getRoleName() === "Client"){
                $data = $aPHPToJSONArray->entityQueryByTypeNameAndClientIdJSON($param->entityTypeName,$aAccessValidator->getSystemUser()->getUserKey());
                $aEntityResults = $aSystemEntityDAO->retrieveEntityWithTypeNameAndClientId($data);
            }else {
                $aEntityResults = $aSystemEntityDAO->retrieveEntityWithTypeName($param);
            }
            if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveProjects");
                return $this->jsonFeedback->projects($aEntityResults['resultsArray'], "Projects successfully retrieved");
            }
            
            $this->logging->exitMethod("retrieveProjects");
            return $this->jsonFeedback->feedback("No projects found on the system", FeedbackConstants::FAILED); 
        }
        
        private function retrieveUserTasks($param) {
            $this->logging->startMethod("retrieveUserTasks");
            $this->logging->debugObject("Page Object",$param);
            
            $aAccessValidator = new AccessValidator();
            $aSystemEntityDAO = new SystemEntityDAO();
            
            $aUserKey = $aAccessValidator->getSystemUser()->getUserKey();
            $aEntityResults = $aSystemEntityDAO->retrieveEntityWithEntityDetailContent($aUserKey);
            if(!$aEntityResults['status']) {
                return $this->jsonFeedback->feedback("No tasks assigned to you", FeedbackConstants::FAILED); 
            }
            
            $this->logging->exitMethod("retrieveUserTasks");
            return $this->jsonFeedback->tasks($aEntityResults['resultsArray'],"Tasks assigned to user successfully retrieved"); 
        }
        /**
         * Retrieves projects in QA enviroments
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveProjectsInQAEnvironments($param) {
            $this->logging->startMethod("retrieveProjectsInQAEnvironments");
            $this->logging->debugObject("Environment Object",$param);
			
            $aSystemEntityDAO = new SystemEntityDAO();
		    $aAccessValidator = new AccessValidator();
			
			$this->logging->debug("Environment Object",$this->accessValidator->getSystemUser()->getUserKey());

			 
			$aDeploymentsResults = null;
            if($this->accessValidator->getSystemUser()->getRoleName() === "Client"){
                $data = $aPHPToJSONArray->entityQueryByTypeNameAndClientIdJSON(GroupsConstants::QA_ENVIRONMENTS,$aAccessValidator->getSystemUser()->getUserKey());
                $aDeploymentsResults = $aSystemEntityDAO->retrieveEntityByTypeGroupNameAndClientId($data);
            }else {
				$aDeploymentsResults = $aSystemEntityDAO->retrieveEntityByTypeGroupName(GroupsConstants::QA_ENVIRONMENTS);
            }
			
            if(!$aDeploymentsResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectsInQAEnvironments","No deployed projects found on system");
                return $this->jsonFeedback->feedback("No deployed projects found on system", FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("retrieveProjectsInQAEnvironments");
            return $this->jsonFeedback->qaProjects($aDeploymentsResults['resultsArray'], "Projects successfully retrieved");
        }
        
        private function retrieveProjectsInQAEnvironmentsJSONP($param) {
            return "angular.callbacks._0(".$this->retrieveProjectsInQAEnvironments($param).")";
        }
        
        /**
         * Retrieves deployments
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveDeployments($param) {
            $this->logging->startMethod("retrieveDeployments");
            $this->logging->debugObject("Deployment Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            
            $aDeploymentsResults = $aSystemEntityDAO->retrieveEntityByTypeGroupName(GroupsConstants::QA_ENVIRONMENTS);
            if(!$aDeploymentsResults['status']) {
                $this->logging->exitMethodWithError("retrieveDeployments","No deployments found on system");
                return $this->jsonFeedback->feedback("No deployments found on system", FeedbackConstants::FAILED);
            }
            
            $this->logging->exitMethod("retrieveDeployments");
            return $this->jsonFeedback->deployments($aDeploymentsResults['resultsArray'], "Deployments successfully retrieved");
        }
        
        private function retrieveDeploymentsJSONP($param) {
            return "angular.callbacks._0(".$this->retrieveDeployments($param).")";
        }
        
        private function retrieveProjectDocuments($param) {
            $this->logging->startMethod("retrieveProjectDocuments");
            $this->logging->debugObject("Project Object",$param);
            
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectStageItems", "Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system.", FeedbackConstants::FAILED); 
            }
            
            $aStagesData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aEntityResults[resultsArray][entity_id], GroupsConstants::PROJECT_STAGES);
            $aStagesResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($aStagesData);
            if(!$aStagesResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectDocuments","Project currently has stages or documents.");
                return $this->jsonFeedback->feedback("Project currently has stages or documents.", FeedbackConstants::WARNING);
            }
            
            $aInParams = array();
            foreach($aStagesResults[resultsArray] as $aResult){
                $aInParams[] = $aResult[sub_entity];
            }
            
            $aDocumentsResults = $aSystemEntityLinkDAO->findRecordsMultipleByMainEntitiesAndLinkType($aInParams, "Document");
            if(!$aDocumentsResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectStageItems","Project currently has documents");
                return $this->jsonFeedback->feedback("Project currently has documents", FeedbackConstants::WARNING);
            }
            
            $this->logging->exitMethod("retrieveProjectDocuments");
            return $this->jsonFeedback->stageItems($aDocumentsResults['resultsArray'], "Project documents successfully retrieved.");
        }
        /**
         * Retrieves project stage items
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveProjectStageItems($param) {
            $this->logging->startMethod("retrieveProjectStageItems");
            $this->logging->debugObject("Project Stage Object",$param);
            
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectStageItems", "Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED); 
            }
            
            $aStageData = $aPHPToJSONArray->entityLinkQueryJSON($aEntityResults[resultsArray][entity_id], $param->stageName);
            
            $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkType($aStageData);
            if(!$aProjectStageResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectStageItems","Stage was not found for project");
                return $this->jsonFeedback->feedback("Stage was not found for project", FeedbackConstants::FAILED); 
            }
            
            $aProjectStageResult = null;
            foreach($aProjectStageResults[resultsArray] as $aResult){
                $aProjectStageResult = $aResult;
            }
            
            $aItemsData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aProjectStageResult[sub_entity], GroupsConstants::ITEM_TYPES);
            
            $aStageItemsResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($aItemsData);
            if(!$aStageItemsResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectStageItems","No items found for specified project and stage on system");
                return $this->jsonFeedback->feedback("Project currently has no tasks or documents", FeedbackConstants::WARNING);
            }
            
            $this->logging->exitMethod("retrieveProjectStageItems");
            return $this->jsonFeedback->stageItems($aStageItemsResults['resultsArray'], "Project stage items successfully retrieved");
        }
        /**
         * Retrieves project stage item
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveProjectStageItem($param) {
            $this->logging->startMethod("retrieveProjectStageItem");
            $this->logging->debugObject("Stage Item Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->itemId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethodWithError("retrieveProjectStageItem", "Item ID was not found on system");
                return $this->jsonFeedback->feedback("Item ID was not found on system", FeedbackConstants::FAILED); 
            }
            
            $this->logging->exitMethod("retrieveProjectStageItem");
            return $this->jsonFeedback->stageItem($aEntityResults['resultsArray'], "Project stage item successfully retrieved");
        }
        /**
         * Retrieves stages for project
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveStagesForProject($param) {
            $this->logging->startMethod("retrieveStagesForProject");
            $this->logging->debugObject("Project Object",$param);
            
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if(!$aEntityResults['status']) {
                $this->logging->exitMethodWithError("retrieveStagesForProject", "Project ID was not found on system");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED); 
            }
            
            $StageData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aEntityResults[resultsArray][entity_id], GroupsConstants::PROJECT_STAGES);
            
            $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($StageData);
            if(!$aProjectStageResults['status']) {
                $this->logging->exitMethodWithError("retrieveStagesForProject","Project stage was not found on system");
                return $this->jsonFeedback->feedback("Project was not initiated correctly. No stage was found for project", FeedbackConstants::WARNING); 
            }
            
            $this->logging->exitMethod("retrieveStagesForProject");
            return $this->jsonFeedback->stages($aProjectStageResults['resultsArray'], "Project stages successfully retrieved");
        }
        /**
         * Retrieves projects for client
         * 
         * @param JSONObject param
         * @return JSONObject
         */
        private function retrieveProjectsForClient($param) {
            $this->logging->startMethod("retrieveProjectsForClient");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityResults = $aSystemEntityDAO->retrieveEntityWithTypeNameAndClientId($param);
             if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveProjectsForClient");
                return $this->jsonFeedback->projects($aEntityResults['resultsArray'], "Projects successfully retrieved");
            }
            $this->logging->exitMethod("retrieveProjectsForClient");
            return $this->jsonFeedback->feedback("Client has no projects", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves clients
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveProject($param) {
            $this->logging->startMethod("retrieveProject");
            $this->logging->debugObject("Project Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveProject");
                return $this->jsonFeedback->project($aEntityResults['resultsArray'], "Project successfully retrieved");
            }
            
            $this->logging->exitMethod("retrieveProject");
            return $this->jsonFeedback->feedback("Project not found", FeedbackConstants::NOT_FOUND); 
        }
        /**
         * Retrieves current user information 
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveLoggedUser($param) {
            $this->logging->startMethod("retrieveLoggedUser");
            $this->logging->debugObject("Page Object",$param);
            
            $aUserKey = $this->accessValidator->getSystemUser()->getUserKey();
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($aUserKey);
            if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveLoggedUser");
                return $this->jsonFeedback->client($aEntityResults['resultsArray'], "User retrieved");
            }
            
            $this->logging->exitMethod("retrieveLoggedUser");
            return $this->jsonFeedback->feedback("User not found", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves clients
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveClient($param) {
            $this->logging->startMethod("retrieveClient");
            $this->logging->debugObject("Client Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->clientId);
            if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveClient");
                return $this->jsonFeedback->client($aEntityResults['resultsArray'], "Clients successfully retrieved");
            }
            
            $this->logging->exitMethod("retrieveClient");
            return $this->jsonFeedback->feedback("Client not found", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves clients
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemUser($param) {
            $this->logging->startMethod("retrieveSystemUser");
            $this->logging->debugObject("Client Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityResults = $aSystemEntityDAO->findRecordWithName($param->clientId);
            if($aEntityResults['status']) {
                $this->logging->exitMethod("retrieveSystemUser");
                return $this->jsonFeedback->client($aEntityResults['resultsArray'], "System user successfully retrieved");
            }
            
            $this->logging->exitMethod("retrieveSystemUser");
            return $this->jsonFeedback->feedback("System user not found", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves system roles
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemRoles($param) {
            $this->logging->startMethod("retrieveSystemRoles");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemRoleDAO = new SystemRoleDAO();
            
            $aRolesResults = $aSystemRoleDAO->retrieveSystemRoles();
            if($aRolesResults['status']) {
                return $this->jsonFeedback->systemRoles($aRolesResults['resultsArray'], "System roles retrieved successfully"); 
            }
            $this->logging->exitMethod("retrieveSystemRoles");
            return $this->jsonFeedback->feedback($aRolesResults['message'], FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves invoice
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveInvoice($param) {
            $this->logging->startMethod("retrieveInvoice");
            $this->logging->debugObject("Invoice Object",$param);
            
            $aFinancialPaymentDAO = new FinancialPaymentDAO();

            $aInvoiceResults = $aFinancialPaymentDAO->findRecordByPaymentCode($param->invoiceId);
            
            if($aInvoiceResults['status']) {
                $this->logging->exitMethod("retrieveInvoice");
                return $this->jsonFeedback->supportInvoice($aInvoiceResults['resultsArray'], "Invoice retrieved successfully"); 
            }
            
            $this->logging->exitMethod("retrieveInvoice");
            return $this->jsonFeedback->feedback("Invoice not found in system.", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves invoices
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveInvoices($param) {
            $this->logging->startMethod("retrieveInvoices");
            $this->logging->debugObject("Page Object",$param);
            
            $aFinancialPaymentDAO = new FinancialPaymentDAO();
            $aSystemUserDAO = new SystemUserDAO();
            $aInputValidator = new InputValidator();
            
            if($this->accessValidator->getSystemUser()->getRoleName() === "Client") {
                $aInvoicesResults = $aFinancialPaymentDAO->retrievePaymentsByUserId($this->accessValidator->getSystemUser()->getUserID());
            } else if($aInputValidator->minLengthRequirement($param->clientId,  InputValidator::MIN_CLIENT_ID)) {
                $aUserResults = $aSystemUserDAO->findPasswordRecordWithClientID($param->clientId);
                if(!$aUserResults['status']) {
                    $this->logging->exitMethod("retrieveSystemSupportTickets");
                    return $this->jsonFeedback->feedback("Specified user not found", FeedbackConstants::FAILED); 
                }
                
                $aInvoicesResults = $aFinancialPaymentDAO->retrievePaymentsByUserId($aUserResults[resultsArray][user_id]);
            } else {
                $aInvoicesResults = $aFinancialPaymentDAO->retrievePayments();
            }
            
            if($aInvoicesResults['status']) {
                $this->logging->exitMethod("retrieveInvoices");
                return $this->jsonFeedback->supportInvoices($aInvoicesResults['resultsArray'], "Invoices retrieved successfully"); 
            }
            
            $this->logging->exitMethod("retrieveInvoices");
            return $this->jsonFeedback->feedback("There are no invoices available", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves invoices
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveInvoicesForProject($param) {
            $this->logging->startMethod("retrieveInvoicesForProject");
            $this->logging->debugObject("Page Object",$param);
            
            $aFinancialPaymentDAO = new FinancialPaymentDAO();

            $aInvoicesResults = $aFinancialPaymentDAO->retrievePaymentsByProjectId($param->projectId);
            if($aInvoicesResults['status']) {
                $this->logging->exitMethod("retrieveInvoicesForProject");
                return $this->jsonFeedback->supportInvoices($aInvoicesResults['resultsArray'], "Invoices retrieved successfully"); 
            }
            
            $this->logging->exitMethod("retrieveInvoicesForProject");
            return $this->jsonFeedback->feedback("There are no invoices available", FeedbackConstants::FAILED); 
        }
        /**
         * Removes deal
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function removeDeal($param) {
            $this->logging->startMethod("removeDeal");
            $this->logging->debugObject("Page Object",$param);
            
            $aFinancialDealsDAO = new FinancialDealsDAO();
            $aBusinessDataValidator = new BusinessDataValidator();

            $aDealsResults = $aFinancialDealsDAO->findRecordByDealCode($param->dealCode);
            if(!$aDealsResults[status]){
                $this->logging->exitMethod("removeDeal");
                return $this->jsonFeedback->feedback("There are no deal matching deal code", FeedbackConstants::FAILED); 
            }
            
            $aFunctionAccessValidation = $aBusinessDataValidator->internalUserAccess();
            if(!$aFunctionAccessValidation[status]){
                $this->logging->exitMethod("removeDeal");
                return $this->jsonFeedback->feedback($aFunctionAccessValidation['message'], FeedbackConstants::FAILED);
            }
            
            $aRemoveDealResults = $aFinancialDealsDAO->removeDeal($param->dealCode);
            if($aRemoveDealResults[status]) {
                $this->logging->exitMethod("retrieveDeal");
                return $this->jsonFeedback->feedback("Deal successfully removed.", FeedbackConstants::SUCCESSFUL); 
            }
            
            $this->logging->exitMethod("retrieveDeal");
            return $this->jsonFeedback->feedback("System failed to remove deal. Please try again later", FeedbackConstants::FAILED);     
        }
        /**
         * Retrieves deals
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveDeal($param) {
            $this->logging->startMethod("retrieveDeal");
            $this->logging->debugObject("Page Object",$param);
            
            $aFinancialDealsDAO = new FinancialDealsDAO();
            
            $aDealsResults = $aFinancialDealsDAO->findRecordByDealCode($param->dealCode);
            if($aDealsResults[status]){
                $this->logging->exitMethod("retrieveDeal");
                return $this->jsonFeedback->deal($aDealsResults['resultsArray'], "Deals retrieved successfully");  
            }
            
            $this->logging->exitMethod("retrieveDeal");
            return $this->jsonFeedback->feedback("There are no deal matching deal code available", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves deals
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveQuotes($param) {
            $this->logging->startMethod("retrieveQuotes");
            $this->logging->debugObject("Page Object",$param);
            
            $aFinancialQuotesDAO = new FinancialQuotesDAO();
            
            $aQuotesResults = $aFinancialQuotesDAO->retrieveQuotes();
            if($aQuotesResults[status]){
                $this->logging->exitMethod("retrieveQuotes");
                return $this->jsonFeedback->quotes($aQuotesResults['resultsArray'], "Quotes retrieved successfully");  
            }
            
            $this->logging->exitMethod("retrieveQuotes");
            return $this->jsonFeedback->feedback("There are no quotes available", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves deals
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveDeals($param) {
            $this->logging->startMethod("retrieveDeals");
            $this->logging->debugObject("Page Object",$param);
            
            $aFinancialDealsDAO = new FinancialDealsDAO();
            
            $aDealsResults = $aFinancialDealsDAO->retrieveDevelopmentDeals();
            if($aDealsResults[status]){
                $this->logging->exitMethod("retrieveDeals");
                return $this->jsonFeedback->deals($aDealsResults['resultsArray'], "Deals retrieved successfully");  
            }
            
            $this->logging->exitMethod("retrieveDeals");
            return $this->jsonFeedback->feedback("There are no deals available", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves system support tickets
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemSupportTickets($param) {
            $this->logging->startMethod("retrieveSystemSupportTickets");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemSupportDAO = new SystemSupportDAO();
            $aSystemUserDAO = new SystemUserDAO();
            $aInputValidator = new InputValidator();
            
            if($this->accessValidator->getSystemUser()->getRoleName() === "Client") {
                $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTicketsByUserId($this->accessValidator->getSystemUser()->getUserID());
            } else if($aInputValidator->minLengthRequirement($param->clientId,  InputValidator::MIN_CLIENT_ID)) {
                $aUserResults = $aSystemUserDAO->findPasswordRecordWithClientID($param->clientId);
                if(!$aUserResults['status']) {
                    $this->logging->exitMethod("retrieveSystemSupportTickets");
                    return $this->jsonFeedback->feedback("Specified user not found", FeedbackConstants::FAILED); 
                }
                
                $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTicketsByUserId($aUserResults[resultsArray][user_id]);
            } else {
                $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTickets();
            }
            
            if($aSupportResults['status']) {
                $this->logging->exitMethod("retrieveSystemSupportTickets");
                return $this->jsonFeedback->supportTickets($aSupportResults['resultsArray'], "System support tickets retrieved successfully"); 
            }
            
            $this->logging->exitMethod("retrieveSystemSupportTickets");
            return $this->jsonFeedback->feedback("There are no support tickets available", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves system support ticket by support id
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemSupportTicket($param) {
            $this->logging->startMethod("retrieveSystemSupportTicket");
            $this->logging->debugObject("Ticket Object",$param);
            
            $aSystemSupportDAO = new SystemSupportDAO();
            
            $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTicket($param->supportId);
            if($aSupportResults['status']) {
                $this->logging->exitMethod("retrieveSystemSupportTicket");
                return $this->jsonFeedback->supportTicket($aSupportResults['resultsArray'], "System support ticket retrieved successfully"); 
            }
            $this->logging->exitMethod("retrieveSystemSupportTicket");
            return $this->jsonFeedback->feedback("Support ticket not found in system.", FeedbackConstants::FAILED); 
        }
        /**
         * Retrieves system properties
         * 
         * @param JSONObject $param
         * @return JSONObject
         */
        private function retrieveSystemProperties($param) {
            $this->logging->startMethod("retrieveSystemProperties");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aPropertyResults = $aSystemPropertyDAO->retrieveSystemProperties();
            if($aPropertyResults['status']) {
                $this->logging->exitMethod("retrieveSystemProperties");
                return $this->jsonFeedback->systemProperties($aPropertyResults['resultsArray'], "System properties retrieved successfully"); 
            }
            
            $this->logging->exitMethod("retrieveSystemProperties");
            return $this->jsonFeedback->feedback($aPropertyResults['message'], FeedbackConstants::FAILED); 
        }
        
        private function retrieveSystemPropertiesJSONP($param) {
            return "angular.callbacks._0(".$this->retrieveSystemProperties($param).")";
        }
        /**
         * 
         * @param type $param
         * @return type
         */
        private function retrieveAdminPanelDashboard($param) {
            $this->logging->startMethod("retrieveAdminPanelDashboard");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemPropertyDAO = new SystemPropertyDAO();
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemGroupDAO = new SystemGroupDAO();
            
            $aPropertyResults = $aSystemPropertyDAO->retrieveSystemProperties();
            $aGroupResults = $aSystemGroupDAO->retrieveSystemGroups();
            $aUserResults = $aSystemUserDAO->retrieveSystemUsers();
            $aLogFlagResults = $aSystemPropertyDAO->findRecordByPropertyName("Logs");
 
            $aLogStatus = strtolower($aLogFlagResults['resultsArray'][property_description]);
            
            if($aLogStatus == "on"){
                $aLogStatus = "off";
            }else if($aLogStatus == "off"){
                $aLogStatus = "on";
            }
            
            $aCount['properties'] = count($aPropertyResults['resultsArray']);
            $aCount['groups']= count($aGroupResults['resultsArray']);
            $aCount['users'] = count($aUserResults['resultsArray']);
            $aCount['logs'] = strtolower($aLogStatus);
            $aCount['systemErrors'] = "1";
            
            $this->logging->exitMethod("retrieveAdminPanelDashboard");
            return $this->jsonFeedback->adminPanelDashboard($aCount, "System properties retrieved successfully"); 
        }
        /**
         * 
         * @param type $param
         * @return type
         */
        private function retrieveProjectTopBarPanel($param) {
            $this->logging->startMethod("retrieveProjectTopBarPanel");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO= new SystemEntityDAO();
            $aSystemEntityDetailsDAO= new SystemEntityDetailsDAO();
            $aAccessValidator = new AccessValidator();
            $aInputValidator = new InputValidator();
            $aPHPToJSONArray = new PHPToJSONArray();
            
            $aProjectResults = $aSystemEntityDAO->findRecordWithName($param->projectId);
            if($aProjectResults[status]) {
                $aProjectDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($param->projectId);
                if(!$aProjectDetails[status]){
                    $this->logging->exitMethod("retrieveProjectTopBarPanel");
                    return $this->jsonFeedback->feedback("Project details were not found on system", FeedbackConstants::FAILED);  
                }
                
                foreach ($aProjectDetails[resultsArray] as $key => $value) {
                    if($value[property_name] === "Project Name"){
                        $aProjectName = $value[entity_detail_content];
                    }else if($value[property_name] === "Release Forecast"){
                        $aDateObject = date_create($value[entity_detail_content]);
                        $aReleaseForecast = date_format($aDateObject,"D, d M Y");
                    }
                }
                $aDateObject = date_create($aProjectResults[resultsArray][date_created]);
                
                $aProjectUserId = $aProjectResults[resultsArray][user_id];
                $aProjectDateCreated = date_format($aDateObject,"D, d M Y");
                
                $aUserResults = $aSystemUserDAO->findRecordWithUserID($aProjectUserId);
                if(!$aUserResults[status]){
                    $this->logging->exitMethod("retrieveProjectTopBarPanel");
                    return $this->jsonFeedback->feedback("Client details not found on system", FeedbackConstants::FAILED);  
                }
                
                $aClientID = $aUserResults[resultsArray][user_key];
                $aClientEmailAddress = $aUserResults[resultsArray][email];
                
                $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aClientID);
                if(!$aUserDetails[status]){
                    $this->logging->exitMethod("retrieveClientTopBarPanel");
                    return $this->jsonFeedback->feedback("Client details were not found on system", FeedbackConstants::FAILED);  
                }
                
                foreach ($aUserDetails[resultsArray] as $key => $value) {
                    if($value[property_name] === "First Name"){
                        $aFirstName = $value[entity_detail_content];
                    }else if($value[property_name] === "Last Name"){
                        $aLastName = $value[entity_detail_content];
                    }
                }
            } else {
                $this->logging->exitMethod("retrieveProjectTopBarPanel");
                return $this->jsonFeedback->feedback("Project ID was not found on system", FeedbackConstants::FAILED);
            }
            
            $aCount['accountHolder'] = $aFirstName." ".$aLastName;
            $aCount['clientId'] = $aClientID;
            $aCount['emailAddress'] = $aClientEmailAddress;
            $aCount['projectId'] = $param->projectId;
            $aCount['projectName'] = $aProjectName;
            $aCount['startDate'] = $aProjectDateCreated;
            $aCount['releaseForecast'] = $aReleaseForecast;
            $aCount['completedDate'] = "-";
            $aCount['amountCharged'] = "R0.00";
            $aCount['balance'] = "R0.00";
            $aCount['depositPaid'] = "R0.00";
            
            $this->logging->exitMethod("retrieveProjectTopBarPanel");
            return $this->jsonFeedback->projectTopBarPanel($aCount, "Project top bar panel successfully retrieved"); 
        }
        /**
         * 
         * @param type $param
         * @return type
         */
        private function retrieveClientTopBarPanel($param) {
            $this->logging->startMethod("retrieveClientTopBarPanel");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDAO= new SystemEntityDAO();
            $aSystemEntityDetailsDAO= new SystemEntityDetailsDAO();
            $aAccessValidator = new AccessValidator();
            $aInputValidator = new InputValidator();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aSystemSupportDAO = new SystemSupportDAO();

            $aUserKey = $param->clientId;
            if($aInputValidator->isEmpty($aUserKey) || $aAccessValidator->getSystemUser()->getRoleName() === "Client") {
                $aUserKey = $aAccessValidator->getSystemUser()->getUserKey();
            }
            
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($aUserKey);
            if($aUserResults[status]) {
                $aUserDetails = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserKey);
                if(!$aUserDetails[status]){
                    $this->logging->exitMethod("retrieveClientTopBarPanel");
                    return $this->jsonFeedback->feedback("Client details were not found on system", FeedbackConstants::FAILED);  
                }
                
                foreach ($aUserDetails[resultsArray] as $key => $value) {
                    if($value[property_name] === "First Name"){
                        $aFirstName = $value[entity_detail_content];
                    }else if($value[property_name] === "Last Name"){
                        $aLastName = $value[entity_detail_content];
                    }
                }
                $aEmailAddress = $aUserResults[resultsArray][email];
            } else {
                $this->logging->exitMethod("retrieveClientTopBarPanel");
                return $this->jsonFeedback->feedback("Client Id was not found on system", FeedbackConstants::FAILED); 
            }
            
            $aLoginResults = $aSystemUserDAO->findPreviousLoginRecordWithClientID($aUserKey);
            if($aLoginResults[status]) {
                $aDateString = $aLoginResults[resultsArray][date_created];
                $aDateObject = date_create($aDateString);
                $aFormattedDate = date_format($aDateObject,"d M Y, H:i");
            }else {
                $aFormattedDate = "-";
                //$this->logging->exitMethod("retrieveClientTopBarPanel");
                //return $this->jsonFeedback->feedback("Login audit information not found on system", FeedbackConstants::FAILED); 
            }
            
            $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTicketsByUserId($aUserResults[resultsArray][user_id]);
            
            $projectData = $aPHPToJSONArray->entityQueryByTypeNameAndClientIdJSON("Project",$aUserKey);
            $aProjectsResults = $aSystemEntityDAO->retrieveEntityWithTypeNameAndClientId($projectData);
            
            $aCount['accountHolder'] = $aFirstName." ".$aLastName;
            $aCount['clientId'] = $aUserKey;
            $aCount['emailAddress'] = $aEmailAddress;
            $aCount['projects'] = count($aProjectsResults['resultsArray']);
            $aCount['supportTickets'] = count($aSupportResults['resultsArray']);
            $aCount['lastLogin'] = $aFormattedDate;
            $aCount['balances'] = "R0.00";
            $aCount['previous_payment'] = "R0.00";
            
            $this->logging->exitMethod("retrieveClientTopBarPanel");
            return $this->jsonFeedback->clientTopBarPanel($aCount, "Client top bar panel successfully retrieved"); 
        }
        /**
         * 
         * @param type $param
         * @return type
         */
        private function retrieveConsultantPanelDashboard($param) {
            $this->logging->startMethod("retrieveConsultantPanelDashboard");
            $this->logging->debugObject("Page Object",$param);
            
            $aSystemEntityDAO = new SystemEntityDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aAccessValidator = new AccessValidator();
            $aFinancialPaymentDAO = new FinancialPaymentDAO();
            $aSystemSupportDAO = new SystemSupportDAO();
            
            $aUserKey = $aAccessValidator->getSystemUser()->getUserKey();
            
            $aClientsResults = $aSystemEntityDAO->retrieveEntityWithTypeName($aPHPToJSONArray->entityQueryByTypeNameJSON("Client"));
            
            if($aAccessValidator->getSystemUser()->getRoleName() === "Client"){
                $data = $aPHPToJSONArray->entityQueryByTypeNameAndClientIdJSON("Project",$aAccessValidator->getSystemUser()->getUserKey());
                $aProjectsResults = $aSystemEntityDAO->retrieveEntityWithTypeNameAndClientId($data);
            }else{
                $aProjectsResults = $aSystemEntityDAO->retrieveEntityWithTypeName($aPHPToJSONArray->entityQueryByTypeNameJSON("Project"));
            }

            if($this->accessValidator->getSystemUser()->getRoleName() === "Client") {
                $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTicketsByUserId($this->accessValidator->getSystemUser()->getUserID());
            } else {
                $aSupportResults = $aSystemSupportDAO->retrieveSystemSupportTickets();
            }
            
            if($this->accessValidator->getSystemUser()->getRoleName() === "Client") {
                $aInvoicesResults = $aFinancialPaymentDAO->retrievePaymentsByUserId($this->accessValidator->getSystemUser()->getUserID());
            } else {
                $aInvoicesResults = $aFinancialPaymentDAO->retrievePayments();
            }
            
            $aDocumentsResults = $aSystemEntityDAO->retrieveEntityWithTypeName($aPHPToJSONArray->entityQueryByTypeNameJSON("Document"));
            $aTasksResults = $aSystemEntityDAO->retrieveEntityWithEntityDetailContent($aUserKey);
            
            $aCount['clients'] = count($aClientsResults['resultsArray']);
            $aCount['projects']= count($aProjectsResults['resultsArray']);
            $aCount['documents'] = count($aDocumentsResults['resultsArray']);
            $aCount['mytasks'] = count($aTasksResults['resultsArray']);
            $aCount['invoices'] = count($aInvoicesResults['resultsArray']);
            $aCount['tickets'] = count($aSupportResults['resultsArray']);
            
            $this->logging->exitMethod("retrieveConsultantPanelDashboard");
            return $this->jsonFeedback->consultantPanelDashboard($aCount, "Consultant panel successfully retrieved"); 
        }
        /**
         * Checks if user has access to page
         * 
         * @deprecated since version 1.1
         * @param JSONObject $param
         * @return JSONObject
         */
        private function validateResourceAccess($param) {
            $this->logging->startMethod("validateResourceAccess");
            $this->logging->debugObject("Page Object",$param);
                    
            $aReturn = $this->accessValidator->hasAccess($param->pageName);
            
            if($aReturn){
               $this->logging->exitMethod("validateResourceAccess");
               return $this->jsonFeedback->feedback("User has access", FeedbackConstants::SUCCESSFUL); 
            }
            
            $this->logging->exitMethod("validateResourceAccess");
            return $this->jsonFeedback->feedback("User doesn't have access", FeedbackConstants::FAILED); 
        } 
    }
