<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_passwords"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class PasswordEntity extends DbTable{
        private $userId;
        private $password;
        private $salt;
        
        public function PasswordEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_PASSWORDS);

            $fieldList = array("user_id","password","salt");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->password,$this->salt);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getSalt() {
            return $this->salt;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setPassword($password) {
            $this->password = $password;
            return $this;
        }

        public function setSalt($salt) {
            $this->salt = $salt;
            return $this;
        }
    }