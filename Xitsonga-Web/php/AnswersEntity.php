<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "answers"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class AnswersEntity extends DbTable{
        private $userId;
        private $answerId;
        private $questionId;
        private $answerText;
        private $correct;
        private $dateCreated;
        
        public function AnswersEntity() {
            parent::__construct(DATABASE_NAME,TABLE_ANSWERS);

            $fieldList = array("user_id","question_id","answer_id","answer_text","correct","date_created");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->questionId, $this->answerId,$this->answerText,$this->correct,$this->dateCreated);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getAnswerId() {
            return $this->answerId;
        }

        public function getQuestionId() {
            return $this->questionId;
        }

        public function getAnswerText() {
            return $this->answerText;
        }

        public function getCorrect() {
            return $this->correct;
        }

        public function getDateCreated() {
            return $this->dateCreated;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setAnswerId($answerId) {
            $this->answerId = $answerId;
            return $this;
        }

        public function setQuestionId($questionId) {
            $this->questionId = $questionId;
            return $this;
        }

        public function setAnswerText($answerText) {
            $this->answerText = $answerText;
            return $this;
        }

        public function setCorrect($correct) {
            $this->correct = $correct;
            return $this;
        }

        public function setDateCreated($dateCreated) {
            $this->dateCreated = $dateCreated;
            return $this;
        }
    }

?>
