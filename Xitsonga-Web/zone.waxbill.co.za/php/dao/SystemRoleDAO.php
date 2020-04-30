<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies system_roles related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemRoleDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemRoleDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
        * Retrieves system roles
        * 
        * @return Array with status and message
        */
        public function retrieveSystemRoles() {
            $this->logging->startMethod("retrieveSystemRoles");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_ROLE_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemRoles");
                return array(status=> false, message=>"No system roles found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemRoles");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
