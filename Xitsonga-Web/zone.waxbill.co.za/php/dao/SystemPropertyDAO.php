<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/PropertyEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies system_properties related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemPropertyDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemPropertyDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * Adds a new system property
         * 
         * @param JSON data - property information
         * @return Array with status and message
         */
        public function addNewSystemProperty($param) {
            $this->logging->startMethod("addNewSystemProperty");
            
            $aResult = $this->findRecordByPropertyName(trim($param->propertyName));
            if($aResult['status']) {
                $this->logging->exitMethod("addNewSystemProperty");
                return array(status=> false, message=>"System property already exists");
            }
            
            $aAccessValidator = new AccessValidator();
            $aSystemUserDTO = $aAccessValidator->getSystemUser();
            
            $aPropertyEntity = new PropertyEntity();
            $aPropertyEntity->setPropertyId(trim(GeneralUtils::generateId()));
            $aPropertyEntity->setUserId(trim($aSystemUserDTO->getUserID()));
            $aPropertyEntity->setGroupId(trim($param->propertyGroup));
            $aPropertyEntity->setPropertyName(trim($param->propertyName));
            $aPropertyEntity->setPropertyValue(trim($param->propertyValue));
            $aPropertyEntity->setPropertyDescription(trim($param->propertyDescription));
            
            $this->aEntityManager->setTable($aPropertyEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aInsertResult = $this->aEntityManager->addData($aPropertyEntity->ToArray());
            if($aInsertResult['status']) {
                $aPropertyResult = $this->findRecordByPropertyId($aPropertyEntity->getPropertyId());
                if($aPropertyResult['status']) {
                    $this->aEntityManager->getSql()->commitTransaction();
                    $this->logging->exitMethod("addNewSystemProperty");
                    return $aPropertyResult;
                }
                $this->aEntityManager->getSql()->rollbackTransaction();
                $this->logging->exitMethod("addNewSystemProperty");
                return array(status=> false, message=> "System failed to add system property");
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethod("addNewSystemProperty");
            return array(status=> false, message=> "System failed to add system property");
        }
         /**
         * Updates a system property description
         * 
         * @param JSON data - property information
         * @return Array with status and message
         */
        public function updateSystemPropertyDescription($param){
            $this->logging->startMethod("updateSystemPropertyDescription");
            
            $aGroupResult = $this->findRecordByPropertyId(trim($param->propertyId));
            if(!$aGroupResult['status']) {
                $this->logging->exitMethod("updateSystemProperty");
                
                return array(status=> false, message=>"System property not found in system");
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_PROPERTY_DESCRIPTION);

            $aNameQuery->setParameter(1,trim($param->propertyDescription));
            $aNameQuery->setParameter(2,trim($param->propertyId));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateSystemPropertyDescription");
                return array(status=> true, message=> "System property successfully updated");
            }
            $this->logging->exitMethod("updateSystemPropertyDescription");
            return array(status=> false, message=> "System failed to update system property");
        }
        /**
         * Updates a system property
         * 
         * @param JSON data - property information
         * @return Array with status and message
         */
        public function updateSystemProperty($param) {
            $this->logging->startMethod("updateSystemProperty");
            
            $aPropertyResult = $this->findRecordByPropertyName(trim($param->propertyName));
            if($aPropertyResult['status']) {
                $aRecord = $aPropertyResult['resultsArray'];
                if($aRecord['property_id'] != $param->propertyId) {
                    $this->logging->exitMethod("updateSystemProperty");
                    return array(status=> false, message=>"System property with property name already exists");
                }
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_PROPERTY);

            $aNameQuery->setParameter(1,trim($param->propertyName));
            $aNameQuery->setParameter(2,trim($param->propertyValue));
            $aNameQuery->setParameter(3,trim($param->propertyDescription));
            $aNameQuery->setParameter(4,trim($param->groupId));
            $aNameQuery->setParameter(5,trim($param->propertyId));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateSystemProperty");
                return array(status=> true, message=> "System property successfully updated");
            }
            $this->logging->exitMethod("updateSystemProperty");
            return array(status=> false, message=> "System failed to update system property");
        }
         /**
         * Deletes a new system property
         * 
         * @param JSON data - property information
         * @return Array with status and message
         */
        public function deleteSystemProperty($param) {
            $this->logging->startMethod("deleteSystemProperty");
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_SYSTEM_PROPERTY);

            $aNameQuery->setParameter(1,"0");
            $aNameQuery->setParameter(2,trim($param->propertyId));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteSystemProperty");
                return array(status=> true, message=> "System property successfully deleted");
            }
            $this->logging->exitMethod("deleteSystemProperty");
            return array(status=> false, message=> "System failed to delete system property");
        }
        /**
        * Finds a record with matching property name
        * 
        * @param String property name
        * @return Array with status and message
        */
        public function findRecordByPropertyName($param) {
            $this->logging->startMethod("findRecordByPropertyName");

            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PROPERTY_RECORD_BY_PROPERTY_NAME);
            $aNameQuery->setParameter(1, $param);

            $this->logging->debug("SQL =",$aNameQuery->getQuery());

            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByPropertyName");
                return array(status=> false,message=>"No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);

                $this->logging->exitMethod("findRecordByPropertyName");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a record with matching property id
        * 
        * @param String property id
        * @return Array with status and message
        */
        public function findRecordByPropertyId($param) {
            $this->logging->startMethod("findRecordByPropertyId");

            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PROPERTY_RECORD_BY_PROPERTY_ID);
            $aNameQuery->setParameter(1, $param);

            $this->logging->debug("SQL =",$aNameQuery->getQuery());

            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByPropertyId");
                return array(status=> false,message=>"No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);

                $this->logging->exitMethod("findRecordByPropertyId");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Retrieves system properties
        * 
        * @return Array with status and message
        */
        public function retrieveSystemProperties() {
            $this->logging->startMethod("retrieveSystemProperties");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PROPERTY_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemProperties");
                return array(status=> false, message=>"No system properties found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemProperties");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        /**
        * Retrieves system properties by group name
        * 
        * @param String param - groupName
        * @return Array with status and message
        */
        public function retrieveSystemPropertiesByGroupName($param) {
            $this->logging->startMethod("retrieveSystemPropertiesByGroupName");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_PROPERTY_RECORDS_BY_GROUP_NAME);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemPropertiesByGroupName");
                return array(status=> false, message=>"No system properties found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemPropertiesByGroupName");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
