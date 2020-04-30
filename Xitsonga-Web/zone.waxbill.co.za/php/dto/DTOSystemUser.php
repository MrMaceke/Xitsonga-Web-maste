<?php
    /**
     * Data transfer object for system user
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class DTOSystemUser { 
        private $ACCESS_KEYS = array("Client"=>"1","Consultant"=>"2","Developer"=>"3","Administrator"=>"4");
        private $userID;
        private $userKey;
        private $email;
        private $accessLevel;
        private $roleName;
        private $isActiveSession = false;
        
        public function toString() {
            return "{Client ID: ".$this->userKey.","."Email: ".$this->email.","."Role Name: ".$this->roleName.","."Access Level: ".$this->accessLevel."}";
        }
        public function getUserID() {
            return $this->userID;
        }

        public function getUserKey() {
            return $this->userKey;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getAccessLevel() {
            return $this->accessLevel;
        }

        public function isActiveSession() {
            return $this->isActiveSession;
        }

        public function setUserID($userID) {
            $this->userID = $userID;
        }

        public function setUserKey($userKey) {
            $this->userKey = $userKey;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function setRoleName($roleName) {
            $accessLevel = $this->ACCESS_KEYS[$roleName];
            
            $this->roleName = $roleName;
            $this->accessLevel = $accessLevel;
        }

        public function setIsActiveSession($isActiveSession) {
            $this->isActiveSession = $isActiveSession;
        }
        
        public function getRoleName() {
            return $this->roleName;
        }
    }