<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'TranslationEntity.php';
    require_once 'EntityManager.php';
    /**
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TranslationDAO{
        private $aEntityManager;
        public function TranslationDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function AddTranslation($input,$output,$language,$build,$rating) {
            $aTranslationEntity = new TranslationEntity();
            
            $aTranslationEntity->input = strtolower($input);
            $aTranslationEntity->output = strtolower($output);
            $aTranslationEntity->language = $language;
            $aTranslationEntity->build = $build;
            $aTranslationEntity->rating = $rating;
              
            $this->aEntityManager->setTable($aTranslationEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aTranslationEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return $aResult;
            }
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
    }
?>
