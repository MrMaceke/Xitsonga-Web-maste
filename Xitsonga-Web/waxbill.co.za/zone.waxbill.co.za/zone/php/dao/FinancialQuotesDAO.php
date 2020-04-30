<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/QuoteEntity.php';
    require_once __DIR__. '/../entities/QuoteDetailsEntity.php';    
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies financial_quotes related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class FinancialQuotesDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function FinancialQuotesDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        
        public function addNewQuote($param) {
            $this->logging->startMethod("addNewQuote");
            
            $aAccessValidator = new AccessValidator();
            $aSystemUserDTO = $aAccessValidator->getSystemUser();
                        
            $aQuoteEntity = new QuoteEntity();
            $aQuoteEntity->setProjectId($param->projectId);
            $aQuoteEntity->setQuoteName(GeneralUtils::generateQuoteID());
            $aQuoteEntity->setUserId($aSystemUserDTO->getUserID());
            $aQuoteEntity->setStartDate(date("Y-m-d"));
            $aQuoteEntity->setEndDate(date("Y-m-d", strtotime("+30 days")));
            
            $this->aEntityManager->setTable($aQuoteEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aQuoteResult = $this->aEntityManager->addData($aQuoteEntity->ToArray());
            if($aQuoteResult[status]) {
                foreach ($param->deals as $key => $value) {
                    $aDealCode = $value->dealCode;
                    $aDealPrice = $value->dealPrice;
                    
                    $aQuoteResultDetails = new QuoteDetailsEntity();
                    $aQuoteResultDetails->setUserId($aSystemUserDTO->getUserID());
                    $aQuoteResultDetails->setQuoteName($aQuoteEntity->getQuoteName());
                    $aQuoteResultDetails->setDealCode($aDealCode);
                    $aQuoteResultDetails->setDealPrice($aDealPrice);
                    
                    $this->aEntityManager->setTable($aQuoteResultDetails);
                    
                    $aQuoteDetailsResult = $this->aEntityManager->addData($aQuoteResultDetails->ToArray());
                    if(!$aQuoteDetailsResult[status]) {
                        $this->aEntityManager->getSql()->rollbackTransaction();
                        $this->logging->exitMethodWithError("addNewQuote",$aQuoteDetailsResult[message]);
                        return array(status=> false, message=>$aQuoteDetailsResult[message]);
                    }
                }
                
                $this->aEntityManager->getSql()->commitTransaction();
                $aFinalQuoteResult = $this->findRecordByQuoteNumber($aQuoteEntity->getQuoteName());
                if(!$aFinalQuoteResult['status']) {
                    $this->logging->exitMethod("addNewQuote");
                    return array(status=> false, message=>"System failed to quote");
                }
                
                $this->logging->exitMethod("addNewQuote");
                return $aFinalQuoteResult;
            }
            
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewQuote",$aQuoteResult[message]);
            return array(status=> false, message=>$aQuoteResult[message]);
        }
        /**
        * Retrieves quote by quote no
        * 
        * @return Array with status and message
        */
        public function findRecordByQuoteNumber($param) {
            $this->logging->startMethod("findRecordByQuoteNumber");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_QUOTE_RECORD_BY_QUOTE_NO);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByQuoteNumber");
                return array(status=> false, message=>"No quote found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                $this->logging->exitMethod("findRecordByQuoteNumber");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrievesquotes
        * 
        * @return Array with status and message
        */
        public function retrieveQuotes() {
            $this->logging->startMethod("retrieveQuotes");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_QUOTE_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveQuotes");
                return array(status=> false, message=>"No quotes found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveQuotes");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves quote by project id
        * 
        * @return Array with status and message
        */
        public function findRecordByProjectId($param) {
            $this->logging->startMethod("findRecordByProjectId");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_QUOTE_RECORD_BY_PROJECT_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByProjectId");
                return array(status=> false, message=>"No quote found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                $this->logging->exitMethod("findRecordByProjectId");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves quote details by quote no
        * 
        * @return Array with status and message
        */
        public function findQuoteDetailsByQuoteNumber($param) {
            $this->logging->startMethod("findQuoteDetailsByQuoteNumber");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_FINANCIAL_QUOTE_DETAIL_RECORDS_BY_QUOTE_NO);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("findQuoteDetailsByQuoteNumber");
                return array(status=> false, message=>"No quote found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("findQuoteDetailsByQuoteNumber");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * Updates quote porject id
         * 
         * @param JSON data - property information
         * @return Array with status and message
         */
        public function updateQuoteProjectId($param) {
            $this->logging->startMethod("updateQuoteProjectId");
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_FINANCIAL_QUOTE_PROJECT_ID_FOR_QUOTE_NUMBER);

            $aNameQuery->setParameter(1,$param->projectId);
            $aNameQuery->setParameter(2,$param->quoteNumber);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateQuoteProjectId");
                return array(status=> true, message=> "Quote successfully updated");
            }
            $this->logging->exitMethod("updateQuoteProjectId");
            return array(status=> false, message=> "System failed to update quote");
        }
        /**
         * Deletes a new system property
         * 
         * @param JSON data - property information
         * @return Array with status and message
         */
        public function deleteQuoteForProject($param) {
            $this->logging->startMethod("deleteQuoteForProject");
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_FINANCIAL_QUOTES_FOR_PROJECT);

            $aNameQuery->setParameter(1,"0");
            $aNameQuery->setParameter(2,trim($param));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteQuoteForProject");
                return array(status=> true, message=> "Quote successfully deleted");
            }
            $this->logging->exitMethod("deleteQuoteForProject");
            return array(status=> false, message=> "System failed to delete quote");
        }
    }
