<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/GroupEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies system_groups related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemGroupDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemGroupDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * Adds a new system group
         * 
         * @param JSON data - group information
         * @return Array with status and message
         */
        public function addNewSystemGroup($param) {
            $this->logging->startMethod("addNewSystemGroup");
            
            $aResult = $this->findRecordByGroupName(trim($param->groupName));
            if($aResult['status']) {
                $this->logging->exitMethod("addNewSystemGroup");
                return array(status=> false, message=>"System group already exists");
            }
            
            $aAccessValidator = new AccessValidator();
            $aSystemUserDTO = $aAccessValidator->getSystemUser();
            
            $aGroupEntity = new GroupEntity();
            $aGroupEntity->setGroupId(trim(GeneralUtils::generateId()));
            $aGroupEntity->setUserId(trim($aSystemUserDTO->getUserID()));
            $aGroupEntity->setGroupName(trim($param->groupName));
            $aGroupEntity->setGroupValue(trim($param->groupValue));
            $aGroupEntity->setGroupDescription(trim($param->groupDescription));
            
            $this->aEntityManager->setTable($aGroupEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aGroupEntity->ToArray());
            if($aResult['status']) {
                $aGroupResult = $this->findRecordByGroupId($aGroupEntity->getGroupId());
                if($aGroupResult['status']) {
                    $this->aEntityManager->getSql()->commitTransaction();
                    $this->logging->exitMethod("addNewSystemGroup");
                    return $aGroupResult;
                }
                $this->aEntityManager->getSql()->rollbackTransaction();
                $this->logging->exitMethod("addNewSystemGroup");
                return array(status=> false, message=> "System failed to add system group");;
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethod("addNewSystemGroup");
            return array(status=> false, message=> "System failed to add system group");
        }
        /**
         * Updates a system group
         * 
         * @param JSON data - group information
         * @return Array with status and message
         */
        public function updateSystemGroup($param) {
            $this->logging->startMethod("updateSystemGroup");
            
            $aGroupResult = $this->findRecordByGroupName(trim($param->groupName));
            if($aGroupResult['status']) {
                $aRecord = $aGroupResult['resultsArray'];
                if($aRecord['group_id'] != $param->groupId) {
                    $this->logging->exitMethod("updateSystemGroup");
                    return array(status=> false, message=>"System group with group name already exists");
                }
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_GROUP);

            $aNameQuery->setParameter(1,trim($param->groupName));
            $aNameQuery->setParameter(2,trim($param->groupValue));
            $aNameQuery->setParameter(3,trim($param->groupDescription));
            $aNameQuery->setParameter(4,trim($param->groupId));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateSystemGroup");
                return array(status=> true, message=> "System group successfully updated");
            }
            $this->logging->exitMethod("updateSystemGroup");
            return array(status=> false, message=> "System failed to update system group");
        }
         /**
         * Deletes a new system group
         * 
         * @param JSON data - groun information
         * @return Array with status and message
         */
        public function deleteSystemGroup($param) {
            $this->logging->startMethod("deleteSystemGroup");
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_SYSTEM_GROUP);

            $aNameQuery->setParameter(1,"0");
            $aNameQuery->setParameter(2,trim($param->groupId));
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteSystemGroup");
                return array(status=> true, message=> "System group successfully deleted");
            }
            $this->logging->exitMethod("deleteSystemGroup");
            return array(status=> false, message=> "System failed to delete system group");
        }
        /**
        * Finds a record with matching group name
        * 
        * @param String group name
        * @return Array with status and message
        */
        public function findRecordByGroupName($param) {
            $this->logging->startMethod("findRecordByGroupName");

            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_GROUP_RECORD_BY_GROUP_NAME);
            $aNameQuery->setParameter(1, $param);

            $this->logging->debug("SQL =",$aNameQuery->getQuery());

            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByGroupName");
                return array(status=> false,message=>"No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);

                $this->logging->exitMethod("findRecordByGroupName");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Finds a record with matching group id
        * 
        * @param String group id
        * @return Array with status and message
        */
        public function findRecordByGroupId($param) {
            $this->logging->startMethod("findRecordByGroudId");

            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_GROUP_RECORD_BY_GROUP_ID);
            $aNameQuery->setParameter(1, $param);

            $this->logging->debug("SQL =",$aNameQuery->getQuery());

            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRecordByGroudId");
                return array(status=> false,message=>"No record found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);

                $this->logging->exitMethod("findRecordByGroudId");
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        /**
        * Retrieves system groups
        * 
        * @return Array with status and message
        */
        public function retrieveSystemGroups() {
            $this->logging->startMethod("retrieveSystemGroups");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_GROUP_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemGroups");
                return array(status=> false, message=>"No system groups found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemGroups");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
