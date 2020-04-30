<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'ItemTypeEntity.php';
    require_once 'EntityManager.php';
    require_once 'constants.php';
    /**
     * Access and modifies user related information from database
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class ItemTypeDAO{
        //Sub types
        public static $AUTHOR= "Author";
        public static $ENGLISH_TRANS= "English translation";
        public static $EXPLAINATION = "Explaination";
        public static $ANTONYMS = "Antonyms";
        public static $SYSNONYMS = "Synonyms";
        public static $HOMONYMS = "Homonyms";
        public static $WEBSITE_LINK= "Website link";
        public static $DICTIONARY_TYPE= "Dictionary type";
        public static $URL_REFERENCE= "URL Reference";
        public static $FAMILY_PRAISE = "Family praise";
        public static $IMAGE= "Image";
        public static $YOUTUBE= "Youtube";
        public static $PAST_TENSE= "Past tense";
        public static $FUTURE_TENSE= "Future tense";
        public static $PRESENT_TENSE= "Present tense";
        public static $MALE= "Male";
        public static $ORIGINAL = "Original";
        public static $FEMALE= "Female";
        public static $RATING= "Rating";
        public static $OPERATION_STATUS = "Operation status";
        public static $APP_UPDATE = "App update";
        
        private $aEntityManager;
        public function ItemTypeDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - all user information
         * @return Array with status and message
         */
        public function addItemType($data, $aUserID) {
            $aItemType = new ItemTypeEntity();
            
            $aItemType->setUserId($aUserID);
            $aItemType->setDescription($data->description);
            $aItemType->setType($data->type);
            $aItemType->setDateCreated(date("Y-m-d H:i:s"));
                    
            $this->aEntityManager->setTable($aItemType);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aItemType->ToArray());
            
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
        public function listItemTypes() {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_MAIN_ITEM_TYPES);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active types found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        
         /**
         * 
         * @return Array with status and message
         */
        public function getItemTypeByID($id) {
            $aNameQuery = new NamedQuery(NamedConstants::$GET_ITEM_TYPE_BY_ID);
            $aNameQuery->setParameter(1, $id);
            
            $aResult =  $this->aEntityManager->queryRows($aNameQuery->getQuery(), false);
            if($aResult['resultsArray'] == NULL){
                return array(status=> false, message=>"No active type matching criteria found on system.");
            }else{
                return array(status=> true,resultsArray => $aResult['resultsArray'] );
            }
        }
        
          /**
         * 
         * @return Array with status and message
         */
        public function listItemTypesType($type) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ITEM_TYPES_BY_TYPE);
            $aNameQuery->setParameterInteger(1, $type);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active types found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function listAllItemTypes() {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ITEM_TYPES);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active types found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function editType($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_ITEM_TYPE);
            
            $aNameQuery->setParameter(1,$data->name);
            $aNameQuery->setParameter(2,$data->itemType);
            $aNameQuery->setParameter(3,$data->id);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Type update successfully");
            }
            return array(status=> true, message=> "Update to". " ".$data->name. " type failed");
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function removedType($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$REMOVE_ITEM_TYPE);            
            $aNameQuery->setParameter(1,$data);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                return array(status=> true, message=> "Type removed successfully");
            }
            return array(status=> false, message=> "Removing". " ".$data->name. " type failed");
        }
    }

?>
