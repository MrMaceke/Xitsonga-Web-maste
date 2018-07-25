<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'TranslationConfigEntity.php';
    require_once 'TranslationConfigDetailsEntity.php';
    require_once 'EntityManager.php';
    /**
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TranslationConfigDAO{
        private $aEntityManager;
        public function TranslationConfigDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function AddTranslationConfig($data,$user) {
            $aTranslationConfigEntity = new TranslationConfigEntity();
            
            $aTranslationConfigEntity->configId = GeneralUtils::generateId();
            $aTranslationConfigEntity->item = strtolower($data->item);
            $aTranslationConfigEntity->replacement = strtolower($data->replacement);
            $aTranslationConfigEntity->language = $data->language;
            $aTranslationConfigEntity->user = $user;
              
            $this->aEntityManager->setTable($aTranslationConfigEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addDataV2($aTranslationConfigEntity->ToArray());
            
            if($aResult['status']){
                $aTranslationConfigDetailsEntity = new TranslationConfigDetailsEntity();
                $aTranslationConfigDetailsEntity->configId = $aTranslationConfigEntity->configId;
                $aTranslationConfigDetailsEntity->item = strtolower($aTranslationConfigEntity->item);
                $aTranslationConfigDetailsEntity->pattern  = strtolower($data->pattern);
                $aTranslationConfigDetailsEntity->swapLeft = $data->swapLeft;
                $aTranslationConfigDetailsEntity->swapRight = $data->swapRight;
                $aTranslationConfigDetailsEntity->pushFirst = $data->pushFirst;
                $aTranslationConfigDetailsEntity->pushLast = $data->pushLast;
                
                $this->aEntityManager->setTable($aTranslationConfigDetailsEntity);
                        
                $this->aEntityManager->getSql()->beginTransaction();

                $aResult = $this->aEntityManager->addDataV2($aTranslationConfigDetailsEntity->ToArray());
                if($aResult['status']) {
                    $this->aEntityManager->getSql()->commitTransaction();
                
                    return $aResult;
                }
            } 
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function listTranslationConfigs() {          
            $aNameQuery = new NamedQuery(""
                    . "select * from translations_config as main"
                    . " inner join translations_config_details as detail on detail.config_id = main.config_id"
                    . " where main.record_status = 1 order by main.date_created asc"
                    );
             
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active configs found.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function getTranslationConfigs($language) {          
            $aNameQuery = new NamedQuery(""
                    . "select * from translations_config as main"
                    . " inner join translations_config_details as detail on detail.config_id = main.config_id"
                    . " where main.record_status = 1 and main.language ='$language' order by length(main.item) asc"
                    );
             
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active configs found.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
?>
