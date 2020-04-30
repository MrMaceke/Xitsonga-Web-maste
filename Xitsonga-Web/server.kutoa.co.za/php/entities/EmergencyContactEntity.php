<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "emergency_contacts"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class EmergencyContactEntity extends DbTable{
        private $userId;
        private $phoneNumber1;
        private $phoneNumber2;
        
        public function EmergencyContactEntity() {
            parent::__construct(TableNamesConstants::EMERGENCY_CONTACTS);

            $fieldList = array("user_id","phone_number1","phone_number2");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->userId),trim($this->phoneNumber1),trim($this->phoneNumber2));
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getPhoneNumber1() {
            return $this->phoneNumber1;
        }

        public function getPhoneNumber2() {
            return $this->phoneNumber2;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setPhoneNumber1($phoneNumber1) {
            $this->phoneNumber1 = $phoneNumber1;
        }

        public function setPhoneNumber2($phoneNumber2) {
            $this->phoneNumber2 = $phoneNumber2;
        }
    }