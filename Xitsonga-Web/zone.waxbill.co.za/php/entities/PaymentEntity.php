<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "financial_payment"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class PaymentEntity extends DbTable{
        private $userId;
        private $paymentId;
        private $projectId;
        private $reference;
        private $description;
        private $amount;
        private $paymentDate;
        private $createdBy;
        
        public function PaymentEntity() {
            parent::__construct(TableNamesConstants::FINANCIAL_PAYMENT);

            $fieldList = array("user_id","project_id","payment_id","reference","description","amount","payment_date","created_by");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array($this->userId,$this->projectId, $this->paymentId,$this->reference,$this->description,$this->amount,$this->paymentDate,$this->createdBy);
        }
        
        public function getProjectId() {
            return $this->projectId;
        }

        public function setProjectId($projectId) {
            $this->projectId = $projectId;
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getPaymentId() {
            return $this->paymentId;
        }

        public function getReference() {
            return $this->reference;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getAmount() {
            return $this->amount;
        }

        public function getPaymentDate() {
            return $this->paymentDate;
        }

        public function getCreatedBy() {
            return $this->createdBy;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setPaymentId($paymentId) {
            $this->paymentId = $paymentId;
        }

        public function setReference($reference) {
            $this->reference = $reference;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setAmount($amount) {
            $this->amount = $amount;
        }

        public function setPaymentDate($paymentDate) {
            $this->paymentDate = $paymentDate;
        }

        public function setCreatedBy($createdBy) {
            $this->createdBy = $createdBy;
        }
    }
