<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/SystemEntityDetailsEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Access and modifies system_entity_details related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemEntityDetailsDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemEntityDetailsDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * 
         * @param JSON data - Entity detail information
         * @see SystemEntityEntity
         * @return Array with status and message
         */
        public function addNewEntityDetail($param) {
            $this->logging->startMethod("addNewEntityDetail");
            
            $aEntityDetailResult = $this->findRecordForProperty($param->propertyId, $param->entityId);
            if($aEntityDetailResult['status']) {
                $this->logging->exitMethod("addNewEntityDetail");
                return array(status=> false, message=> ucfirst(strtolower($param->propertyName))." already exists for ".$param->entityName);
            }
            
            $aSystemEntityDetailsEntity = new SystemEntityDetailsEntity();
            $aSystemEntityDetailsEntity->setUserId($param->userId);
            $aSystemEntityDetailsEntity->setEntityId($param->entityId);
            $aSystemEntityDetailsEntity->setEntityDetailId(GeneralUtils::generateId());
            $aSystemEntityDetailsEntity->setEntityDetailType($param->propertyId);
            $aSystemEntityDetailsEntity->setEntityDetailContent($param->entityContent);
            
            $this->aEntityManager->setTable($aSystemEntityDetailsEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aSystemEntityDetailsEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $aEntityDetailResult = $this->findRecordForProperty($aSystemEntityDetailsEntity->getEntityDetailType(), $aSystemEntityDetailsEntity->getEntityId());
                if(!$aEntityDetailResult['status']) {
                    $this->logging->exitMethodWithError("addNewEntityDetail", $aEntityDetailResult['message']);
                    return array(status=> false, message=>"System failed to add new entity detail");
                }
               
                $this->logging->exitMethod("addNewEntityDetail");
                return $aEntityDetailResult;
            }
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewEntityDetail",$aResult[message]);
            return array(status=> false, message=>"System failed to add new entity detail");
        }
        /**
         * Updates entity detail
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function updateEntityDetailWithID($data) {
            $this->logging->startMethod("updateEntityDetailWithID");
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_ENTITY_DETAIL_RECORD_CONTENT);
  
            $aNameQuery->setParameter(1,trim($data->entityContent));
            $aNameQuery->setParameter(2,$data->entityDetailId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aInsertResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aInsertResult){
                $this->logging->exitMethod("updateEntityDetailWithID");
                return array(status=> true, message=> "Entity detail successfully updated");
            }
            $this->logging->exitMethod("updateEntityDetailWithID");
            return array(status=> false, message=> "System failed to update entity detail");
        }
        
        /**
         * Updates entity detail
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function updateEntityDetailWithEntityAndType($data) {
            $this->logging->startMethod("updateEntityDetailWithEntityAndType");
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_ENTITY_DETAIL_RECORD_CONTENT_BY_TYPE_AND_ENTITY_ID);
  
            $aNameQuery->setParameter(1,trim($data->entityContent));
            $aNameQuery->setParameter(2,trim($data->entityId));
            $aNameQuery->setParameter(3,trim($data->propertyId));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            $aInsertResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aInsertResult){
                $this->logging->exitMethod("updateEntityDetailWithEntityAndType");
                return array(status=> true, message=> "Entity detail successfully updated");
            }
            $this->logging->exitMethod("updateEntityDetailWithEntityAndType");
            return array(status=> false, message=> "System failed to update entity detail");
        }
        /**
         * 
         * @param JSON data - Entity detail information
         * @see SystemEntityEntity
         * @return Array with status and message
         */
        public function addOrUpdateEntityDetail($param) {
            $this->logging->startMethod("addOrUpdateEntityDetail");
            
            $aEntityDetailResult = $this->findRecordForProperty($param->propertyId, $param->entityId);
            if($aEntityDetailResult['status']) {
                $this->logging->exitMethod("addOrUpdateEntityDetail");
                return $this->updateEntityDetailWithEntityAndType($param);
            }
            
            $aSystemEntityDetailsEntity = new SystemEntityDetailsEntity();
            $aSystemEntityDetailsEntity->setUserId($param->userId);
            $aSystemEntityDetailsEntity->setEntityId($param->entityId);
            $aSystemEntityDetailsEntity->setEntityDetailId(GeneralUtils::generateId());
            $aSystemEntityDetailsEntity->setEntityDetailType($param->propertyId);
            $aSystemEntityDetailsEntity->setEntityDetailContent($param->entityContent);
            
            $this->aEntityManager->setTable($aSystemEntityDetailsEntity);
             
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aSystemEntityDetailsEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $aEntityDetailResult = $this->findRecordForProperty($aSystemEntityDetailsEntity->getEntityDetailType(), $aSystemEntityDetailsEntity->getEntityId());
                if(!$aEntityDetailResult['status']) {
                    $this->logging->exitMethodWithError("addOrUpdateEntityDetail", $aEntityDetailResult['message']);
                    return array(status=> false, message=>"System failed to add or update entity detail");
                }
               
                $this->logging->exitMethod("addOrUpdateEntityDetail");
                return $aEntityDetailResult;
            }
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addOrUpdateEntityDetail",$aResult[message]);
            return array(status=> false, message=>"System failed to add or update entity detail");
        }
        /**
         * 
         * @param Integer  detailType
         * @param Integer entityId
         * @return Array with status and message
         */
        public function findRecordForProperty($detailType, $entityId) {
            $this->logging->startMethod("findRecordForProperty");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_DETAIL_RECORD_BY_TYPE_AND_ENTITY_ID);
            $aNameQuery->setParameter(1, $entityId);
            $aNameQuery->setParameter(2, $detailType);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordForProperty");
                return array(status=> false, "No record found");
            } else {
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordForProperty");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
         * 
         * @param Integer  detailType
         * @param Integer entityId
         * @return Array with status and message
         */
        public function findRecordsForEntity($entityId) {
            $this->logging->startMethod("findRecordsForEntity");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_DETAIL_RECORDS_BY_ENTITY_ID);
            $aNameQuery->setParameter(1, $entityId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordsForEntity");
                return array(status=> false, "No record found");
            } else {
                $aRecord = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordsForEntity");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function findRecordsForEntityByEntityName($entityName) {
            $this->logging->startMethod("findRecordsForEntityByEntityName");
            
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ENTITY_DETAIL_RECORDS_BY_ENTITY_NAME);
            $aNameQuery->setParameter(1, $entityName);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordsForEntityByEntityName");
                return array(status=> false, "No record found");
            } else {
                $aRecord = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRecordsForEntityByEntityName");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
    }
