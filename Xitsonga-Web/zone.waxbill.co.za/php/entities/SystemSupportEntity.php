<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "system_support"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemSupportEntity extends DbTable{
        private $userId;
        private $supportId;
        private $projectId;
        private $supportDescription;
        private $dueDate;
        
        public function SystemSupportEntity() {
            parent::__construct(TableNamesConstants::SYSTEM_SUPPORT);

            $fieldList = array("user_id","support_id","project_id","support_description","due_date");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->supportId,$this->projectId,$this->supportDescription,$this->dueDate);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getSupportId() {
            return $this->supportId;
        }

        public function getProjectId() {
            return $this->projectId;
        }

        public function getSupportDescription() {
            return $this->supportDescription;
        }

        public function getDueDate() {
            return $this->dueDate;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setSupportId($supportId) {
            $this->supportId = $supportId;
        }

        public function setProjectId($projectId) {
            $this->projectId = $projectId;
        }

        public function setSupportDescription($supportDescription) {
            $this->supportDescription = $supportDescription;
        }

        public function setDueDate($dueDate) {
            $this->dueDate = $dueDate;
        }
    }
