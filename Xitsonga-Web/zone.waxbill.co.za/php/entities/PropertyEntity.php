<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_properties"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class PropertyEntity extends DbTable{
        private $userId;
        private $propertyId;
        private $groupId;
        private $propertyName;
        private $propertyValue;
        private $propertyDescription;
        
        public function PropertyEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_PROPERTIES);

            $fieldList = array("property_id","group_id","user_id","property_name","property_value","property_description");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->propertyId,$this->groupId,$this->userId,$this->propertyName,$this->propertyValue,$this->propertyDescription);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getPropertyId() {
            return $this->propertyId;
        }

        public function getGroupId() {
            return $this->groupId;
        }

        public function getPropertyName() {
            return $this->propertyName;
        }

        public function getPropertyValue() {
            return $this->propertyValue;
        }

        public function getPropertyDescription() {
            return $this->propertyDescription;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setPropertyId($propertyId) {
            $this->propertyId = $propertyId;
            return $this;
        }

        public function setGroupId($groupId) {
            $this->groupId = $groupId;
            return $this;
        }

        public function setPropertyName($propertyName) {
            $this->propertyName = $propertyName;
            return $this;
        }

        public function setPropertyValue($propertyValue) {
            $this->propertyValue = $propertyValue;
            return $this;
        }

        public function setPropertyDescription($propertyDescription) {
            $this->propertyDescription = $propertyDescription;
            return $this;
        }
    }