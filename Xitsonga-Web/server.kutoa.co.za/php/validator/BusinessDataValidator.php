<?php
    require_once __DIR__.'/../dao/UserDAO.php';
    /**
     * Business Data Validator
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0  
     */
    class BusinessDataValidator {  

        public function validateAccess($param) {
            $aUserDAO = new UserDAO();
            
            $aUserResults = $aUserDAO->findUserByUserId($param->userId);
            if($aUserResults[status]) {
                $aUserRecord = $aUserResults[record];
                
                if($aUserRecord[firebase_id] == $param->firebaseId) {
                    return array(status=> true);
                }
            }
            return array(status=> false, message=>"Device session expired. You must re-login"); 
        }
        
        public function validateContactAvailable($param) {
            $aUserDAO = new UserDAO();
            
            $aUserResults = $aUserDAO->findContactsForUserId($param->userId);
            if($aUserResults[status]) {
                $aUserRecord = $aUserResults[record];
                if(strlen(($aUserRecord[phone_number1])) > 0 || strlen(($aUserRecord[phone_number2])) > 0) {
                    return array(status=> true);
                }
            }
            return array(status=> false, message=>"Setup emergency contacts on Settings"); 
        }
    }
