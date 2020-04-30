<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_users"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class UserEntity extends DbTable{
        private $userId;
        private $userKey;
        private $email;
        private $role;
        
        public function UserEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_USERS);

            $fieldList = array("user_id","user_key","email","role_id");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->userKey,$this->email, $this->role);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getUserKey() {
            return $this->userKey;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setUserKey($userKey) {
            $this->userKey = $userKey;
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
        
        public function getRole() {
            return $this->role;
        }

        public function setRole($role) {
            $this->role = $role;
            return $this;
        }
    }
