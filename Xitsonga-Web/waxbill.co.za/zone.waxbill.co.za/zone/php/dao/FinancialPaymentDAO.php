<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../entities/PaymentEntity.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies financial payment related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class FinancialPaymentDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function FinancialPaymentDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * 
         * @param type $param
         * @return Array with status and message
        */
        public function addNewPayment($param) {
            $this->logging->startMethod("addNewPayment");
            
            $aDate = date("Y-m-d h:i:s", strtotime($param->paymentDate. " 23:59:59"));
            
            $aAccessValidator = new AccessValidator();
            $aSystemUserDTO = $aAccessValidator->getSystemUser(); 
            
            $aPaymentEntity = new PaymentEntity();
            $aPaymentEntity->setUserId($param->userId);
            $aPaymentEntity->setCreatedBy($aSystemUserDTO->getUserID());
            $aPaymentEntity->setPaymentId(GeneralUtils::generatePaymentID());
            $aPaymentEntity->setProjectId($param->projectId);
            $aPaymentEntity->setReference($param->paymentReference);
            $aPaymentEntity->setAmount($param->paymentAmount);
            $aPaymentEntity->setDescription($param->paymentDescription);
            $aPaymentEntity->setPaymentDate($aDate);
            
            $this->aEntityManager->setTable($aPaymentEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aPaymentResult = $this->aEntityManager->addData($aPaymentEntity->ToArray());
            if($aPaymentResult[status]) {
                $this->aEntityManager->getSql()->commitTransaction();
                $aPaymentRecordResult = $this->findRecordByPaymentCode($aPaymentEntity->getPaymentId());
                if(!$aPaymentRecordResult['status']) {
                    $this->logging->exitMethod("addNewPayment");
                    return array(status=> false, message=>"System failed tto add payment");
                }
                $this->logging->exitMethod("addNewPayment");
                return $aPaymentRecordResult;
            }
            
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewPayment",$aPaymentResult[message]);
            return array(status=> false, message=>$aPaymentResult[message]);
        }
        /**
        * Retrieves payments
        * 
        * @return Array with status and message
        */
        public function retrievePayments() {
            $this->logging->startMethod("retrievePayments");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_PAYMENTS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrievePayments");
                return array(status=> false, message=>"No payment found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrievePayments");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves payments
        * 
        * @return Array with status and message
        */
        public function retrievePaymentsByUserId($param) {
            $this->logging->startMethod("retrievePaymentsByUserId");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_PAYMENTS_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrievePaymentsByUserId");
                return array(status=> false, message=>"No payment found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrievePaymentsByUserId");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves payments by project id
        * 
        * @return Array with status and message
        */
        public function retrievePaymentsByProjectId($param) {
            $this->logging->startMethod("retrievePaymentsByProjectId");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_PAYMENTS_PROJECT_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrievePaymentsByProjectId");
                return array(status=> false, message=>"No payment found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrievePaymentsByProjectId");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves payments by payment code
        * 
        * @return Array with status and message
        */
        public function findRecordByPaymentCode($param) {
            $this->logging->startMethod("findRecordByPaymentCode");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_PAY_RECORD_BY_PAYMENT_CODE);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByPaymentCode");
                return array(status=> false, message=>"No payment found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                $this->logging->exitMethod("findRecordByPaymentCode");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
