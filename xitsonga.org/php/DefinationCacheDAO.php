<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'DefinationEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies user related information from database
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class DefinationCacheDAO{
        private $aEntityManager;
        public function DefinationCacheDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - user_id
         * @return Array with status and message
         */
        public function AddCache($item, $defination,$device) {
            $aRetults = $this->findCacheByName($item, $device);
            if($aRetults['status']){
               
            	return $aRetults;
            }
 
            $aDefinationEntity = new DefinationEntity();
            
            $aDefinationEntity->item = $item;
            $aDefinationEntity->defination= $defination;
            $aDefinationEntity->device = $device; 
              
            $this->aEntityManager->setTable($aDefinationEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addDataV2($aDefinationEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return $aResult;
            }
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        
        public function findCacheByName($item, $device) {          
            $aNameQuery = new NamedQuery(NamedConstants::$FIND_CACH_BY_NAME);
            $aNameQuery->setParameter(1, $item);
            $aNameQuery->setParameter(2, $device);
             
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No item found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
?>
