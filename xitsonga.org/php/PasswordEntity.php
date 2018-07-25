<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "passwords"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class PasswordEntity extends DbTable{
        private $userId;
        private $userPassword;
        private $userSalt;
        
        public function PasswordEntity() {
            parent::__construct(DATABASE_NAME,TABLE_PASSWORDS);

            $fieldList = array("user_id","user_password","user_salt");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->userPassword,$this->userSalt);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getUserPassword() {
            return $this->userPassword;
        }

        public function getUserSalt() {
            return $this->userSalt;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setUserPassword($userPassword) {
            $this->userPassword = $userPassword;
        }

        public function setUserSalt($userSalt) {
            $this->userSalt = $userSalt;
        }
    }

?>
