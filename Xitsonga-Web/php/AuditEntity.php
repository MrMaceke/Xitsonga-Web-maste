<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class AuditEntity extends DbTable{
        private $userId;
        private $itemId;
        private $previous;
        private $change;
        
        public function AuditEntity() {
            parent::__construct(DATABASE_NAME,TABLE_AUDIT);

            $fieldList = array("user_id","item_id","previous","new_value");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->itemId,$this->previous,$this->change);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getItemId() {
            return $this->itemId;
        }

        public function getPrevious() {
            return $this->previous;
        }

        public function getChange() {
            return $this->change;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setItemId($itemId) {
            $this->itemId = $itemId;
        }

        public function setPrevious($previous) {
            $this->previous = $previous;
        }

        public function setChange($change) {
            $this->change = $change;
        }
        
    }

?>
