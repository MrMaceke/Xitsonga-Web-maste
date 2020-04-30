<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_activations"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class ActivationEntity extends DbTable{
        private $userId;
        private $activateKey;
        private $status;
        
        public function ActivationEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_ACTIVATIONS);

            $fieldList = array("user_id","activate_key","status");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->activateKey,$this->status);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getActivateKey() {
            return $this->activateKey;
        }

        public function getStatus() {
            return $this->status;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setActivateKey($activateKey) {
            $this->activateKey = $activateKey;
            return $this;
        }

        public function setStatus($status) {
            $this->status = $status;
            return $this;
        }
    }