<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/UserEntity.php';
    require_once __DIR__. '/../entities/UserDigitCodeEntity.php';
    require_once __DIR__. '/../entities/EmergencyContactEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Access and modifies users and user_digit_code  related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class UserDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
                
        public function UserDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * Retrieves user information
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function userLogin($userId, $param) {
            $this->logging->startMethod("userLogin");
             
            $aUserResults = $this->updateUserByUserId($userId, $param);
            if($aUserResults[status]) {
                $aUserByFacebookResult = $this->findUserByFacebookId($param->facebookId);
                if($aUserByFacebookResult['status']) {
                    $this->aEntityManager->getSql()->commitTransaction();
                    $this->logging->exitMethod("userLogin");
                    return $aUserByFacebookResult;
                }
                $this->logging->exitMethodWithError("userLogin",$aUserByPhoneResult[message]);
                return array(status=> false, message=>$aUserByPhoneResult[message]);
            }
            
            $this->logging->exitMethod("userLogin");
            return array(status=> false, message=>"Server side error occured");
        }
        /**
         * Adds a new user
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addNewUser($param) {
            $this->logging->startMethod("addNewUser");
            
            $aUserByFacebookResult = $this->findUserByFacebookId($param->facebookId);
            if($aUserByFacebookResult['status']) {
                $this->logging->exitMethod("addNewUser");
                return $this->userLogin($aUserByFacebookResult[record][user_id], $param);
            }
            
            $aUserEntity = new UserEntity();
            $aUserEntity->setFirebaseId($param->firebaseId);
            $aUserEntity->setFacebookId($param->facebookId);
            $aUserEntity->setPhoneNumber($param->phoneNumber);
            $aUserEntity->setFirstname($param->firstName);
            $aUserEntity->setLastName($param->lastName);
            $aUserEntity->setEmailAddress($param->emailAddress);
            $aUserEntity->setGender(ucfirst($param->gender));
            
            $this->aEntityManager->setTable($aUserEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aUserEntity->ToArray());
            if($aResult['status']) {
                $aUserByPhoneResult = $this->findUserByPhoneNumber($param->phoneNumber);
                if($aUserByPhoneResult['status']) {
                    $this->aEntityManager->getSql()->commitTransaction();
                    $this->logging->exitMethod("addNewUser");
                    return $aUserByPhoneResult;
                }
                $this->aEntityManager->getSql()->rollbackTransaction();
                $this->logging->exitMethodWithError("addNewUser",$aUserByPhoneResult[message]);
                return array(status=> false, message=>$aUserByPhoneResult[message]);
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewUser",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
        /**
         * Adds a new digit code
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addNewDigitCode($param) {
            $this->logging->startMethod("addNewDigitCode");
            
            $this->updateDigitCodesByPhoneNumber($param->phoneNumber);
            
            $UserDigitCodeEntity = new UserDigitCodeEntity();
            $UserDigitCodeEntity->setPhoneNumber($param->phoneNumber);
            $UserDigitCodeEntity->setDigitCode($param->digitCode);
            
            $this->aEntityManager->setTable($UserDigitCodeEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($UserDigitCodeEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $this->logging->exitMethod("addNewDigitCode");
                return array(status=> true, message=>"Digit added");
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewDigitCode",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
        
        /**
         * Adds a new digit code
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addEmergencyContacts($param) {
            $this->logging->startMethod("addEmergencyContacts");
                        
            $this->deleteEmergencyContactByUserId($param->userId);
            
            $EmergencyContactEntity = new EmergencyContactEntity();
            $EmergencyContactEntity->setUserId($param->userId);
            $EmergencyContactEntity->setPhoneNumber1($param->phoneNumber1);
            $EmergencyContactEntity->setPhoneNumber2($param->phoneNumber2);
            
            $this->aEntityManager->setTable($EmergencyContactEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($EmergencyContactEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $this->logging->exitMethod("addEmergencyContacts");
                return array(status=> true, message=>"Emergency contacts added");
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addEmergencyContacts",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
        /**
         * Updates digit code for specified phone number
         * 
         * @param JSON phone number
         * @return Array with status and message
         */
        public function updateUserByUserId($userId, $param) {
            $this->logging->startMethod("updateUserByUserId");

            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_USER_BY_USER_ID);
            $aNameQuery->setParameter(1,$userId);
            $aNameQuery->setParameter(2,$param->firebaseId);
            $aNameQuery->setParameter(3,$param->facebookId);
            $aNameQuery->setParameter(4,$param->phoneNumber);
            $aNameQuery->setParameter(5,$param->firstName);
            $aNameQuery->setParameter(6,$param->lastName);
            $aNameQuery->setParameter(7,$param->emailAddress);
            $aNameQuery->setParameter(8,$param->gender);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateUserByUserId");
                return array(status=> true, message=> "User updated");
            }
            $this->logging->exitMethod("updateUserByUserId");
            return array(status=> false, message=> "Service failed to update user");
        }
        /**
         * Updates digit code for specified phone number
         * 
         * @param JSON phone number
         * @return Array with status and message
         */
        public function updateDigitCodesByPhoneNumber($param) {
            $this->logging->startMethod("updateDigitCodesByPhoneNumber");
 
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_USER_DIGIT_CODE_BY_PHONE_NUMBER);
            $aNameQuery->setParameter(1,"0");
            $aNameQuery->setParameter(2,$param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateDigitCodesByPhoneNumber");
                return array(status=> true, message=> "Digit codes updated");
            }
            $this->logging->exitMethod("updateDigitCodesByPhoneNumber");
            return array(status=> false, message=> "Service failed to update digit codes");
        }
        /**
         * 
         * @param JSON phone number
         * @return Array with status and message
         */
        public function deleteEmergencyContactByUserId($param) {
            $this->logging->startMethod("deleteEmergencyContactByUserId");
 
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_CONTACT_BY_USER_ID);
            $aNameQuery->setParameter(1,$param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->removeData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteEmergencyContactByUserId");
                return array(status=> true, message=> "Service failed to delete contact");
            }
            $this->logging->exitMethod("deleteEmergencyContactByUserId");
            return array(status=> false, message=> "Service failed to delete contact");
        }
        /**
        * Finds a previous digit code for specified number
        * 
        * @param String phone number
        * @return Array with status and message
        */
        public function findPreviousDigitCodeByPhoneNumber($param) {
            $this->logging->startMethod("findPreviousDigitCodeByPhoneNumber");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_USER_DIGIT_CODE_BY_PHONE_NUMBER);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findPreviousDigitCodeByPhoneNumber");
                return array(status=> false,message => "Digit code not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findPreviousDigitCodeByPhoneNumber");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds a user by phone number
        * 
        * @param String phone number
        * @return Array with status and message
        */
        public function findUserByPhoneNumberFacebookIdAndFirebaseId($param) {
            $this->logging->startMethod("findUserByPhoneNumberFacebookIdAndFirebaseId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_USER_BY_F_F_P);
            $aNameQuery->setParameter(1, $param->firebaseId);
            $aNameQuery->setParameter(2, $param->facebookId);
            $aNameQuery->setParameter(3, $param->phoneNumber);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findUserByPhoneNumberFacebookIdAndFirebaseId");
                return array(status=> false,message => "User not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findUserByPhoneNumberFacebookIdAndFirebaseId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds a user by phone number
        * 
        * @param String phone number
        * @return Array with status and message
        */
        public function findUserByPhoneNumber($param) {
            $this->logging->startMethod("findUserByPhoneNumber");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_USER_BY_PHONE_NUMBER);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findUserByPhoneNumber");
                return array(status=> false,message => "User not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findUserByPhoneNumber");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds trips
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findContactsForUserId($param) {
            $this->logging->startMethod("findContactsForUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_CONTACTS_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findContactsForUserId");
                return array(status=> false,message => "You don't have contacts");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findContactsForUserId");
                return array(status=> true,record => $aRecords);
            }
        }
        /**
        * Finds a user by facebook id
        * 
        * @param String facebook id
        * @return Array with status and message
        */
        public function findUserByFacebookId($param) {
            $this->logging->startMethod("findUserByFacebookId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_USER_BY_FACEBOOK_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findUserByFacebookId");
                return array(status=> false,message => "User not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findUserByFacebookId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds a user by user id
        * 
        * @param String user id
        * @return Array with status and message
        */
        public function findUserByUserId($param) {
            $this->logging->startMethod("findUserByUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_USER_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findUserByUserId");
                return array(status=> false,message => "User does not exist");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findUserByUserId");
                return array(status=> true,record => $aRecord);
            }
        }
        
        /**
         * Updates digit code for specified phone number
         * 
         * @param JSON phone number
         * @return Array with status and message
         */
        public function deleteUserByUserId($param) {
            $this->logging->startMethod("deleteUserByUserId");
 
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_USER_BY_USER_ID);
            $aNameQuery->setParameter(1,"0");
            $aNameQuery->setParameter(2,$param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteUserByUserId");
                return array(status=> true, message=> "Account has been deleted");
            }
            $this->logging->exitMethod("deleteUserByUserId");
            return array(status=> false, message=> "Service failed to delete user");
        }
    }