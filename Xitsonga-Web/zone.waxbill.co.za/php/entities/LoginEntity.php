<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_logins"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class LoginEntity extends DbTable{
        private $userId;
        private $specialMessage;
        
        public function LoginEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_LOGINS);

            $fieldList = array("user_id","special_message");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->specialMessage);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getSpecialMessage() {
            return $this->specialMessage;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setSpecialMessage($specialMessage) {
            $this->specialMessage = $specialMessage;
        }
    }