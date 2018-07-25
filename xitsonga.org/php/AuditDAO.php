<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'AuditEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies user related information from database
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class AuditDAO{
        private $aEntityManager;
        public function AuditDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - user_id
         * @return Array with status and message
         */
        public function AddAuditTrail($data) {
            $aAuditEntity = new AuditEntity();
            
            $aAuditEntity->setUserId($data[user_id]);
            $aAuditEntity->setItemId($data[item_id]);
            $aAuditEntity->setPrevious($data[previous]);
            $aAuditEntity->setChange($data[change]);
                    
            $this->aEntityManager->setTable($aAuditEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aAuditEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return $aResult;
            }
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditTrailByUser($userId) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_AUDIT_BY_USER);
            $aNameQuery->setParameter(1, $userId);
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"User has never edited an entity.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditTrail($item) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_AUDIT_FOR_ITEM);
            $aNameQuery->setParameter(1, $item);
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"This item has never been edited.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditTrailByUserIDCount($aUserID) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_AUDIT_BY_USER_ID);
            $aNameQuery->setParameter(1, $aUserID);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"Items returned is zero");
            }else{
                return array(status=> true,itemsCount => $aCount );
            }
        }
    }
?>
