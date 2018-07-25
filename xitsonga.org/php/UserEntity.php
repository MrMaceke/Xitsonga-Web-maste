<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "users"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class UserEntity extends DbTable{
        private $userId;
        private $password;
        private $passwordSalt;
        private $firstname;
        private $lastname;
        private $email;
        private $facebookReg;
        private $facebookID;
        public function UserEntity() {
            parent::__construct(DATABASE_NAME,TABLE_USERS);

            $fieldList = array("user_id","firstname","lastname","email","password","password_salt","facebook_reg","facebook_id");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->firstname,$this->lastname,$this->email,$this->password, $this->passwordSalt, $this->facebookReg, $this->facebookID);
        }

        public function getUserId() {
            return $this->userId;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getPasswordSalt() {
            return $this->passwordSalt;
        }
        
        
        public function getFacebookReg() {
            return $this->facebookReg;
        }

        public function getFacebookID() {
            return $this->facebookID;
        }

        public function setFacebookReg($facebookReg) {
            $this->facebookReg = $facebookReg;
        }

        public function setFacebookID($facebookID) {
            $this->facebookID = $facebookID;
        }
        
        public function getFirstname() {
            return $this->firstname;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function getEmail() {
            return $this->email;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setPassword($password) {
            $this->password = $password;
        }

        public function setPasswordSalt($passwordSalt) {
            $this->passwordSalt = $passwordSalt;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;
        }

        public function setLastname($lastname) {
            $this->lastname = $lastname;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
    }

?>
