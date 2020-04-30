<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "financial_quotes_details"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class QuoteDetailsEntity extends DbTable{
        private $userId;
        private $quoteName;
        private $dealCode;
        private $dealPrice;
        
        public function QuoteDetailsEntity() {
            parent::__construct(TableNamesConstants::FINANCIAL_QUOTES_DETAILS);

            $fieldList = array("quote_name","deal_code","detail_price");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->quoteName,$this->dealCode,$this->dealPrice);
        }
       
        public function getUserId() {
            return $this->userId;
        }

        public function getQuoteName() {
            return $this->quoteName;
        }

        public function getDealCode() {
            return $this->dealCode;
        }

        public function getDealPrice() {
            return $this->dealPrice;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setQuoteName($quoteName) {
            $this->quoteName = $quoteName;
        }

        public function setDealCode($dealCode) {
            $this->dealCode = $dealCode;
        }

        public function setDealPrice($dealPrice) {
            $this->dealPrice = $dealPrice;
        }
    }
