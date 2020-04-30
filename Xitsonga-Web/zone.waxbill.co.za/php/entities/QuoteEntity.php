<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "financial_quotes"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class QuoteEntity extends DbTable{
        private $userId;
        private $projectId;
        private $quoteName;
        private $startDate;
        private $endDate;
        
        public function QuoteEntity() {
            parent::__construct(TableNamesConstants::FINANCIAL_QUOTES);

            $fieldList = array("user_id","project_id","quote_name","start_date","end_date");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->projectId,$this->quoteName,$this->startDate,$this->endDate);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getProjectId() {
            return $this->projectId;
        }

        public function getQuoteName() {
            return $this->quoteName;
        }

        public function getStartDate() {
            return $this->startDate;
        }

        public function getEndDate() {
            return $this->endDate;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setProjectId($projectId) {
            $this->projectId = $projectId;
        }

        public function setQuoteName($quoteName) {
            $this->quoteName = $quoteName;
        }

        public function setStartDate($startDate) {
            $this->startDate = $startDate;
        }

        public function setEndDate($endDate) {
            $this->endDate = $endDate;
        }
    }
