<?php
    require_once 'GeneralUtils.php';
    require_once 'JSONDisplay.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'EntityDetailsEntity.php';
    require_once 'EntityManager.php';
    require_once 'constants.php';
    /**
     * Access and modifies entity detail related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class EntityDetailsDAO{
        private $aEntityManager;
        public function EntityDetailsDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        /**
         * 
         * @param JSON data - all user information
         * @return Array with status and message
         */
        public function addEntityDetail($data,$entityId, $aUserID) {
            $aEntityDetail = new EntityDetailsEntity();
            
            $aItemType = str_replace("_"," ", $data->itemType);
                    
            $aNameQuery = new NamedQuery(NamedConstants::$GET_ITEM_TYPE_BY_DESCRIPTION);
            $aNameQuery->setParameter(1, $aItemType);
            
            $aResult =  $this->aEntityManager->queryRows($aNameQuery->getQuery(), false);
            
            $aItemTypeId = $aResult['resultsArray']['item_type'];
            
            $aEntityDetail->setEntityId($entityId);
            $aEntityDetail->setUserId($aUserID);
            $aEntityDetail->setContent(JSONDisplay::mres($data->content));
            $aEntityDetail->setItemType($aItemTypeId);
            $aEntityDetail->setDateCreated(date("Y-m-d H:i:s"));

            $this->aEntityManager->setTable($aEntityDetail);
                                    
            $aResult = $this->aEntityManager->addData($aEntityDetail->ToArray());
            
            if($aResult['status']){
                return $aResult;
            }
            return $aResult;
        }
        /**
         * 
         * @param JSON data - all user information
         * @return Array with status and message
         */
        public function addEntityImageDetail($aImageName,$entityId, $aUserID) {
            
            $aExists = $this->getEntityDetailsByEntityIdAndType($entityId);
            if(!$aExists['status']){
                $aEntityDetail = new EntityDetailsEntity();

                $aItemType = "Image";

                $aNameQuery = new NamedQuery(NamedConstants::$GET_ITEM_TYPE_BY_DESCRIPTION);
                $aNameQuery->setParameter(1, $aItemType);
                $aResult =  $this->aEntityManager->queryRows($aNameQuery->getQuery(), false);

                $aItemTypeId = $aResult['resultsArray']['item_type'];

                $aEntityDetail->setEntityId($entityId);
                $aEntityDetail->setUserId($aUserID);
                $aEntityDetail->setContent($aImageName);
                $aEntityDetail->setItemType($aItemTypeId);
                $aEntityDetail->setDateCreated(date("Y-m-d H:i:s"));

                $this->aEntityManager->setTable($aEntityDetail);

                $this->aEntityManager->getSql()->beginTransaction();

                $aResult = $this->aEntityManager->addData($aEntityDetail->ToArray());

                if($aResult['status']){
                    $this->aEntityManager->getSql()->commitTransaction();
                    return $aResult;
                }
                //discard mofidication attempt data
                $this->aEntityManager->getSql()->rollbackTransaction();
                return $aResult;
            }else{
                
                $aResult = $this->editEntityImageDetail($aExists[resultsArray][entity_details_id],$aImageName,$aUserID);
                
                return $aResult;
            }
        }
        /** 
         * @return Array with status and message
         */
        public function getEntityDetailsById($aAntity) {
            $aNameQuery = new NamedQuery(NamedConstants::$GET_ENTITY_DETAIL_BY_ID);
            $aNameQuery->setParameter(1, $aAntity);
          
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                return array(status=> false, message=>"No active entity details found on system.");
            }else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecord );
            }
        }        
        /**
         * 
         * @return Array with status and message
         */
        public function getEntityDetailsByEntityId($aAntity) {
        
            $aNameQuery = new NamedQuery(NamedConstants::$GET_ENTITY_DETAIL_BY_ENTITY_ID);
            $aNameQuery->setParameter(1, $aAntity);

            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);

                    
            if($aCount == 0){
                return array(status=> false, message=>"No active entity details found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                  
                return array(status=> true,resultsArray => $aRecords);
            }
        }
         /**
         * 
         * @return Array with status and message
         */
        public function getEntityDetailsByEntityIdCount($aAntity) {
            $aNameQuery = new NamedQuery(NamedConstants::$GET_ENTITY_DETAIL_BY_ENTITY_ID);
            $aNameQuery->setParameter(1, $aAntity);
          
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            return $aCount;
        }
        /**
         * 
         * @return Array with status and message
         */
        public function getEntityDetailsByEntityIdAndType($id,$type = "Image") {
            $aNameQuery = new NamedQuery(NamedConstants::$GET_ENTITY_DETAIL_BY_ENTITY_ID_AND_TYPE);
            $aNameQuery->setParameter(1, $id);
            $aNameQuery->setParameter(2, $type);

            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery(), false);
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active entity details found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function editEntityDetail($data,$user_id) {
            
            $aRecord = $this->getEntityDetailsById($data->id);

            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_ENTITY_DETAIL);
            
            $aNameQuery->setParameter(1,  JSONDisplay::mres($data->content));
            $aNameQuery->setParameter(2,$data->id);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                if($aRecord[status] == true){
                    $previous_value = $aRecord[resultsArray][content];
                    
                    if(strtolower(trim($previous_value)) != strtolower(trim($data->content))){
                        $AUDIT["user_id"] = $user_id;
                        $AUDIT["item_id"] = $aRecord[resultsArray][entity_id];
                        $AUDIT["previous"] = $previous_value;
                        $AUDIT["change"] = $data->content;
                        $aAudit = new AuditDAO();
                        $aAudit->AddAuditTrail($AUDIT);
                    }
                }               

                return array(status=> true, message=> "Entity detail updated successfully");
            }
            return array(status=> true, message=> "Update to". " ".$data->content. " entity failed");
        } 
        /**
         * 
         * @return Array with status and message
         */
        public function removeEntityDetail($data) {
            $aNameQuery = new NamedQuery(NamedConstants::$REMOVE_ENTITY_DETAIL);
            
            $aNameQuery->setParameter(1,$data->id);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Entity detail removed successfully");
            }
            return array(status=> true, message=> "Remove entity failed");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function editEntityImageDetail($id, $image,$user_id) {
            $aNameQuery = new NamedQuery(NamedConstants::$UPDATE_ENTITY_DETAIL);
            
            $aNameQuery->setParameter(1,$image);
            $aNameQuery->setParameter(2,$id);

            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                $aRecord = $this->getEntityDetailsById($data->id);
                if($aRecord[status] == true){
                    $previous_value = $aRecord[resultsArray][content];
                    
                    if($previous_value != $data->content){
                        $AUDIT["user_id"] = $user_id;
                        $AUDIT["item_id"] = $data->id;
                        $AUDIT["previous"] = $previous_value;
                        $AUDIT["change"] = $data->content;
                        $aAudit = new AuditDAO();
                        $aAudit->AddAuditTrail($AUDIT);
                    }
                }        
                return array(status=> true, message=> "Entity detail updated successfully");
            }
            return array(status=> true, message=> "Update to". " ".$data->content. " entity failed");
        }
    }
?>
