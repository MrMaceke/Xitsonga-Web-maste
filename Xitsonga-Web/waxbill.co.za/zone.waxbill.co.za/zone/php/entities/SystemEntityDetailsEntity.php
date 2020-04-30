<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_entity_details"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemEntityDetailsEntity extends DbTable{
        private $userId;
        private $entityId;
        private $entityDetailId;
        private $entityDetailType;
        private $entityDetailContent;
        
        public function SystemEntityDetailsEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_ENTITY_DETAILS);

            $fieldList = array("user_id","entity_id","entity_detail_id","entity_detail_type","entity_detail_content");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->entityId,$this->entityDetailId,$this->entityDetailType,$this->entityDetailContent);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getEntityId() {
            return $this->entityId;
        }

        public function getEntityDetailId() {
            return $this->entityDetailId;
        }

        public function getEntityDetailType() {
            return $this->entityDetailType;
        }

        public function getEntityDetailContent() {
            return $this->entityDetailContent;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setEntityId($entityId) {
            $this->entityId = $entityId;
            return $this;
        }

        public function setEntityDetailId($entityDetailId) {
            $this->entityDetailId = $entityDetailId;
            return $this;
        }

        public function setEntityDetailType($entityDetailType) {
            $this->entityDetailType = $entityDetailType;
            return $this;
        }

        public function setEntityDetailContent($entityDetailContent) {
            $this->entityDetailContent = $entityDetailContent;
            return $this;
        }
    }