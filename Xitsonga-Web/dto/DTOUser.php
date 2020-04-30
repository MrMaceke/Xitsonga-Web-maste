<?php
    /**
     * Data transfer object for user data
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class DTOUser{
        private $town;
        private $userID;
        private $isSignedIn;
        private $isAdmin;
        private $firstName;
        private $lastName;
        private $email;
        private $picture;
        private $isFacebookSignIn;
        /**
         * Constructs a DTO, both values default to FALSE
         * 
         * @param Boolean isSignedIn 
         * @param Boolean isAdmin
         */
        public function __construct($isSignedIn = false, $isAdmin=false){
            $this->isSignedIn = false;
            $this->isAdmin = false;
            $this->town =NULL;
        }
        
        public function getUserID() {
            return $this->userID;
        }

        public function setUserID($userID) {
            $this->userID = $userID;
        }
        
        public function getTown() {
            return $this->town;
        }

        public function setTown($town) {
            $this->town = $town;
        }
        
        public function isSignedIn() {
            return $this->isSignedIn;
        }

        public function isAdmin() {
            return $this->isAdmin;
        }

        public function getFirstName() {
            return $this->firstName;
        }

        public function getLastName() {
            return $this->lastName;
        }

        public function getEmail() {
            return $this->email;
        }
        
        public function getPicture() {
            return $this->picture;
        }
        
        public function isFacebookSignIn() {
            return $this->isFacebookSignIn;
        }

        public function setIsSignedIn($isSignedIn) {
            $this->isSignedIn = $isSignedIn;
        }

        public function setIsAdmin($isAdmin) {
            $this->isAdmin = $isAdmin;
        }

        public function setFirstName($firstName) {
            $this->firstName = $firstName;
        }

        public function setLastName($lastName) {
            $this->lastName = $lastName;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
        
        public function setPicture($picture) {
            $this->picture = $picture;
        }
        
        public function setIsFacebookSignIn($isFacebookSignIn) {
            $this->isFacebookSignIn = $isFacebookSignIn;
        }
        
    }

?>
