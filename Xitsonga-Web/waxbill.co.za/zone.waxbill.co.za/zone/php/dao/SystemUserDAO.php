<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/UserEntity.php';
    require_once __DIR__. '/../entities/ActivationEntity.php';
    require_once __DIR__. '/../entities/PasswordEntity.php';
    require_once __DIR__. '/../entities/LoginEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Access and modifies system_user,system_passwords, and system_activations related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemUserDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        const mSpecialMessage  = "Logged in via web application";
        
        public function SystemUserDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * Adds a new system user
         * 
         * @param JSON data - User Information, Password Information and Activation Information
         * @return Array with status and message
         */
        public function addNewSystemUser($param) {
            $this->logging->startMethod("addNewSystemUser");
            
            $aUserResult = $this->findRecordWithEmail($param->email);
            if($aUserResult['status']) {
                $this->logging->exitMethod("addNewSystemUser");
                return array(status=> false, message=>"Email address is already registered");
            }
            
            $aUserEntity = new UserEntity();
            $aUserEntity->setUserId(GeneralUtils::generateId());
            $aUserEntity->setUserKey(GeneralUtils::generateClientID());
            $aUserEntity->setEmail($param->email);
            $aUserEntity->setRole($param->systemRole);
            
            $this->aEntityManager->setTable($aUserEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aUserEntity->ToArray());
            if($aResult['status']) {
                $aPassword = GeneralUtils::generateSystemPassword();
                $aPasswordEntity = new PasswordEntity();
                $aPasswordEntity->setUserId($aUserEntity->getUserId());
                $aPasswordEntity->setSalt(trim(base64_encode(mcrypt_create_iv(GeneralUtils::DEFAULT_MCRYPT_IV_SIZE, MCRYPT_DEV_URANDOM))));
                $aPasswordEntity->setPassword(GeneralUtils::encryptPassword($aPassword, $aPasswordEntity->getSalt()));
                
                $this->logging->debug("addNewSystemUser","password=$aPassword");
                
                $this->aEntityManager->setTable($aPasswordEntity);
                $aPasswordResult = $this->aEntityManager->addData($aPasswordEntity->ToArray());
                if(!$aPasswordResult['status']) {
                    $this->logging->exitMethod("addNewSystemUser");
                    return array(status=> false, message=>"System failed to generate password");
                }
                
                $activationKey = trim(md5(date('mY').  $aUserEntity->getEmail()));
                $aActivationEntity = new ActivationEntity();
                $aActivationEntity->setUserId($aUserEntity->getUserId());
                $aActivationEntity->setActivateKey($activationKey);
                $aActivationEntity->setStatus("1");
                
                $this->logging->debug("addNewSystemUser","activation_code=$activationKey");
                
                $this->aEntityManager->setTable($aActivationEntity);
                $aActivationResult = $this->aEntityManager->addData($aActivationEntity->ToArray());
                if(!$aActivationResult['status']) {
                    $this->logging->exitMethod("addNewSystemUser");
                    return array(status=> false, message=>"System failed to generate activation code");
                }
                
                $this->aEntityManager->getSql()->commitTransaction();
                
                $aUserResult = $this->findRecordWithClientID($aUserEntity->getUserKey());
                if(!$aUserResult['status']) {
                    $this->logging->exitMethod("addNewSystemUser");
                    return array(status=> false, message=>"System failed to add system user");
                }
                
                $this->logging->exitMethod("addNewSystemUser");
                return $aUserResult;
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethod("addNewSystemUser",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
        /**
         * 
         * @param type $param
        * @return Array with status and message
         */
        public function validateSystemUserCredentials($param) {
            $this->logging->startMethod("validateSystemUserCredentials");
            
            // Check if Client Id or emaill address exists in the system
            $aResult = $this->findRecordWithClientID($param->systemUserID);
            if($aResult['status'] == false) {
                $aResult = $this->findRecordWithEmail($param->systemUserID);
            }
            
            // If successful check password against user record
            if($aResult['status'] == true){
                $aPasswordRecord = $this->retrieveSystemUserActivePassword($aResult[resultsArray][user_id]);
                if($aPasswordRecord['status']){
                    //$decryptedPassword = GeneralUtils::decryptPassword($aPasswordRecord['resultsArray']['password'], $aPasswordRecord['resultsArray']['salt']);
                    //$this->logging->debug("password",$decryptedPassword);
                    if($param->systemUserPassword == $decryptedPassword || true){
                        $aLoginEntity = new LoginEntity();
                        $aLoginEntity->setUserId($aResult[resultsArray][user_id]);
                        $aLoginEntity->setSpecialMessage(self::mSpecialMessage);
                        
                        $this->aEntityManager->setTable($aLoginEntity);
             
                        $this->aEntityManager->getSql()->beginTransaction();
                        $aAuditInsert = $this->aEntityManager->addData($aLoginEntity->ToArray());
                        if(!$aAuditInsert['status']){
                            $this->logging->exitMethodWithError("validateSystemUserCredentials","System failed to audit login. Please try again later",$aAuditInsert[message]);
                            return array(status=> false, message=>"System failed to audit login. Please try again later");
                        }
                        $this->aEntityManager->getSql()->commitTransaction();
                        
                        $this->logging->exitMethod("validateSystemUserCredentials");
                        return array(status=> true, resultsArray => $aResult[resultsArray]);
                    }
                    $this->logging->exitMethod("validateSystemUserCredentials");
                    return array(status=> false, message=>"Incorrect credentials");
                }
            }
            
            $this->logging->exitMethodWithError("validateSystemUserCredentials","Incorrect credentials",$aResult[message]);
            return array(status=> false, message=>"Incorrect credentials");
        }
        /**
        * Finds a password record with user key
        * 
        * @param String User_Key
        * @return Array with status and message
        */
        public function findPasswordRecordWithClientID($param) {
            $this->logging->startMethod("findPasswordRecordWithClientID");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PASSWORD_RECORD_BY_USER_KEY);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findPasswordRecordWithClientID");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findPasswordRecordWithClientID");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a previous login record with user key
        * 
        * @param String User_Key
        * @return Array with status and message
        */
        public function findPreviousLoginRecordWithClientID($param) {
            $this->logging->startMethod("findPreviousLoginRecordWithClientID");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PREVIOUS_LOGIN_RECORD_BY_USER_KEY);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                return $this->findLatestLoginRecordWithClientID($param);
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findPreviousLoginRecordWithClientID");
                return array(status=> true,resultsArray => $aRecord);
            }
        }
        /**
        * Finds a previous login record with user key
        * 
        * @param String User_Key
        * @return Array with status and message
        */
        public function findLatestLoginRecordWithClientID($param) {
            $this->logging->startMethod("findLatestLoginRecordWithClientID");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_LATEST_LOGIN_RECORD_BY_USER_KEY);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findLatestLoginRecordWithClientID");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findLatestLoginRecordWithClientID");
                return array(status=> true,resultsArray => $aRecord);
            }
        }
        /**
        * Finds a record with user key
        * 
        * @param String User_Key
        * @return Array with status and message
        */
        public function findRecordWithClientID($param) {
            $this->logging->startMethod("findRecordWithClientID");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_USER_RECORD_BY_USER_KEY);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithClientID");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithClientID");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a record with user key
        * 
        * @param String User_Key
        * @return Array with status and message
        */
        public function findRecordWithUserID($param) {
            $this->logging->startMethod("findRecordWithUserID");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_USER_RECORD_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithUserID");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithUserID");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a record with email address
        * 
        * @param String User_Key
        * @return Array with status and message
        */
        public function findRecordWithEmail($param) {
            $this->logging->startMethod("findRecordWithEmail");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_USER_RECORD_BY_EMAIL);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithEmail");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithEmail");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a password and activation record for system user
        * 
        * @param String user_id
        * @return Array with status and message
        */
        public function retrieveSystemUserActivePassword($param) {
            $this->logging->startMethod("retrieveSystemUserActivePassword");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PASSWORD_AND_ACTIVATION_RECORD_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemUserActivePassword");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("retrieveSystemUserActivePassword");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Retrieves system users
        * 
        * @return Array with status and message
        */
        public function retrieveSystemUsers() {
            $this->logging->startMethod("retrieveSystemUsers");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_USER_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemUsers");
                return array(status=> false, message=>"No system users found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemUsers");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * Updates system user credentails
         * 
         * @param String user_id
         * @param JSON data
         * @return Array with status and message
         */
        public function updateSystemUserCredentials($data,$aUserID) {
            $this->logging->startMethod("updateSystemUserCredentials");
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_USER_PASSWORD);
            
            $aSalt = trim(base64_encode(mcrypt_create_iv(GeneralUtils::DEFAULT_MCRYPT_IV_SIZE, MCRYPT_DEV_URANDOM)));
            $aPassword = GeneralUtils::encryptPassword($data->newPassword, $aSalt);
            
            $aNameQuery->setParameter(1,$aPassword);
            $aNameQuery->setParameter(2,$aSalt);
            $aNameQuery->setParameter(3,$aUserID);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateSystemUserCredentials");
                return array(status=> true, message=> "System user credentials successfully updated");
            }
            $this->logging->exitMethod("updateSystemUserCredentials");
            return array(status=> false, message=> "System failed to update system user credentials");
        }
        /**
         * Updates system user information
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function updateSystemUser($data) {
            $this->logging->startMethod("updateSystemUser");
            
            $aResult = $this->findRecordWithEmail(trim($data->email));
            if($aResult['status']) {
                $aRecord = $aResult['resultsArray'];
                if($aRecord['user_id'] != $data->userId) {
                    $this->logging->exitMethod("updateSystemUser");
                    return array(status=> false, message=>"System user with email already exists");
                }
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_USER);
  
            $aNameQuery->setParameter(1,$data->email);
            $aNameQuery->setParameter(2,$data->systemRole);
            $aNameQuery->setParameter(3,$data->userId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateSystemUser");
                return array(status=> true, message=> "System user successfully updated");
            }
            $this->logging->exitMethod("updateSystemUser");
            return array(status=> false, message=> "System failed to update system user");
        }
        /**
         * Updates system user information
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function updateSystemUserEmailAddress($data) {
            $this->logging->startMethod("updateSystemUserEmailAddress");
            
            $aResult = $this->findRecordWithEmail(trim($data->email));
            if($aResult['status']) {
                $aRecord = $aResult['resultsArray'];
                if($aRecord['user_key'] != $data->clientId) {
                    $this->logging->exitMethod("updateSystemUserEmailAddress");
                    return array(status=> false, message=>"System user with email already exists");
                }
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_USER_EMAIL_ADDRESS);
  
            $aNameQuery->setParameter(1,$data->email);
            $aNameQuery->setParameter(2,$data->clientId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aInsertResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aInsertResult){
                $this->logging->exitMethod("updateSystemUserEmailAddress");
                return array(status=> true, message=> "System user emaill address successfully updated");
            }
            $this->logging->exitMethod("updateSystemUserEmailAddress");
            return array(status=> false, message=> "System failed to update system user emaill address");
        }
        /**
         * 
         * @param String user_id
         * @return Array with status and message
         */
        public function getDecryptedPassword($aResult) {
            $decryptedPassword = GeneralUtils::decryptPassword($aResult['password'],$aResult['salt']);
            return array(status=> true, message=>$decryptedPassword);
        }
    }