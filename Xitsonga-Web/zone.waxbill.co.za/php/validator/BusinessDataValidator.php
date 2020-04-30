<?php
    require_once __DIR__.'/../validator/AccessValidator.php';
    /**
     * Business Data Validator
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0  
     */
    class BusinessDataValidator {
        public $accessValidator = null;
        
        public function BusinessDataValidator() {
            $this->accessValidator = new AccessValidator();
            
            $this->accessValidator->startSession();
        }
        
        public function internalUserAccess() {
            if(!$this->accessValidator->getSystemUser()->isActiveSession()){
                return array(status=> false, message=>"No session running");
            }elseif($this->accessValidator->getSystemUser()->getAccessLevel() == AccessValidator::CLIENT){
                return array(status=> false, message=>"Access to function denied");
            }
            return array(status=> true);
        }
        
        public function administratorAccess() {
            if($this->accessValidator->getSystemUser()->getAccessLevel() != AccessValidator::ADMIN){
                return array(status=> false, message=>"Access to function denied");
            }
            return array(status=> true);
        }
    }
