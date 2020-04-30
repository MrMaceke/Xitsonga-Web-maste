<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'ExerciseEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies exercise related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class ExerciseDAO{
        private $aEntityManager;
        public function ExerciseDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - user_id
         * @return Array with status and message
         */
        public function addNewExercise($data,$aUserId) {
            $date = date("Y-m-d H:i:s");
            
            $aExerciseEntity = new ExerciseEntity();
            
            $aExerciseEntity->setUserId($aUserId);
            $aExerciseEntity->setExerciseId(GeneralUtils::generateId());
            $aExerciseEntity->setDateCreated($date);
            $aExerciseEntity->setExerciseTitle($data->title);
            $aExerciseEntity->setExerciseText($data->text);
                    
            $this->aEntityManager->setTable($aExerciseEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aExerciseEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return array(status=> true, message=> $aExerciseEntity->getExerciseId());
            }
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function editExercise($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$EDIT_EXERCISE_BY_ID);
            $aNameQuery->setParameter(1, $data->title);
            $aNameQuery->setParameter(2, $data->text);
            $aNameQuery->setParameter(3, $data->published);
            $aNameQuery->setParameter(4, $data->exercise_id);
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Exercise updated successfully");
            }
            return array(status=> false, message=> "Update exercise failed");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function removeExercise($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$REMOVE_EXERCISE_BY_ID);
            $aNameQuery->setParameter(1, $data->exercise_id);
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                return array(status=> true, message=> "Exercise removed successfully");
            }
            return array(status=> false, message=> "Remove exercise failed");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listExercisesByPublished($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_EXERCISES_BY_PUBLISHED);
            $aNameQuery->setParameter(1, $data["published"]);
            $aNameQuery->setParameterInteger(2, $data["start"]);
            $aNameQuery->setParameterInteger(3, $data["end"]);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No active exercise found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
         /**
         * 
         * @return Array with status and message
         */
        public function publishedExerciseCount($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$COUNT_EXERCISES_BY_PUBLISHED);
            $aNameQuery->setParameter(1, $data);       
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
        public function listExercises() {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_EXERCISES);
                        
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active exercise found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
         /**
         * 
         * @return Array with status and message
         */
        public function getExerciseByURL($aURL) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_EXERCISES_BY_URL);
            $aNameQuery->setParameter(1, $aURL);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                return array(status=> false, message=>"No active exercise with title \"$aURL\" found on system. Please Log a call to sneidon@yahoo.com to have it fixed.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
?>
