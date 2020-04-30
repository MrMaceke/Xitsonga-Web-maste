<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/SystemEntityLink.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Access and modifies system_entity_links related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemEntityLinkDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemEntityLinkDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * 
         * @param JSON data - Entity information
         * @see SystemEntityLink
         * @return Array with status and message
         */
        public function addNewEntityLink($param) {
            $this->logging->startMethod("addNewEntityLink");
            
            $aUserResult = $this->findRecordWithName($param->entityLinkName);
            if($aUserResult['status']) {
                $this->logging->exitMethod("addNewEntityLink");
                return array(status=> false, message=>"Entity link name already exists");
            }
            
            $aSystemEntityLink = new SystemEntityLink();
            $aSystemEntityLink->setUserId($param->userId);
            $aSystemEntityLink->setEntityLinkId(GeneralUtils::generateId());
            $aSystemEntityLink->setMainEntity($param->mainEntity);
            $aSystemEntityLink->setSubEntity($param->subEntity);
            $aSystemEntityLink->setEntityLinkType($param->entityLinkType);
            $aSystemEntityLink->setEntityLinkName($param->entityLinkName);
            
            $this->aEntityManager->setTable($aSystemEntityLink);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aSystemEntityLink->ToArray());
            if($aResult['status']) {
                $aEntityResult = $this->findRecordWithName($aSystemEntityLink->getEntityLinkName());
                if(!$aEntityResult['status']) {
                    $this->logging->exitMethodWithError("addNewEntityLink", $aEntityResult['message']);
                    return array(status=> false, message=>"System failed to add new entity link");
                }
                $this->aEntityManager->getSql()->commitTransaction();
                $this->logging->exitMethod("addNewEntityLink");
                return $aEntityResult;
            }
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewEntityLink", $aResult['message']);
            return array(status=> false, message=>"System failed to add new entity link");
        }
        /**
        * Finds a record with entity link name
        * 
        * @param String entity link name
        * @return Array with status and message
        */
        public function findRecordWithName($param) {
            $this->logging->startMethod("findRecordWithName");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_LINK_RECORD_BY_NAME);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithName");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithName");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a record with entity link sub type
        * 
        * @param String entity link name
        * @return Array with status and message
        */
        public function findRecordWithSubEntity($param) {
            $this->logging->startMethod("findRecordWithSubEntity");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_LINK_RECORD_BY_SUB_ENTITY);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithSubEntity");
                return array(status=> false, "No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithSubEntity");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a records by  main entity and link type name
        * 
        * @param String entity link name
        * @return Array with status and message
        */
        public function findRecordsMultipleByMainEntitiesAndLinkType($aMainEntityArray, $aLinkTypeName) {
            $this->logging->startMethod("findRecordsMultipleByMainEntitiesAndLinkType");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_MAIN_ENTITIES_AND_LINK_TYPE);
            $aNameQuery->setParameterArray(1, $aMainEntityArray);
            $aNameQuery->setParameter(2, $aLinkTypeName);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordsMultipleByMainEntitiesAndLinkType");
                return array(status=> false, "No records found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordsMultipleByMainEntitiesAndLinkType");
                return array(status=> true,resultsArray => $aRecord);
            }
        }
        /**
        * Finds a records by  main entity and link type name
        * 
        * @param String entity link name
        * @return Array with status and message
        */
        public function findRecordsByMainEntityAndLinkType($param) {
            $this->logging->startMethod("findRecordsByMainEntityAndLinkType");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_MAIN_ENTITY_AND_LINK_TYPE);
            $aNameQuery->setParameter(1, $param->mainEntity);
            $aNameQuery->setParameter(2, $param->linkTypeName);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordsByMainEntityAndLinkType");
                return array(status=> false, "No records found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordsByMainEntityAndLinkType");
                return array(status=> true,resultsArray => $aRecord);
            }
        }
        /**
        * Finds a records by sub entity and link type name
        * 
        * @param String entity link name
        * @return Array with status and message
        */
        public function findRecordsBySubEntityAndLinkType($param) {
            $this->logging->startMethod("findRecordsBySubEntityAndLinkType");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_SUB_ENTITY_AND_LINK_TYPE);
            $aNameQuery->setParameter(1, $param->subEntity);
            $aNameQuery->setParameter(2, $param->linkTypeName);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordsBySubEntityAndLinkType");
                return array(status=> false, "No records found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordsBySubEntityAndLinkType");
                return array(status=> true,resultsArray => $aRecord);
            }
        }
        /**
        * Finds a records by  main entity and link type group name
        * 
        * @param String entity link name
        * @return Array with status and message
        */
        public function findRecordsByMainEntityAndLinkTypeGroupName($param) {
            $this->logging->startMethod("findRecordsByMainEntityAndLinkTypeGroupName");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_MAIN_ENTITY_AND_LINK_TYPE_GROUP_NAME);
            $aNameQuery->setParameter(1, $param->mainEntity);
            $aNameQuery->setParameter(2, $param->typeGroupName);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordsByMainEntityAndLinkTypeGroupName");
                return array(status=> false, "No records found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordsByMainEntityAndLinkTypeGroupName");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
