<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class ItemTypeEntity extends DbTable{
        private $userId;
        private $description;
        private $type;
        private $dateCreated;
        
        public function ItemTypeEntity() {
            parent::__construct(DATABASE_NAME,TABLE_ITEM_TYPE);

            $fieldList = array("user_id","description","date_created", "type");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->description, $this->dateCreated, $this->type);
        }
        
         public function getUserId() {
            return $this->userId;
        }

        public function getDescription() {
            return $this->description;
        }
        
        public function getDateCreated() {
            return $this->dateCreated;
        }
        
        public function getType() {
            return $this->type;
        }

        public function setType($type) {
            $this->type = $type;
        }
        
        public function setDateCreated($dateCreated) {
            $this->dateCreated = $dateCreated;
        }
        
        public function setUserId($userId) {
            $this->userId = $userId;
        }
        
        public function setDescription($description) {
            $this->description = $description;
        }
    }

?>
