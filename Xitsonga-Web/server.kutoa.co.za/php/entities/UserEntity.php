<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "users"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class UserEntity extends DbTable{
        private $firebaseId;
        private $facebookId;
        private $phoneNumber;
        private $firstname;
        private $lastName;
        private $emailAddress;
        private $gender;
        
        public function UserEntity() {
            parent::__construct(TableNamesConstants::USERS);

            $fieldList = array("firebase_id","facebook_id","phone_number","firstname","lastname","email_address","gender");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->firebaseId),trim($this->facebookId),trim($this->phoneNumber),trim($this->firstname),trim($this->lastName),trim($this->emailAddress),trim($this->gender));
        }
        
        public function getFirebaseId() {
            return $this->firebaseId;
        }

        public function getFacebookId() {
            return $this->facebookId;
        }

        public function getPhoneNumber() {
            return $this->phoneNumber;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function getLastName() {
            return $this->lastName;
        }

        public function getEmailAddress() {
            return $this->emailAddress;
        }

        public function setFirebaseId($firebaseId) {
            $this->firebaseId = $firebaseId;
        }

        public function setFacebookId($facebookId) {
            $this->facebookId = $facebookId;
        }

        public function setPhoneNumber($phoneNumber) {
            $this->phoneNumber = $phoneNumber;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;
        }

        public function setLastName($lastName) {
            $this->lastName = $lastName;
        }

        public function setEmailAddress($emailAddress) {
            $this->emailAddress = $emailAddress;
        }
        
        public function getGender() {
            return $this->gender;
        }

        public function setGender($gender) {
            $this->gender = $gender;
        }
    }