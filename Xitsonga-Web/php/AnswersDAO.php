<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'AnswersEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies answers related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class AnswersDAO{
        private $aEntityManager;
        public function AnswersDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - user_id
         * @return Array with status and message
         */
        public function addNewAnswer($data,$aQuestionID,$aUserId) {
            $date = date("Y-m-d H:i:s");
            
            $aAnswerEntity = new AnswersEntity();
            
            $aAnswerEntity->setUserId($aUserId);
            $aAnswerEntity->setQuestionId($aQuestionID);
            $aAnswerEntity->setAnswerId(GeneralUtils::generateId());
            $aAnswerEntity->setDateCreated($date);
            $aAnswerEntity->setAnswerText($data->answerText); 
            $aAnswerEntity->setCorrect($data->correct);
                        
            $this->aEntityManager->setTable($aAnswerEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aAnswerEntity->ToArray());
            
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
        public function deleteAnswersByQuestionID($aQuestionID) {          
            $aNameQuery = new NamedQuery(NamedConstants::$REMOVE_ANSWERS_BY_QUESTION);
            $aNameQuery->setParameter(1, $aQuestionID);
            
            $aResult =  $this->aEntityManager->getSql()->removeData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Answers deleted successfully");
            }
            return array(status=> false, message=> $this->aEntityManager->getSql()->getMySqliError());
        }
        
         /**
         * 
         * @return Array with status and message
         */
        public function getAnswersByQuestionID($aQuestionID) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ANSWERS_BY_QUESTION_ID);
            $aNameQuery->setParameter(1, $aQuestionID);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> false, message=>"No active answers for this question found on system.");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
          /**
         * 
         * @return Array with status and message
         */
        public function getAnswersByQuestionIDCount($aQuestionID) {          
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_ANSWERS_BY_QUESTION_ID);
            $aNameQuery->setParameter(1, $aQuestionID);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
                        
            if($aCount == 0){
                 return array(status=> true, itemsCount => $aCount);
            }else{
                return array(status=> true,itemsCount => $aCount );
            }
        }
    }
?>
