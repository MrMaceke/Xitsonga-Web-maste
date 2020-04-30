<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_groups"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class GroupEntity extends DbTable{
        private $userId;
        private $groupId;
        private $groupName;
        private $groupValue;
        private $groupDescription;
        
        public function GroupEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_GROUPS);

            $fieldList = array("group_id","user_id","group_name","group_value","group_description");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->groupId,$this->userId,$this->groupName,$this->groupValue,$this->groupDescription);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getGroupId() {
            return $this->groupId;
        }

        public function getGroupName() {
            return $this->groupName;
        }

        public function getGroupValue() {
            return $this->groupValue;
        }

        public function getGroupDescription() {
            return $this->groupDescription;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setGroupId($groupId) {
            $this->groupId = $groupId;
            return $this;
        }

        public function setGroupName($groupName) {
            $this->groupName = $groupName;
            return $this;
        }

        public function setGroupValue($groupValue) {
            $this->groupValue = $groupValue;
            return $this;
        }

        public function setGroupDescription($groupDescription) {
            $this->groupDescription = $groupDescription;
            return $this;
        } 
    }
