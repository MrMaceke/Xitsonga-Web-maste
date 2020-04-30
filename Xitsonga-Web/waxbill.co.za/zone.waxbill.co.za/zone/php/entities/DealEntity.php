<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "financial_development_deals"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class DealEntity extends DbTable{
        private $userId;
        private $dealCode;
        private $dealName;
        private $dealDescription;
        private $dealPrice;
        private $startDate;
        private $endDate;
        
        public function DealEntity() {
            parent::__construct(TableNamesConstants::FINANCIAL_DEALS);

            $fieldList = array("user_id","deal_code","deal_name","deal_description","deal_price","start_date","end_date");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->dealCode,$this->dealName,$this->dealDescription,$this->dealPrice,$this->startDate,$this->endDate);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getDealCode() {
            return $this->dealCode;
        }

        public function getDealName() {
            return $this->dealName;
        }

        public function getDealDescription() {
            return $this->dealDescription;
        }

        public function getDealPrice() {
            return $this->dealPrice;
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

        public function setDealCode($dealCode) {
            $this->dealCode = $dealCode;
        }

        public function setDealName($dealName) {
            $this->dealName = $dealName;
        }

        public function setDealDescription($dealDescription) {
            $this->dealDescription = $dealDescription;
        }

        public function setDealPrice($dealPrice) {
            $this->dealPrice = $dealPrice;
        }

        public function setStartDate($startDate) {
            $this->startDate = $startDate;
        }

        public function setEndDate($endDate) {
            $this->endDate = $endDate;
        }
    }
