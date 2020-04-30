<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_entity_links"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemEntityLink extends DbTable{
        private $userId;
        private $entityLinkId;
        private $mainEntity;
        private $subEntity;
        private $entityLinkType;
        private $entityLinkName;
        
        public function SystemEntityLink() {
            parent::__construct(TableNamesConstants::SYSTEM_ENTITY_LINKS);

            $fieldList = array("user_id","entity_link_id","main_entity","sub_entity","entity_link_type","entity_link_name");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->entityLinkId,$this->mainEntity, $this->subEntity, $this->entityLinkType, $this->entityLinkName);
        }
        
        function getUserId() {
            return $this->userId;
        }

        function getEntityLinkId() {
            return $this->entityLinkId;
        }

        function getMainEntity() {
            return $this->mainEntity;
        }

        function getSubEntity() {
            return $this->subEntity;
        }

        function getEntityLinkType() {
            return $this->entityLinkType;
        }

        function getEntityLinkName() {
            return $this->entityLinkName;
        }

        function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        function setEntityLinkId($entityLinkId) {
            $this->entityLinkId = $entityLinkId;
            return $this;
        }

        function setMainEntity($mainEntity) {
            $this->mainEntity = $mainEntity;
            return $this;
        }

        function setSubEntity($subEntity) {
            $this->subEntity = $subEntity;
            return $this;
        }

        function setEntityLinkType($entityLinkType) {
            $this->entityLinkType = $entityLinkType;
            return $this;
        }

        function setEntityLinkName($entityLinkName) {
            $this->entityLinkName = $entityLinkName;
            return $this;
        }
    }