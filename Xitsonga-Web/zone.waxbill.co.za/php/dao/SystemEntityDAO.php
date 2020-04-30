<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/SystemEntityEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Access and modifies system_entity related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemEntityDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemEntityDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * 
         * @param JSON data - Entity information
         * @see SystemEntityEntity
         * @return Array with status and message
         */
        public function addNewEntity($param) {
            $this->logging->startMethod("addNewEntity");
            
            $aUserResult = $this->findRecordWithName($param->entityName);
            if($aUserResult['status']) {
                $this->logging->exitMethod("addNewEntity");
                return array(status=> false, message=>"Entity name already exists");
            }
            
            $aSystemEntityEntity = new SystemEntityEntity();
            $aSystemEntityEntity->setUserId($param->userId);
            $aSystemEntityEntity->setEntityId(GeneralUtils::generateId());
            $aSystemEntityEntity->setEntityType($param->entityType);
            $aSystemEntityEntity->setEntityName($param->entityName);
            
            $this->aEntityManager->setTable($aSystemEntityEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aSystemEntityEntity->ToArray());
            if($aResult['status']) {
                $aEntityResult = $this->findRecordWithName($aSystemEntityEntity->getEntityName());
                if(!$aEntityResult['status']) {
                    $this->logging->exitMethodWithError("addNewEntity", $aEntityResult['message']);
                    return array(status=> false, message=>"System failed to add new entity");
                }
                $this->aEntityManager->getSql()->commitTransaction();
                $this->logging->exitMethod("addNewEntity");
                return $aEntityResult;
            }
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethod("addNewEntity",$aResult[message]);
            return array(status=> false, message=>"System failed to add new entity");
        }
        /**
        * Finds a record with entity name
        * 
        * @param String entity name
        * @return Array with status and message
        */
        public function findRecordWithName($param) {
            $this->logging->startMethod("findRecordWithName");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORD_BY_NAME);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithName");
                return array(status=> false, message=>"No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithName");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a record with entity id
        * 
        * @param String entity id
        * @return Array with status and message
        */
        public function findRecordWithID($param) {
            $this->logging->startMethod("findRecordWithID");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORD_BY_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordWithID");
                return array(status=> false, message=>"No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordWithID");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Retrieves entity by type name
        * 
        * @return Array with status and message
        */
        public function retrieveEntityWithTypeName($param) {
            $this->logging->startMethod("retrieveEntityWithTypeName");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORDS_BY_TYPE_NAME);
            $aNameQuery->setParameter(1, $param->entityTypeName);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveEntityWithTypeName");
                return array(status=> false, message=>"No entities found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveEntityWithTypeName");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves entity which is link to entity detail with specified content  
        * 
        * @return Array with status and message
        */
        public function retrieveEntityWithEntityDetailContent($param) {
            $this->logging->startMethod("retrieveEntityWithEntityDetailContent");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORDS_BY_ENTITY_DETAIL_CONTENT);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveEntityWithEntityDetailContent");
                return array(status=> false, message=>"No entities found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveEntityWithEntityDetailContent");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves entity which is link to entity detail with specified content  
        * 
        * @return Array with status and message
        */
        public function retrieveEntityByTypeGroupName($param) {
            $this->logging->startMethod("retrieveEntityByTypeGroupName");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORDS_GROUP_NAME);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveEntityByTypeGroupName");
                return array(status=> false, message=>"No entities found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveEntityByTypeGroupName");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves entity by group and client id
        * 
        * @return Array with status and message
        */
        public function retrieveEntityByTypeGroupNameAndClientId($param) {
            $this->logging->startMethod("retrieveEntityByTypeGroupNameAndClientId");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORDS_BY_GROUP_NAME_AND_CLIENT_ID);
            $aNameQuery->setParameter(1, $param->entityTypeName);
            $aNameQuery->setParameter(2, $param->clientId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveEntityByTypeGroupNameAndClientId");
                return array(status=> false, message=>"No entities found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveEntityByTypeGroupNameAndClientId");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves entity by type name and client id
        * 
        * @return Array with status and message
        */
        public function retrieveEntityWithTypeNameAndClientId($param) {
            $this->logging->startMethod("retrieveEntityWithTypeNameAndClientId");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_RECORDS_BY_TYPE_NAME_AND_CLIENT_ID);
            $aNameQuery->setParameter(1, $param->entityTypeName);
            $aNameQuery->setParameter(2, $param->clientId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveEntityWithTypeNameAndClientId");
                return array(status=> false, message=>"No entities found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveEntityWithTypeNameAndClientId");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
