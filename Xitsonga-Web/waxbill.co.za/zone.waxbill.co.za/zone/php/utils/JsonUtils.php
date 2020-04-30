<?php
    require_once __DIR__. '/../constants/FeedbackConstants.php';
    require_once __DIR__.'/../dao/SystemUserDAO.php';
    require_once __DIR__.'/../dao/SystemEntityDAO.php';
    require_once __DIR__.'/../dao/SystemEntityDetailsDAO.php';
    require_once __DIR__.'/../dao/SystemPropertyDAO.php';
    require_once __DIR__.'/../dao/SystemEntityLinkDAO.php';
    
    require_once __DIR__.'/../utils/PHPToJSONArray.php';
    require_once __DIR__.'/../validator/AccessValidator.php';
    /**
     * Generates a JSON object
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class JSONUtils{
        /**
         * Formats input message to JSON
         * 
         * @param string message
         * @param NumberFormatter statusCode
         * @return JSON string
         */
        public function feedback($message, $statusCode) {
            return "{ "
                    ."\"status\":" . $statusCode .","
                    ."\"message\":"."\"".$message ."\""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param DTOSystemUser pSystemUser
         * @param string message
         * @return JSON string
         */
        public function systemDefaultUser($pSystemUser, $pMessage) {
            $aBasic = $aBasic."{"
                        ."\"email\":\"" .  $pSystemUser->getEmail() ."\","
                        ."\"roleName\":\"" .  $pSystemUser->getRoleName() ."\","
                        ."\"clientID\":\"" . $pSystemUser->getUserKey() ."\""       
                    ."}";
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"credentials\":"
                       .$aBasic 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pSystemUser
         * @param string message
         * @return JSON string
         */
        public function adminPanelDashboard($pCount, $pMessage) {
            $aBasic = $aBasic."{"
                        ."\"logs\":\"" . $pCount['logs'] ."\","
                        ."\"properties\":\"" . $pCount['properties'] ."\","
                        ."\"users\":\"" . $pCount['users'] ."\","
                        ."\"systemErrors\":\"" . $pCount['systemErrors'] ."\","
                        ."\"groups\":\"" . $pCount['groups'] ."\""
                    ."}";
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"countItems\":"
                       .$aBasic 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pSystemUser
         * @param string message
         * @return JSON string
         */
        public function consultantPanelDashboard($pCount, $pMessage) {
            $aBasic = $aBasic."{"
                        ."\"clients\":\"" . $pCount['clients'] ."\","
                        ."\"projects\":\"" . $pCount['projects'] ."\","
                        ."\"documents\":\"" . $pCount['documents'] ."\","
                        ."\"invoices\":\"" . $pCount['invoices'] ."\","
                        ."\"tickets\":\"" . $pCount['tickets'] ."\","
                        ."\"mytasks\":\"" . $pCount['mytasks'] ."\""
                    ."}";
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"countItems\":"
                       .$aBasic 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param Integer pCount
         * @param string pMessage
         * @return JSON string
         */
        public function projectTopBarPanel($pCount, $pMessage) {
            $aBasic = $aBasic."{"
                        ."\"accountHolder\":\"" . $pCount['accountHolder'] ."\","
                        ."\"clientId\":\"" . $pCount['clientId'] ."\","
                        ."\"emailAddress\":\"" . $pCount['emailAddress'] ."\","
                        ."\"projectId\":\"" . $pCount['projectId'] ."\","
                        ."\"projectName\":\"" . $pCount['projectName'] ."\","
                        ."\"startDate\":\"" . $pCount['startDate'] ."\","
                        ."\"completedDate\":\"" . $pCount['completedDate'] ."\","
                        ."\"releaseForecast\":\"" . $pCount['releaseForecast'] ."\","
                        ."\"amountCharged\":\"" . $pCount['amountCharged'] ."\","
                        ."\"balance\":\"" . $pCount['balance'] ."\","
                        ."\"depositPaid\":\"" . $pCount['depositPaid'] ."\""
                    ."}";
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"countItems\":"
                       .$aBasic 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param Integer pCount
         * @param string pMessage
         * @return JSON string
         */
        public function clientTopBarPanel($pCount, $pMessage) {
            $aBasic = $aBasic."{"
                        ."\"accountHolder\":\"" . $pCount['accountHolder'] ."\","
                        ."\"clientId\":\"" . $pCount['clientId'] ."\","
                        ."\"emailAddress\":\"" . $pCount['emailAddress'] ."\","
                        ."\"projects\":\"" . $pCount['projects'] ."\","
                        ."\"supportTickets\":\"" . $pCount['supportTickets'] ."\","
                        ."\"lastLogin\":\"" . $pCount['lastLogin'] ."\","
                        ."\"balances\":\"" . $pCount['balances'] ."\","
                        ."\"previous_payment\":\"" . $pCount['previous_payment'] ."\""
                    ."}";
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"countItems\":"
                       .$aBasic 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function tasks($pEntities, $pMessage) {
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            foreach($pEntities as $aEntity){
                $aEntityId  = $aEntity['entity_id'];
                $aItemKey = $aEntity['entity_name'];
                $aDateCreated = $aEntity['date_created'];
                
                $aItemResults = $aSystemEntityLinkDAO->findRecordWithSubEntity($aEntityId); 
                $aStageResults = $aSystemEntityLinkDAO->findRecordWithSubEntity($aItemResults[resultsArray][main_entity]);   
                $aProjectResults = $aSystemEntityDAO->findRecordWithID($aStageResults[resultsArray][main_entity]);
                
                if($aProjectResults['status']) {
                    $aProjectId = $aProjectResults[resultsArray][entity_name];
                }

                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    foreach($aDetailsArray as $aDetail){
                        $aContent = $aDetail['entity_detail_content'];
                        $aContentId = $aDetail['entity_detail_content'];
                        if(is_numeric($aContent)){
                            $aSystemPropertyDAO = new SystemPropertyDAO();

                            $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                            if($aPropertyResults['status']){
                                $aContent = $aPropertyResults['resultsArray']['property_name'];
                            }
                        }
                        
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"entityDetailContent\":\"" . $aContent ."\"," 
                            ."\"entityDetailContentId\":\"" . $aContentId ."\""     
                        ."},";
                    }

                    $aDetails = substr_replace($aDetails, "", -1);

                    $aTasks = $aTasks."{"
                        ."\"itemId\":\"" . $aItemKey ."\","
                        ."\"projectId\":\"" . $aProjectId ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","   
                        ."\"details\":["
                            .$aDetails 
                        ."]"
                    ."},";
                } else {
                    $aTasks = $aTasks."{"
                        ."\"itemId\":\"" . $aItemKey ."\","
                        ."\"projectId\":\"" . $aProjectId ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","
                        ."\"details\":[" ."]"
                    ."},";
                }
            }   
            $aTasks = substr_replace($aTasks, "", -1);
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"tasks\":["
                   .$aTasks 
                ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function clients($pEntities, $pMessage) {
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            foreach($pEntities as $aEntity){
                $aEntityId  = $aEntity['entity_id'];
                $aClientKey = $aEntity['entity_name'];
                
                $aUserResults = $aSystemUserDAO->findRecordWithClientID($aClientKey);
                if($aUserResults['status']) {
                    $aEmailAddress = $aUserResults['resultsArray']['email'];
                    $aUserID = $aUserResults['resultsArray']['user_id'];
                    $aRoleName = $aUserResults['resultsArray']['role_name'];
                    
                    if($aRoleName === "Client"){
                        $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                        if($aDetailsResults['status']) {
                            $aDetailsArray = $aDetailsResults['resultsArray'];
                            $aDetails = "";
                            foreach($aDetailsArray as $aDetail){
                                $aDetails = $aDetails."{"
                                    ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                                    ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                                    ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                                    ."\"entityDetailContent\":\"" . $aDetail['entity_detail_content'] ."\""       
                                ."},";
                            }

                            $aDetails = substr_replace($aDetails, "", -1);

                            $aClients = $aClients."{"
                                ."\"emailAddress\":\"" . $aEmailAddress ."\","
                                ."\"clientID\":\"" . $aClientKey ."\","
                                ."\"roleName\":\"" . $aRoleName ."\","
                                ."\"userID\":\"" . $aUserID ."\","
                                ."\"details\":["
                                    .$aDetails 
                                ."]"
                            ."},";
                        }
                    }
                }
            }   
            $aClients = substr_replace($aClients, "", -1);
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"clients\":["
                   .$aClients 
                ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function projects($pEntities, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            foreach($pEntities as $aEntity){
                $aEntityId  = $aEntity['entity_id'];
                $aProjectKey = $aEntity['entity_name'];

                /**
                * Find current stage
                */
                $StageData = $aPHPToJSONArray->entityLinkQueryByGroupJSON($aEntityId, GroupsConstants::PROJECT_STAGES);
                $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsByMainEntityAndLinkTypeGroupName($StageData);

                $aCurrentStage = "";
                foreach ($aProjectStageResults[resultsArray] as $key => $value) {
                    $aCurrentStage = $value["property_name"];
                }
                
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    foreach($aDetailsArray as $aDetail){
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"entityDetailContent\":\"" . $aDetail['entity_detail_content'] ."\""       
                        ."},";
                    }

                    $aDetails = substr_replace($aDetails, "", -1);

                    $aProjects = $aProjects."{"
                        ."\"projectId\":\"" . $aProjectKey ."\","
                        ."\"dateCreated\":\"" . $aEntity['date_created'] ."\","
                        ."\"projectStage\":\"" . $aCurrentStage ."\","
                        ."\"details\":["
                            .$aDetails 
                        ."]"
                    ."},";
                } else {
                    $aProjects = $aProjects."{"
                        ."\"projectId\":\"" . $aProjectKey ."\","
                        ."\"projectStage\":\"" . $aCurrentStage ."\","
                        ."\"details\":[" ."]"
                    ."},";
                }
            }   
            $aProjects = substr_replace($aProjects, "", -1);
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"projects\":["
                   .$aProjects 
                ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function stages($pEntities, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aAccessValidator = new AccessValidator();
            
            foreach($pEntities as $aEntity){
                $aEntityId  = $aEntity['sub_entity'];
                $aProjectStage = $aEntity['property_name'];
                $aDateCreated = $aEntity['date_created'];
                $aActivateMenu = "YES";
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                $aProjectDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntity['main_entity']);
                if($aProjectDetailsResults['status']) {
                    $aProjectDetailsArray = $aProjectDetailsResults['resultsArray'];
                    foreach($aProjectDetailsArray as $aDetail){
                        if($aDetail['property_name'] === "Project Status") {
                            $aActivateMenu = "NO";
                        }
                    }
                }
                $aAccessLevel = $aAccessValidator->getSystemUser()->getAccessLevel();
                if($aAccessLevel == AccessValidator::CLIENT){
                   $aActivateMenu = "NO";
                }
                
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    foreach($aDetailsArray as $aDetail){
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"entityDetailContent\":\"" . $aDetail['entity_detail_content'] ."\""       
                        ."},";
                    }

                    $aDetails = substr_replace($aDetails, "", -1);

                    $aStages = $aStages."{"
                        ."\"stageName\":\"" . $aProjectStage ."\","
                        ."\"activateMenu\":\"" . $aActivateMenu ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","
                        ."\"details\":["
                            .$aDetails 
                        ."]"
                    ."},";
                } else {
                    $aStages = $aStages."{"
                        ."\"stageName\":\"" . $aProjectStage ."\","
                        ."\"activateMenu\":\"" . $aActivateMenu ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","
                        ."\"details\":[" ."]"
                    ."},";
                }
            }   
            $aStages = substr_replace($aStages, "", -1);
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"stages\":["
                   .$aStages 
                ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function deployments($pDeployments, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            
            foreach($pDeployments as $pDeployment){
                $aEntityId    = $pDeployment['entity_id'];
                $aEntityName  = $pDeployment['entity_name'];
                $aEnvironment = $pDeployment['property_name'];
                $aDateCreated = $pDeployment['date_created'];
                
                $StageData = $aPHPToJSONArray->entityLinkQueryBySubLinkJSON($aEntityId, "Deployment");
                $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsBySubEntityAndLinkType($StageData);

                $aProjectEntityID = NULL;
                foreach ($aProjectStageResults[resultsArray] as $key => $value) {
                    $aProjectEntityID = $value["main_entity"];
                }
                
                $aProjectResults = $aSystemEntityDAO->findRecordWithID($aProjectEntityID);

                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aProjectEntityID);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    foreach($aDetailsArray as $aDetail){
                        $aContent = $aDetail['entity_detail_content'];
                        $aContentId = $aDetail['entity_detail_content'];
                        if(is_numeric($aContent)){
                            $aSystemPropertyDAO = new SystemPropertyDAO();

                            $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                            if($aPropertyResults['status']){
                                $aContent = $aPropertyResults['resultsArray']['property_name'];
                            }
                        }
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"entityDetailContentId\":\"" . ucfirst($aContentId) ."\"," 
                            ."\"entityDetailContent\":\"" . ucfirst($aContent) ."\""       
                        ."},";
                    }
                    $aDetails = substr_replace($aDetails, "", -1);
                }
                
                if($aProjectResults['status']) {
                    $aItems = $aItems."{"
                        ."\"deploymentId\":\"" . $aEntityName ."\","
                        ."\"projectId\":\"" . $aProjectResults[resultsArray][entity_name] ."\","
                        ."\"environment\":\"" . $aEnvironment ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","
                        ."\"details\":["
                            .$aDetails 
                        ."]"
                    ."},";
                }
            }   
            $aItems = substr_replace($aItems, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"deployments\":["
                       .$aItems 
                    ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function qaProjects($pDeployments, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aSystemEntityLinkDAO = new SystemEntityLinkDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            $aAccessValidator = new AccessValidator();
            
            $aAccessLevel = $aAccessValidator->getSystemUser()->getAccessLevel();
            $aProjects = array();
            $index = 0;
            foreach($pDeployments as $pDeployment){
                $aEntityId    = $pDeployment['entity_id'];
                $aUserId      = $pDeployment['user_id'];
                $aEntityName  = $pDeployment['entity_name'];
                $aEnvironment = $pDeployment['property_name'];
                $aDateCreated = $pDeployment['date_created'];
                
                if($aAccessLevel == AccessValidator::CLIENT){
                    if($aUserId != $aAccessValidator->getSystemUser()->getUserID()) {
                        continue;
                    }
                }
                
                $StageData = $aPHPToJSONArray->entityLinkQueryBySubLinkJSON($aEntityId, "Deployment");
                $aProjectStageResults = $aSystemEntityLinkDAO->findRecordsBySubEntityAndLinkType($StageData);

                $aProjectEntityID = NULL;
                foreach ($aProjectStageResults[resultsArray] as $key => $value) {
                    $aProjectEntityID = $value["main_entity"];
                }
                
                $value = $aEnvironment.$aProjectEntityID;
                
                if(!in_array($value, $aProjects)){
                    $aProjects[$index ++] = $value;

                    $aProjectResults = $aSystemEntityDAO->findRecordWithID($aProjectEntityID);

                    $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aProjectEntityID);
                    if($aDetailsResults['status']) {
                        $aDetailsArray = $aDetailsResults['resultsArray'];
                        $aDetails = "";
                        foreach($aDetailsArray as $aDetail){
                            $aContent = $aDetail['entity_detail_content'];
                            $aContentId = $aDetail['entity_detail_content'];
                            if(is_numeric($aContent)){
                                $aSystemPropertyDAO = new SystemPropertyDAO();

                                $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                                if($aPropertyResults['status']){
                                    $aContent = $aPropertyResults['resultsArray']['property_name'];
                                }
                            }
                            $aDetails = $aDetails."{"
                                ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                                ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                                ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                                ."\"entityDetailContentId\":\"" . ucfirst($aContentId) ."\"," 
                                ."\"entityDetailContent\":\"" . ucfirst($aContent) ."\""       
                            ."},";
                        }
                        $aDetails = substr_replace($aDetails, "", -1);
                    }

                    if($aProjectResults['status']) {
                        $aItems = $aItems."{"
                            ."\"deploymentId\":\"" . $aEntityName ."\","
                            ."\"projectId\":\"" . $aProjectResults[resultsArray][entity_name] ."\","
                            ."\"environment\":\"" . $aEnvironment ."\","
                            ."\"dateCreated\":\"" . $aDateCreated ."\","
                            ."\"details\":["
                                .$aDetails 
                            ."]"
                        ."},";
                    }
                }
            }   
            $aItems = substr_replace($aItems, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"deployments\":["
                       .$aItems 
                    ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function stageItems($pLinks, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            foreach($pLinks as $pLink){
                $aEntityId    = $pLink['sub_entity'];
                
                $aDateCreated = $pLink['date_created'];
                
                $aEntityResults = $aSystemEntityDAO->findRecordWithID($aEntityId);
                if($aEntityResults['status']) {
                    $aStageId = $aEntityResults['resultsArray']['entity_name'];
                    $aItemType = $aEntityResults['resultsArray']['property_name'];
                    $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                    if($aDetailsResults['status']) {
                        $aDetailsArray = $aDetailsResults['resultsArray'];
                        $aDetails = "";
                        foreach($aDetailsArray as $aDetail){
                            $aContent = $aDetail['entity_detail_content'];
                            $aContentId = $aDetail['entity_detail_content'];
                            if(is_numeric($aContent)){
                                $aSystemPropertyDAO = new SystemPropertyDAO();

                                $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                                if($aPropertyResults['status']){
                                    $aContent = $aPropertyResults['resultsArray']['property_name'];
                                }
                            }
                            $aDetails = $aDetails."{"
                                ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                                ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                                ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                                ."\"entityDetailContentId\":\"" . ucfirst($aContentId) ."\"," 
                                ."\"entityDetailContent\":\"" . ucfirst($aContent) ."\""       
                            ."},";
                        }

                        $aDetails = substr_replace($aDetails, "", -1);

                        $aItems = $aItems."{"
                            ."\"stageId\":\"" . $aStageId ."\","
                            ."\"itemType\":\"" . $aItemType ."\","
                            ."\"dateCreated\":\"" . $aDateCreated ."\","
                            ."\"details\":["
                                .$aDetails 
                            ."]"
                        ."},";
                    } else {
                        $aItems = $aItems."{"
                            ."\"stageId\":\"" . $aStageId ."\","
                            ."\"itemType\":\"" . $aItemType ."\","
                            ."\"dateCreated\":\"" . $aDateCreated ."\","
                            ."\"details\":[" ."]"
                        ."},";
                    }
                }
            }   
            $aItems = substr_replace($aItems, "", -1);
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"items\":["
                   .$aItems 
                ."]"
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function stageItem($pEntity, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            $aSystemEntityDAO = new SystemEntityDAO();
            $aEntityId    = $pEntity['entity_id'];
            $aItemId      = $pEntity['entity_name'];
            $aItemType    = $pEntity['property_name'];
            $aDateCreated = $pEntity['date_created'];
                
            $aEntityResults = $aSystemEntityDAO->findRecordWithID($aEntityId);
            if($aEntityResults['status']) {
                $aStageId = $aEntityResults['resultsArray']['entity_name'];
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    foreach($aDetailsArray as $aDetail){
                        $aContent = $aDetail['entity_detail_content'];
                        $aContentId = $aDetail['entity_detail_content'];
                        if(is_numeric($aContent)){
                            $aSystemPropertyDAO = new SystemPropertyDAO();
                            
                            $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                            if($aPropertyResults['status']){
                                $aContent = $aPropertyResults['resultsArray']['property_name'];
                            }
                        }
                        
                        if($aDetail['property_name'] === "Assigned Person") {
                            $aAssignedUserDetailResults = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aContentId);
                            if($aAssignedUserDetailResults["status"]) {
                                foreach($aAssignedUserDetailResults[resultsArray] as $aResult){
                                   if($aResult[property_name] === "First Name") {
                                       $aContent = $aContentId." - ".$aResult[entity_detail_content];
                                   }elseif($aResult[property_name] === "Last Name") {
                                       $aContent = $aContent." ".$aResult[entity_detail_content];
                                   }
                                }
                            }
                        }
                        
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"entityDetailContentId\":\"" . ucfirst($aContentId) ."\"," 
                            ."\"entityDetailContent\":\"" . ucfirst($aContent) ."\""       
                        ."},";
                    }

                    $aDetails = substr_replace($aDetails, "", -1);

                    $aItem = $aItem."{"
                        ."\"stageId\":\"" . $aItemId ."\","
                        ."\"itemType\":\"" . $aItemType ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","
                        ."\"details\":["
                            .$aDetails 
                        ."]"
                    ."}";
                } else {
                    $aItem = $aItem."{"
                        ."\"stageId\":\"" . $aItemId ."\","
                        ."\"itemType\":\"" . $aItemType ."\","
                        ."\"dateCreated\":\"" . $aDateCreated ."\","
                        ."\"details\":[" ."]"
                    ."}";
                }
            } 
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"item\":"
                   .$aItem 
                .""
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function project($aEntity, $pMessage) {
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            
            $aEntityId  = $aEntity['entity_id'];
            $aProjectKey = $aEntity['entity_name'];
            $aUserKey = $aEntity['user_key'];

            $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
            if($aDetailsResults['status']) {
                $aDetailsArray = $aDetailsResults['resultsArray'];
                $aDetails = "";
                foreach($aDetailsArray as $aDetail){
                    $aContent = $aDetail['entity_detail_content'];
                    $aContentId = $aDetail['entity_detail_content'];
                    if(is_numeric($aContent)){
                        $aSystemPropertyDAO = new SystemPropertyDAO();

                        $aPropertyResults = $aSystemPropertyDAO->findRecordByPropertyId($aContent);
                        if($aPropertyResults['status']){
                            $aContent = $aPropertyResults['resultsArray']['property_name'];
                        }
                    }
                    
                    $aDetails = $aDetails."{"
                        ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                        ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                        ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                        ."\"entityDetailContentId\":\"" . $this->cleanJSONdata($aContentId) ."\"," 
                        ."\"entityDetailContent\":\"" . $this->cleanJSONdata($aContent) ."\""       
                    ."},";
                }

                $aDetails = substr_replace($aDetails, "", -1);

                $aProject = $aProject."{"
                    ."\"projectId\":\"" . $aProjectKey ."\","
                    ."\"clientId\":\"" . $aUserKey ."\","
                    ."\"details\":["
                        .$aDetails 
                    ."]"
                ."}";
            } else {
                $aProject = $aProject."{"
                    ."\"projectId\":\"" . $aProjectKey ."\","
                    ."\"clientId\":\"" . $aUserKey ."\","
                    ."\"details\":[" ."]"
                ."}";
            }
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"project\":"
                   .$aProject
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pEntity
         * @param string message
         * @return JSON string
         */
        public function client($pEntity, $pMessage) {
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            
            $aEntityId  = $pEntity['entity_id'];
            $aClientKey = $pEntity['entity_name'];
                
            $aUserResults = $aSystemUserDAO->findRecordWithClientID($aClientKey);
            if($aUserResults['status']) {
                $aEmailAddress = $aUserResults['resultsArray']['email'];
                $aUserID = $aUserResults['resultsArray']['user_id'];
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntity($aEntityId);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    foreach($aDetailsArray as $aDetail){
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"GroupName\":\"" .  $aDetail['group_name'] ."\","
                            ."\"entityDetailContent\":\"" . $aDetail['entity_detail_content'] ."\""       
                        ."},";
                    }

                    $aDetails = substr_replace($aDetails, "", -1);

                    $aClients = $aClients."{"
                        ."\"emailAddress\":\"" . $aEmailAddress ."\","
                        ."\"clientID\":\"" . $aClientKey ."\","
                        ."\"userID\":\"" . $aUserID ."\","
                        ."\"details\":["
                            .$aDetails 
                        ."]"
                    ."}";
                }
            }
            
            return "{ "
                ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                ."\"message\":"."\"".$pMessage ."\","
                ."\"client\":"
                   .$aClients 
                . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemGroups($pResults, $pMessage) {
            $aPriority = 1;
            foreach($pResults as $aResult){  
                $aGroups = $aGroups."{"
                    ."\"priority\":\"" . ($aPriority ++) ."\","
                    ."\"groupId\":\"" .  $aResult['group_id'] ."\","
                    ."\"userCreated\":\"" .  $aResult['user_id'] ."\","
                    ."\"dateCreated\":\"" .  $aResult['date_created'] ."\","
                    ."\"groupName\":\"" .  $aResult['group_name'] ."\","
                    ."\"groupValue\":\"" .  $aResult['group_value'] ."\","
                    ."\"groupDescription\":\"" . $aResult['group_description'] ."\""       
                ."}";
                
                $aGroups = $aGroups.",";
            }
            $aGroups = substr_replace($aGroups, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemGroups\":["
                       .$aGroups 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemGroup($pResult, $pMessage) {
            $aPriority = 100;
            $aGroup = $aGroup. "{"
                ."\"priority\":\"" . ($aPriority ++) ."\","
                ."\"groupId\":\"" .  $pResult['group_id'] ."\","
                ."\"userCreated\":\"" .  $pResult['user_id'] ."\","
                ."\"dateCreated\":\"" .  $pResult['date_created'] ."\","
                ."\"groupName\":\"" .  $pResult['group_name'] ."\","
                ."\"groupValue\":\"" .  $pResult['group_value'] ."\","
                ."\"groupDescription\":\"" . $pResult['group_description'] ."\""       
            ."}";

            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemGroup\":"
                       .$aGroup
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemUser($pResult, $pMessage) {
            $aPriority = 100;
            $aUser = $aUser. "{"
                ."\"priority\":\"" . ($aPriority ++) ."\","
                ."\"userId\":\"" .  $pResult['user_id'] ."\","
                ."\"userKey\":\"" .  $pResult['user_key'] ."\","
                ."\"email\":\"" .  $pResult['email'] ."\","
                ."\"roleName\":\"" .  $pResult['role_name'] ."\","
                ."\"dateCreated\":\"" .  $pResult['date_created'] ."\","
                ."\"password\":\"" .  $pResult['password'] ."\""
            ."}";

            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemUser\":"
                       .$aUser
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemProperties($pResults, $pMessage) {
            $aPriority = 1;
            foreach($pResults as $aResult){  
                $aProperties = $aProperties."{"
                    ."\"priority\":\"" . ($aPriority ++) ."\","
                    ."\"propertyId\":\"" .  $aResult['property_id'] ."\","
                    ."\"groupId\":\"" .  $aResult['group_id'] ."\","
                    ."\"groupName\":\"" .  $aResult['group_name'] ."\","
                    ."\"userCreated\":\"" .  $aResult['user_id'] ."\","
                    ."\"dateCreated\":\"" .  $aResult['date_created'] ."\","
                    ."\"propertyName\":\"" .  $aResult['property_name'] ."\","
                    ."\"propertyValue\":\"" .  $aResult['property_value'] ."\","
                    ."\"propertyDescription\":\"" . $aResult['property_description'] ."\""       
                ."}";
                
                $aProperties = $aProperties.",";
            }
            $aProperties = substr_replace($aProperties, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemProperties\":["
                       .$aProperties 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function deal($pResult, $pMessage) {
            $aAmount = "R".number_format($pResult[deal_price],2);
            $aDeals = $aDeals."{"
                ."\"dealId\":\"" .  $pResult['deal_id'] ."\","
                ."\"dealCode\":\"" .  $pResult['deal_code'] ."\","
                ."\"dealName\":\"" .  $pResult['deal_name'] ."\","
                ."\"dealDescription\":\"" .  $pResult['deal_description'] ."\","
                ."\"dealPrice\":\"" .  $pResult['deal_price'] ."\","
                ."\"dealAmountPrice\":\"" .  $aAmount ."\","
                ."\"startDate\":\"" .  $pResult['start_date'] ."\","
                ."\"endDate\":\"" . $pResult['end_date'] ."\""       
            ."}";
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"developmentDeal\":"
                       .$aDeals 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function deals($pResults, $pMessage) {
            foreach($pResults as $aResult){  
                $aAmount = "R".number_format($aResult[deal_price],2);
                $aDeals = $aDeals."{"
                    ."\"dealId\":\"" .  $aResult['deal_id'] ."\","
                    ."\"dealCode\":\"" .  $aResult['deal_code'] ."\","
                    ."\"dealName\":\"" .  $aResult['deal_name'] ."\","
                    ."\"dealDescription\":\"" .  $aResult['deal_description'] ."\","
                    ."\"dealPrice\":\"" .  $aResult['deal_price'] ."\","
                    ."\"dealAmountPrice\":\"" .  $aAmount ."\","
                    ."\"startDate\":\"" .  $aResult['start_date'] ."\","
                    ."\"endDate\":\"" . $aResult['end_date'] ."\""       
                ."}";
                
                $aDeals = $aDeals.",";
            }
            $aDeals = substr_replace($aDeals, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"developmentDeals\":["
                       .$aDeals 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function quotes($pResults, $pMessage) {
            foreach($pResults as $aResult){  
                $aQuotes = $aQuotes."{"
                    ."\"quoteNumber\":\"" .  $aResult['quote_name'] ."\","
                    ."\"projectId\":\"" .  $aResult['project_id'] ."\","
                    ."\"startDate\":\"" .  $aResult['start_date'] ."\","
                    ."\"endDate\":\"" . $aResult['end_date'] ."\""       
                ."}";
                
                $aQuotes = $aQuotes.",";
            }
            $aQuotes = substr_replace($aQuotes, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"quotes\":["
                       .$aQuotes 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemUsers($pResults, $pMessage) {
            $aPriority = 1;
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            foreach($pResults as $aResult){  
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aResult['user_key']);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aDetails = "";
                    $aFirstName = "";
                    $aLastName = "";
                    foreach($aDetailsArray as $aDetail){
                        
                        if($aDetail['property_name'] === "First Name") {
                            $aFirstName = $aDetail['entity_detail_content'];
                        }elseif($aDetail['property_name'] === "Last Name") {
                            $aLastName = $aDetail['entity_detail_content'];
                        }
                        
                        $aDetails = $aDetails."{"
                            ."\"entityId\":\"" .  $aDetail['entity_id'] ."\","
                            ."\"entityDetailId\":\"" .  $aDetail['entity_detail_id'] ."\","
                            ."\"typeName\":\"" .  $aDetail['property_name'] ."\","
                            ."\"entityDetailContent\":\"" . $aDetail['entity_detail_content'] ."\""       
                        ."},";
                    }
                    $aDetails = substr_replace($aDetails, "", -1);
                }
                
                $aUsers = $aUsers."{"
                    ."\"priority\":\"" . ($aPriority ++) ."\","
                    ."\"userId\":\"" .  $aResult['user_id'] ."\","
                    ."\"roleId\":\"" .  $aResult['role_id'] ."\","
                    ."\"firstName\":\"" .  $aFirstName."\","
                    ."\"lastName\":\"" .  $aLastName ."\","
                    ."\"roleName\":\"" .  $aResult['role_name'] ."\","
                    ."\"userKey\":\"" .  $aResult['user_key'] ."\","
                    ."\"email\":\"" .  $aResult['email'] ."\","
                    ."\"dateCreated\":\"" .  $aResult['date_created'] ."\","
                    ."\"details\":["
                        .$aDetails 
                    ."]"    
                ."}";

                $aUsers = $aUsers.",";
            }
            $aUsers = substr_replace($aUsers, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemUsers\":["
                       .$aUsers 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResult
         * @param string message
         * @return JSON string
         */
        public function supportInvoice($pResult, $pMessage) {
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            
            $aUserRecord = $aSystemUserDAO->findRecordWithUserID($pResult['user_id']);
            if($aUserRecord[status]) {
                $aUserKey = $aUserRecord[resultsArray][user_key];
                $aEmailAddress = $aUserRecord[resultsArray][email];
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserKey);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aFirstName = "";
                    $aLastName = "";
                    foreach($aDetailsArray as $aDetail){
                        
                        if($aDetail['property_name'] === "First Name") {
                            $aFirstName = $aDetail['entity_detail_content'];
                        }elseif($aDetail['property_name'] === "Last Name") {
                            $aLastName = $aDetail['entity_detail_content'];
                        }
                    }
                }
            }
            $aAmount = "R".number_format($pResult[amount],2);
            $aInvoice = $aInvoice."{"
                ."\"userId\":\"" .  $pResult['user_id'] ."\","
                ."\"fullnames\":\"" .  $aFirstName." ".$aLastName."\","
                ."\"clientId\":\"" .  $aUserKey ."\","
                ."\"emailAddress\":\"" .  $aEmailAddress ."\","
                ."\"paymentId\":\"" .  $pResult['payment_id'] ."\","
                ."\"projectId\":\"" .  $pResult['project_id'] ."\","
                ."\"reference\":\"" .  $pResult['reference'] ."\","
                ."\"description\":\"" .  $pResult['description'] ."\","
                ."\"amount\":\"" .  $aAmount ."\","
                ."\"paymentDate\":\"" .  $pResult['payment_date'] ."\","
                ."\"dateCreated\":\"" .  $pResult['date_created'] ."\""      
            ."}";
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"invoice\":"
                       .$aInvoice 
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function supportInvoices($pResults, $pMessage) {
            foreach($pResults as $aResult){    
                $aAmount = "R".number_format($aResult[amount],2);
                $aInvoices = $aInvoices."{"
                    ."\"userId\":\"" .  $aResult['user_id'] ."\","
                    ."\"paymentId\":\"" .  $aResult['payment_id'] ."\","
                    ."\"projectId\":\"" .  $aResult['project_id'] ."\","
                    ."\"reference\":\"" .  $aResult['reference'] ."\","
                    ."\"description\":\"" .  $aResult['description'] ."\","
                    ."\"amount\":\"" .  $aAmount ."\","
                    ."\"paymentDate\":\"" .  $aResult['payment_date'] ."\","
                    ."\"dateCreated\":\"" .  $aResult['date_created'] ."\""      
                ."}";

                $aInvoices = $aInvoices.",";
            }
            $aInvoices = substr_replace($aInvoices, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"invoices\":["
                       .$aInvoices 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function supportTickets($pResults, $pMessage) {
            foreach($pResults as $aResult){  
                $aStatus = $aResult[support_status];
                if($aStatus === "1"){
                    $aStatusDescription = "Inititated";
                }else if($aStatus === "2"){
                    $aStatusDescription = "Assigned";
                }else if($aStatus === "3"){
                    $aStatusDescription = "Completed";
                }
                
                $aTickets = $aTickets."{"
                    ."\"userId\":\"" .  $aResult['user_id'] ."\","
                    ."\"supportId\":\"" .  $aResult['support_id'] ."\","
                    ."\"projectId\":\"" .  $aResult['project_id'] ."\","
                    ."\"description\":\"" .  $aResult['support_description'] ."\","
                    ."\"status\":\"" .  $aStatusDescription ."\","
                    ."\"dueDate\":\"" .  $aResult['due_date'] ."\","
                    ."\"dateCreated\":\"" .  $aResult['date_created'] ."\""      
                ."}";

                $aTickets = $aTickets.",";
            }
            $aTickets = substr_replace($aTickets, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"supportTickets\":["
                       .$aTickets 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function supportTicket($pResult, $pMessage) {
            $aSystemUserDAO = new SystemUserDAO();
            $aSystemEntityDetailsDAO = new SystemEntityDetailsDAO();
            
            $aUserRecord = $aSystemUserDAO->findRecordWithUserID($pResult['user_id']);
            if($aUserRecord[status]) {
                $aUserKey = $aUserRecord[resultsArray][user_key];
                $aEmailAddress = $aUserRecord[resultsArray][email];
                $aDetailsResults = $aSystemEntityDetailsDAO->findRecordsForEntityByEntityName($aUserKey);
                if($aDetailsResults['status']) {
                    $aDetailsArray = $aDetailsResults['resultsArray'];
                    $aFirstName = "";
                    $aLastName = "";
                    foreach($aDetailsArray as $aDetail){
                        
                        if($aDetail['property_name'] === "First Name") {
                            $aFirstName = $aDetail['entity_detail_content'];
                        }elseif($aDetail['property_name'] === "Last Name") {
                            $aLastName = $aDetail['entity_detail_content'];
                        }
                    }
                }
            }
            
            $aStatus = $pResult[support_status];
            if($aStatus === "1"){
                $aStatusDescription = "Inititated";
            }else if($aStatus === "2"){
                $aStatusDescription = "Assigned";
            }else if($aStatus === "3"){
                $aStatusDescription = "Completed";
            }
            
            $aTicket = $aTicket."{"
                ."\"userId\":\"" .  $pResult['user_id'] ."\","
                ."\"supportId\":\"" .  $pResult['support_id'] ."\","
                ."\"projectId\":\"" .  $pResult['project_id'] ."\","
                ."\"clientId\":\"" .  $aUserKey ."\","
                ."\"fullnames\":\"" .  $aFirstName." ".$aLastName."\","
                ."\"emailAddress\":\"" .  $aEmailAddress ."\","
                ."\"description\":\"" .  $pResult['support_description'] ."\","
                ."\"status\":\"" .  $aStatusDescription ."\","
                ."\"dueDate\":\"" .  $pResult['due_date'] ."\","
                ."\"dateCreated\":\"" .  $pResult['date_created'] ."\""      
            ."}";
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"supportTicket\":"
                       .$aTicket
                    .""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemRoles($pResults, $pMessage) {
            $aPriority = 1;
            foreach($pResults as $aResult){  
                $aRoles = $aRoles."{"
                    ."\"priority\":\"" . ($aPriority ++) ."\","
                    ."\"userId\":\"" .  $aResult['user_id'] ."\","
                    ."\"roleId\":\"" .  $aResult['role_id'] ."\","
                    ."\"roleName\":\"" .  $aResult['role_name'] ."\","
                    ."\"roleDescription\":\"" .  $aResult['description'] ."\","
                    ."\"dateCreated\":\"" .  $aResult['date_created'] ."\""      
                ."}";

                $aRoles = $aRoles.",";
            }
            $aRoles = substr_replace($aRoles, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemRoles\":["
                       .$aRoles 
                    ."]"
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHPArray pResults
         * @param string message
         * @return JSON string
         */
        public function systemProperty($pResult, $pMessage) {
            $aPriority = 100;
            $aProperty = $aProperty. "{"
                ."\"priority\":\"" . ($aPriority ++) ."\","
                ."\"propertyId\":\"" .  $pResult['property_id'] ."\","
                ."\"groupId\":\"" .  $pResult['group_id'] ."\","
                ."\"groupName\":\"" .  $pResult['group_name'] ."\","
                ."\"userCreated\":\"" .  $pResult['user_id'] ."\","
                ."\"dateCreated\":\"" .  $pResult['date_created'] ."\","
                ."\"propertyName\":\"" .  $pResult['property_name'] ."\","
                ."\"propertyValue\":\"" .  $pResult['property_value'] ."\","
                ."\"propertyDescription\":\"" . $pResult['property_description'] ."\""       
            ."}";

            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"systemProperty\":"
                       .$aProperty
                    . "}";
        }
        /**
         * 
         * @param type $aJson
         * @return String aJson
         */
        public function cleanJSONdata($aJson) {
            $aJson = empty($aJson) ? '[]' : $aJson ;
            $aSearch = array('\\',"\n","\r","\f","\t","\b",'"') ;
            $aReplace = array('\\\\',"\\n", "\\r","\\f","\\t","\\b", "'");
            $aJson = str_replace($aSearch,$aReplace,$aJson);

            return $aJson;
        }
    }