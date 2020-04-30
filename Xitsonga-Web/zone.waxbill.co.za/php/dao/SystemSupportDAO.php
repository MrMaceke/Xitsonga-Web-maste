<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../entities/SystemSupportEntity.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    require_once __DIR__. '/../validator/AccessValidator.php';
    /**
     * Access and modifies system_support related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SystemSupportDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
        
        public function SystemSupportDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * Adds a new system support ticket
         * 
         * @param JSON data - group information
         * @return Array with status and message
         */
        public function addSupportTicket($param) {
            $this->logging->startMethod("addSupportTicket");
            
            $aDate = date("Y-m-d h:i:s", strtotime($param->dueDate. " 23:59:59"));
            
            $aSystemSupportEntity = new SystemSupportEntity();
            $aSystemSupportEntity->setUserId($param->clientId);
            $aSystemSupportEntity->setSupportId(GeneralUtils::generateTicketID());
            $aSystemSupportEntity->setProjectId($param->projectId);
            $aSystemSupportEntity->setSupportDescription($param->ticketDescription);
            $aSystemSupportEntity->setDueDate($aDate);
            
            $this->aEntityManager->setTable($aSystemSupportEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aSystemSupportEntity->ToArray());
            if($aResult['status']) {
                $aTicketResults = $this->retrieveSystemSupportTicket($aSystemSupportEntity->getSupportId());
                if($aTicketResults[status]){
                    $this->aEntityManager->getSql()->commitTransaction();
                    $this->logging->exitMethod("addSupportTicket");
                    return $aTicketResults;
                }
            }
            
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethod("addSupportTicket");
            return array(status=> false, message=> "System failed to add system support ticket");
        }
        /**
        * Retrieves system support tickets
        * 
        * @return Array with status and message
        */
        public function retrieveSystemSupportTickets() {
            $this->logging->startMethod("retrieveSystemSupportTickets");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_SUPPORT_RECORDS);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemSupportTickets");
                return array(status=> false, message=>"No system support ticket found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemSupportTickets");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves system support tickets yb user_id
        * 
        * @return Array with status and message
        */
        public function retrieveSystemSupportTicketsByUserId($param) {
            $this->logging->startMethod("retrieveSystemSupportTicketsByUserId");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_SUPPORT_RECORDS_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemSupportTicketsByUserId");
                return array(status=> false, message=>"No system support ticket found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemSupportTicketsByUserId");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * Retrieves system support ticket by suppord id
        * 
        * @return Array with status and message
        */
        public function retrieveSystemSupportTicket($param) {
            $this->logging->startMethod("retrieveSystemSupportTicket");
            $aNameQuery = new NamedQuery(NamedConstants::FIND_SYSTEM_SUPPORT_RECORD_BY_SUPPORT_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                $this->logging->exitMethod("retrieveSystemSupportTicket");
                return array(status=> false, message=>"No system support ticket found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                $this->logging->exitMethod("retrieveSystemSupportTicket");
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
        * updates ticket status system support ticket by suppord id
        * 
        * @return Array with status and message
        */
        public function updateSystemSupportTicketStatusByTicketId($param) {
            $this->logging->startMethod("updateSystemSupportTicketStatusByTicketId");
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_SYSTEM_SUPPORT_STATUS);
            $aNameQuery->setParameter(1, $param->status);
            $aNameQuery->setParameter(2, $param->supportId);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
                        
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateSystemSupportTicketStatusByTicketId");
                return array(status=> true, message=> "System ticket successfully updated");
            }
            $this->logging->exitMethod("updateSystemSupportTicketStatusByTicketId");
            return array(status=> false, message=> "System failed to update system ticket");
        }
    }
