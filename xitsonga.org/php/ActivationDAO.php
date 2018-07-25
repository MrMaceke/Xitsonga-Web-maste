<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'ActivationEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies user related information from database
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class ActivationDAO{
        private $aEntityManager;
        public function ActivationDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - user_id
         * @return Array with status and message
         */
        public function getActivationByUserID($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$FIND_ACTIVATION_RECORD_BY_USER_ID);
            $aNameQuery->setParameter(1, $data);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                 return array(status=> false,message=>"No record found");
            }else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecord );
            }
        }
        
        public function getActivationByHash($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$FIND_ACTIVATION_RECORD_BY_HASH);
            $aNameQuery->setParameter(1, $data);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                 return array(status=> false);
            }else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                return array(status=> true,resultsArray => $aRecord );
            }
        }
    }
?>
