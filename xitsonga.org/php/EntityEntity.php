<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class EntityEntity extends DbTable{
        private $id;
        private $userId;
        private $name;
        private $itemType;
        private $dateCreated;
        
        public function EntityEntity() {
            parent::__construct(DATABASE_NAME,TABLE_ENTITY);

            $fieldList = array("entity_id","user_id","entity_name","item_type","date_created");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->id,$this->userId, $this->name, $this->itemType, $this->dateCreated);
        }
        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function getName() {
            return $this->name;
        }

        public function getItemTYpe() {
            return $this->itemType;
        }

        public function getDateCreated() {
            return $this->dateCreated;
        }

        public function setDateCreated($dateCreated) {
            $this->dateCreated = $dateCreated;
        }
        
        public function setName($name) {
            $this->name = $name;
        }

        public function setItemTYpe($itemTYpe) {
            $this->itemType = $itemTYpe;
        }
    }

?>
