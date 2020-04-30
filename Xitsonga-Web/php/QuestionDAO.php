<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'QuestionEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies questions related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class QuestionDAO{
        private $aEntityManager;
        public function QuestionDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - user_id
         * @return Array with status and message
         */
        public function addNewQuestion($data,$aUserId) {
            $date = date("Y-m-d H:i:s");
            
            $aQuestionEntity = new QuestionEntity();
            
            $aQuestionEntity->setUserId($aUserId);
            $aQuestionEntity->setExerciseId($data->exerciseId);
            $aQuestionEntity->setQuestionId(GeneralUtils::generateId());
            $aQuestionEntity->setDateCreated($date);
            $aQuestionEntity->setQuestionText($data->questionText); 
            $aQuestionEntity->setCorrect($data->corrent);
            
            $this->aEntityManager->setTable($aQuestionEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aQuestionEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return array(status=> true, message=> $aQuestionEntity->getQuestionId());
            }
            //discard mofidication attempt data
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
         
        /**
         * 
         * @return Array with status and message
         */
        public function editQuestion($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$EDIT_QUESTION_BY_ID);
            $aNameQuery->setParameter(1, $data->questionText);
            $aNameQuery->setParameter(2, $data->question_id);
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Question updated successfully");
            }
            return array(status=> false, message=> "Update Question failed");
        }
        /**
         * 
         * @return Array with status and message
         */
        public function removeQuestion($data) {          
            $aNameQuery = new NamedQuery(NamedConstants::$REMOVE_QUESTION_BY_ID);
            $aNameQuery->setParameter(1, $data->question_id);
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Question removed successfully");
            }
            return array(status=> false, message=> "Remove Question failed");
        }
         /**
         * 
         * @return Array with status and message
         */
        public function listQuestionsByExerciseID($aExerciseID) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_QUESTIONS_BY_EXERCISE_ID);
            $aNameQuery->setParameter(1, $aExerciseID);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active question for this exercise found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
?>
