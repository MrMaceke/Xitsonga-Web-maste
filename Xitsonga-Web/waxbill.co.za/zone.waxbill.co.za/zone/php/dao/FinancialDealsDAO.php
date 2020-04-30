<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../entities/DealEntity.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies financial deals related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class FinancialDealsDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function FinancialDealsDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * 
         * @param type $param
         * @return Array with status and message
         */
        public function addNewDeal($param) {
            $this->logging->startMethod("addNewDeal");
            
            $aAccessValidator = new AccessValidator();
            $aSystemUserDTO = $aAccessValidator->getSystemUser();
            
            $aDealEntity = new DealEntity();
            $aDealEntity->setUserId($aSystemUserDTO->getUserID());
            $aDealEntity->setDealCode(GeneralUtils::generateDealID());
            $aDealEntity->setDealName($param->dealName);
            $aDealEntity->setDealDescription($param->description);
            $aDealEntity->setDealPrice($param->dealPrice);
            $aDealEntity->setStartDate(date("Y-m-d h:i:s", strtotime($param->startDate. " 00:00:00")));
            $aDealEntity->setEndDate(date("Y-m-d h:i:s", strtotime($param->endDate. " 23:59:59")));
            
            $this->aEntityManager->setTable($aDealEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aDealResult = $this->aEntityManager->addData($aDealEntity->ToArray());
            if($aDealResult[status]) {
                $this->aEntityManager->getSql()->commitTransaction();
                $aDealRecordResult = $this->findRecordByDealCode($aDealEntity->getDealCode());
                if(!$aDealRecordResult['status']) {
                    $this->logging->exitMethod("addNewDeal");
                    return array(status=> false, message=>"System failed to deal");
                }
                
                $this->logging->exitMethod("addNewDeal");
                return $aDealRecordResult;
            }
            
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewDeal",$aDealResult[message]);
            return array(status=> false, message=>$aDealResult[message]);
        }
        /**
         * 
         * @param type $param
         * @return Array with status and message
         */
        public function updateDeal($param) {
            $this->logging->startMethod("updateDeal");
            
            $aResult = $this->findRecordByDealCode(trim($param->dealCode));
            if(!$aResult['status']) {
                $this->logging->exitMethod("updateDeal");
                return array(status=> false, message=>"Deal code not found in system.");
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_FINANCIAL_DEVELOPMENT_DEALS_RECORD_BY_DEAL_CODE);
  
            $aNameQuery->setParameter(1,$param->dealName);
            $aNameQuery->setParameter(2,$param->description);
            $aNameQuery->setParameter(3,$param->dealPrice);
            $aNameQuery->setParameter(4,date("Y-m-d h:i:s", strtotime($param->startDate. " 00:00:00")));
            $aNameQuery->setParameter(5,date("Y-m-d h:i:s", strtotime($param->endDate. " 00:00:00")));
            $aNameQuery->setParameter(6,$param->dealCode);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aUpdateResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aUpdateResult){
                $this->logging->exitMethod("updateDeal");
                return array(status=> true, message=> "Deal successfully updated");
            }
            
            $this->logging->exitMethodWithError("updateDeal",$aUpdateResult[message]);
            return array(status=> false, message=>$aUpdateResult[message]);
        }
        /**
         * 
         * @param type $param
         * @return Array with status and message
         */
        public function removeDeal($param) {
            $this->logging->startMethod("removeDeal");
            
            $aResult = $this->findRecordByDealCode(trim($param));
            if(!$aResult['status']) {
                $this->logging->exitMethod("removeDeal");
                return array(status=> false, message=>"Deal code not found in system.");
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::REMOVE_FINANCIAL_DEVELOPMENT_DEALS_RECORD_BY_DEAL_CODE);
            $aNameQuery->setParameter(1,$param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aRemoveResult =  $this->aEntityManager->getSql()->removeData($aNameQuery->getQuery());
            if($aRemoveResult){
                $this->logging->exitMethod("removeDeal");
                return array(status=> true, message=> "Deal successfully deleted");
            }
            
            $this->logging->exitMethodWithError("removeDeal",$aRemoveResult[message]);
            return array(status=> false, message=>$aRemoveResult[message]);
        }
        /**
        * Retrieves development deals
        * 
        * @return Array with status and message
        */
        public function retrieveDevelopmentDeals() {
            $this->logging->startMethod("retrieveDevelopmentDeals");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_DEVELOPMENT_DEALS_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveDevelopmentDeals");
                return array(status=> false, message=>"No development deals found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveDevelopmentDeals");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves development deal by deal code
        * 
        * @return Array with status and message
        */
        public function findRecordByDealCode($param) {
            $this->logging->startMethod("findRecordByDealCode");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_DEVELOPMENT_DEALS_RECORD_BY_DEAL_CODE);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByDealCode");
                return array(status=> false, message=>"No development deal found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                $this->logging->exitMethod("findRecordByDealCode");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
