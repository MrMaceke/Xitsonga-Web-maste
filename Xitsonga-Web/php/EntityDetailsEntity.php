<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class EntityDetailsEntity extends DbTable{
        
        private $userId;
        private $entityId;
        private $itemType;
        private $content;
        private $dateCreated;
        
        public function EntityDetailsEntity() {
            parent::__construct(DATABASE_NAME,TABLE_ENTITY_DETAILS);

            $fieldList = array("user_id","entity_id","item_type","content","date_created");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId, $this->entityId, $this->itemType, $this->content,$this->dateCreated);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getEntityId() {
            return $this->entityId;
        }

        public function getItemType() {
            return $this->itemType;
        }

        public function getContent() {
            return $this->content;
        }

        public function getDateCreated() {
            return $this->dateCreated;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setEntityId($entityId) {
            $this->entityId = $entityId;
        }

        public function setItemType($itemType) {
            $this->itemType = $itemType;
        }

        public function setContent($content) {
            $this->content = $content;
        }

        public function setDateCreated($dateCreated) {
            $this->dateCreated = $dateCreated;
        }
    }

?>
