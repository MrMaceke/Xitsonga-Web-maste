<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_entity"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemEntityEntity extends DbTable{
        private $userId;
        private $entityId;
        private $entityType;
        private $entityName;
        
        public function SystemEntityEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_ENTITY);

            $fieldList = array("user_id","entity_id","entity_type","entity_name");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->entityId,$this->entityType, $this->entityName);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getEntityId() {
            return $this->entityId;
        }

        public function getEntityType() {
            return $this->entityType;
        }

        public function getEntityName() {
            return $this->entityName;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setEntityId($entityId) {
            $this->entityId = $entityId;
            return $this;
        }

        public function setEntityType($entityType) {
            $this->entityType = $entityType;
            return $this;
        }

        public function setEntityName($entityName) {
            $this->entityName = $entityName;
            return $this;
        }
    }