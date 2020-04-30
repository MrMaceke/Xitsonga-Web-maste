<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class ActivationEntity extends DbTable{
        private $userId;
        private $activationKey;
        private $activationStatus;
        
        public function ActivationEntity() {
            parent::__construct(DATABASE_NAME,TABLE_ACTIVATIONS);

            $fieldList = array("user_id","activation_key","activation_status");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->activationKey,$this->activationStatus);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getActivationKey() {
            return $this->activationKey;
        }

        public function getActivationStatus() {
            return $this->activationStatus;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setActivationKey($activationKey) {
            $this->activationKey = $activationKey;
        }

        public function setActivationStatus($activationStatus) {
            $this->activationStatus = $activationStatus;
        }
    }

?>
