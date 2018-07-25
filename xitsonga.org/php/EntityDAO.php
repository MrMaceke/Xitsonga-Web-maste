<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'EntityEntity.php';
    require_once 'EntityDetailsDAO.php';
    require_once 'AuditDAO.php';
    require_once 'EntityManager.php';
    require_once 'constants.php';
    /**
     * Access and modifies entity related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class EntityDAO{
        private $aEntityManager;
        public function EntityDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        /**
         * Adds a new entity
         * 
         * @param JSON data - all user information
         * @param Strig aUserID
         * @return Array with status and message
         */
        public function addEntity($data, $aUserID) {
            $exists = $this->getEntityByName($data->name);
            
            if(!$exists['status'] OR $data->force == 1){
                $aEntity = new EntityEntity();
                $aEntityDetailsDAO = new EntityDetailsDAO();
                $date = date("Y-m-d H:i:s");
                
                $aEntity->setId(GeneralUtils::generateId());
                $aEntity->setUserId($aUserID);
                $aEntity->setName(JSONDisplay::mres($data->name));
                $aEntity->setItemTYpe($data->itemType);
                $aEntity->setDateCreated($date);

                $this->aEntityManager->setTable($aEntity);

                //$this->aEntityManager->getSql()->beginTransaction();
            
                $aResult = $this->aEntityManager->addData($aEntity->ToArray());

                if($aResult['status']){                
                    foreach ($data->details as $key => $value) {
                       if($value->content != ""){
                            $aTempResult = $aEntityDetailsDAO->addEntityDetail($value, $aEntity->getId() , $aUserID);
                            if(!$aTempResult['status']){
                                //$this->aEntityManager->getSql()->rollbackTransaction();
                                return array(status=> false, message=>"Entity ".$data->name." failed ". $aTempResult[message]);
                            }
                       }
                    };
                    $this->aEntityManager->getSql()->commitTransaction();
                    return array(status=> true, message=>$aEntity->getId());
                }
                //discard mofidication attempt data
                //$this->aEntityManager->getSql()->rollbackTransaction();
                return array(status=> false, message=>"Entity ".$data->name." failed ". $aResult[message]);
            }else{
                return array(status=> false, exists=>true,message=>"Entity ".$data->name." already exists");
            }
        }
        /**
         * 
         * @param type $param
         */
        public function getEntityByTypeCount($data) {            
            $aDTOUser = unserialize($_SESSION['USER']);
            
            $aNameQuery = new NamedQuery(NamedConstants::$COUNT_ENTITIES_BY_TYPE);
            $aNameQuery->setParameter(1, $data["entity_type"]);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"Items returned is zero");
            }else{
                return array(status=> true,itemsCount => $aCount );
            }
        }
        /**
         * 
         * @param type $param
         */
        public function getEntityBySubTypeCount($data) {                        
            $aNameQuery = new NamedQuery(NamedConstants::$COUNT_ENTITIES_BY_SUB_TYPE);
            $aNameQuery->setParameter(1, $data["entity_sub_type"]);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"Items returned is zero");
            }else{
                return array(status=> true,itemsCount => $aCount );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listEntityWithWords($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_CONTAINS_WORD);
            $aNameQuery->setParameter(1, $data["word"]." %");
            $aNameQuery->setParameter(2, "% ".$data["word"]." %");
            $aNameQuery->setParameter(3, "% ".$data["word"]);
            $aNameQuery->setParameter(4, $data["word"]);
           
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No example use of <b>$data[word]</b> found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
         /**
         * 
         * @return Array with status and message
         */
        public function listEntityByTypeAndFirstLetter($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_BY_TYPE_AND_FIRST_LETTER);
            $aNameQuery->setParameter(1, $data["entity_type"]);
            $aNameQuery->setParameter(2, $data["letter"]);
           
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active $data[entity_type] found on system");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
          /**
         * 
         * @return Array with status and message
         */
        public function listEntityByTypeSortByDate($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_BY_TYPE_ORDER_BY_DATE);
            $aNameQuery->setParameter(1, $data["entity_type"]);
           
            $aResult =  $this->aEntityManager->queryRows($aNameQuery->getQuery(), false);
            if($aResult['resultsArray'] == NULL){
                 return array(status=> false, message=>"No active new message found on system.");
            }else{
                return array(status=> true,resultsArray => $aResult['resultsArray'] );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function listEntityByType($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_BY_TYPE);
            $aNameQuery->setParameter(1, $data["entity_type"]);
            $aNameQuery->setParameterInteger(2, $data["start"]);
            $aNameQuery->setParameterInteger(3, $data["end"]);
            $aNameQuery->setParameter(4, $data["entity_type_2"]);
            $aNameQuery->setParameter(5, $data["entity_type_3"]);
            $aNameQuery->setParameter(6, $data["entity_type_4"]);
            $aNameQuery->setParameter(7, $data["entity_type_5"]);
            $aNameQuery->setParameter(8, $data["entity_type_6"]);

                        
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                    //var_dump($aNameQuery->getQuery());    
            if($aCount == 0){
                 return array(status=> false, message=>"No active $data[entity_type] found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function listEntityByTypeExport($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_BY_TYPE);
            $aNameQuery->setParameter(1, "xitsonga");
            $aNameQuery->setParameterInteger(2, 0);
            $aNameQuery->setParameterInteger(3, 10000);
            $aNameQuery->setParameter(4, "english");
            $aNameQuery->setParameter(5, "phrases");
            $aNameQuery->setParameter(6, "proverbs");
            $aNameQuery->setParameter(7, "idioms");
            $aNameQuery->setParameter(8, "riddles");

                        
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                    //var_dump($aNameQuery->getQuery());    
            if($aCount == 0){
                 return array(status=> false, message=>"No active $data[entity_type] found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        public function listEntityBySubType($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_BY_SUB_TYPE);
            $aNameQuery->setParameter(1, $data["entity_sub_type"]);
            $aNameQuery->setParameterInteger(2, $data["start"]);
            $aNameQuery->setParameterInteger(3, $data["end"]);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active $data[entity_sub_type] found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        public function listEntityContainingSubType($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIES_CONTAINING_SUB_TYPE);
            $aNameQuery->setParameter(1, $data["entity_sub_type"]);
            $aNameQuery->setParameterInteger(2, $data["start"]);
            $aNameQuery->setParameterInteger(3, $data["end"]);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active $data[entity_sub_type] found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        public function searchEntityByName($data,$exclude = FALSE) {
            $SQLQuery = NamedConstants::$SEARCH_ENTITIES_BY_NAME;
            if($exclude == TRUE){
               $SQLQuery = NamedConstants::$SEARCH_ENTITIES_BY_NAME_EXCLUDING;
               $literial = $data;
               
               $data = "%".$data."%";
            }
            
            $aNameQuery = new NamedQuery($SQLQuery);
            
            $aNameQuery->setParameter(1, $data);
            $aNameQuery->setParameter(2, "xitsonga");
            $aNameQuery->setParameter(3, "english");
            $aNameQuery->setParameter(4, "phrases");
            $aNameQuery->setParameter(5, "names");
            $aNameQuery->setParameter(6, "surnames");
            $aNameQuery->setParameter(10, $data);
            
            $aNameQuery->setParameter(11, $literial);
            $aNameQuery->setParameter(12, $literial);
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No results found for $data on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        public function findBestEntitySearchByName($data,$type,$exclude = FALSE) {
            $SQLQuery = NamedConstants::$SEARCH_ENTITIES_BY_NAME;
            if($exclude == TRUE){
               $SQLQuery = NamedConstants::$SEARCH_ENTITIES_BY_NAME_EXCLUDING;
               $literial = $data;
               
               $data = "%".$data."%";
            }
            
            $aNameQuery = new NamedQuery($SQLQuery);
            
            $aNameQuery->setParameter(1, trim($data));
            $aNameQuery->setParameter(2, "xitsonga");
            $aNameQuery->setParameter(3, "phrases");
            $aNameQuery->setParameter(4, "english");
            $aNameQuery->setParameter(5, "nothing");
            $aNameQuery->setParameter(6, "nothing");
            $aNameQuery->setParameter(10, trim($data));
            
            $aNameQuery->setParameter(11, $literial);
            $aNameQuery->setParameter(12, $literial);
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No results found for $data on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
          /**
         * 
         * @return Array with status and message
         */
        public function getEntityById($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIY_BY_ID);
            $aNameQuery->setParameter(1, $data["id"]);
            
            $aResult =  $this->aEntityManager->queryRows($aNameQuery->getQuery(), false);
            if($aResult['resultsArray'] == NULL){
                 return array(status=> false, message=>"No active entity matching criteria found on system.");
            }else{
                return array(status=> true,resultsArray => $aResult['resultsArray'] );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function getEntityByName($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ENTITIY_BY_NAME);
            $aNameQuery->setParameter(1, $data);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);

            if($aCount == 0){
                 return array(status=> false, message=>"No active entities found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
         
        /**
         * 
         * @return Array with status and message
         */
        public function listEntity($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ENTITIES);
            $aNameQuery->setParameter(1, $data["letter"]);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active entities found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @param type $param
         */
        public function listEntityByUserIdCount($aUserID) {            
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ENTITIES_BY_USER_ID_COUNT);
            $aNameQuery->setParameter(1, $aUserID);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"Items returned is zero");
            }else{
                return array(status=> true,itemsCount => $aCount );
            }
        }
        /**
         * 
         * @param type $param
         */
        public function listEntityCount() {            
            $aDTOUser = unserialize($_SESSION['USER']);
            
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ENTITIES_COUNT);

            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"Items returned is zero");
            }else{
                return array(status=> true,itemsCount => $aCount );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function editEntity($data,$user_id) {
            
            $ID[id] = $data->id;
            $aRecord = $this->getEntityById($ID);
            
            if($aRecord[status] == true){
                $previous_name = $aRecord[resultsArray][entity_name];
                $previous_type = $aRecord[resultsArray][item_type];
            }

            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_ENTITY);
            
            $aNameQuery->setParameter(1,$data->name);
            $aNameQuery->setParameter(2,$data->itemType);
            $aNameQuery->setParameter(4,$data->id);
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                if(strtolower($previous_name) != strtolower($data->name)){
                    $AUDIT["user_id"] = $user_id;
                    $AUDIT["item_id"] = $data->id;
                    $AUDIT["previous"] = $previous_name;
                    $AUDIT["change"] = $data->name;
                    
                    $aAudit = new AuditDAO();
                    $aAudit->AddAuditTrail($AUDIT);
                }
                
                if(strtolower($previous_type) != strtolower($data->itemType)){
                    $AUDIT["user_id"] = $user_id;
                    $AUDIT["item_id"] = $data->id;
                    $AUDIT["previous"] = $previous_type;
                    $AUDIT["change"] = $data->itemType;
                    
                    $aAudit = new AuditDAO();
                    $aAudit->AddAuditTrail($AUDIT);
                }
                return array(status=> true, message=> "Entity updated successfully");
            }
            return array(status=> true, message=> "Update to". " ".$data->name. " entity failed");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function removeEntity($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$REMOVE_ENTITY);
            
            $aNameQuery->setParameter(1,$data->id);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Entity removed successfully");
            }
            return array(status=> true, message=> "Remove entity failed");
        }
    }

?>
