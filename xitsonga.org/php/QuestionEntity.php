<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "exercises"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class QuestionEntity extends DbTable{
        private $userId;
        private $exerciseId;
        private $questionId;
        private $questionText;
        private $correct;
        private $dateCreated;
        
        public function QuestionEntity() {
            parent::__construct(DATABASE_NAME,TABLE_QUESTIONS);

            $fieldList = array("user_id","exercise_id","question_id","question_text","correct","date_created");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->exerciseId,$this->questionId,$this->questionText,$this->correct,$this->dateCreated);
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getExerciseId() {
            return $this->exerciseId;
        }

        public function getQuestionId() {
            return $this->questionId;
        }

        public function getQuestionText() {
            return $this->questionText;
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

        public function setExerciseId($exerciseId) {
            $this->exerciseId = $exerciseId;
            return $this;
        }

        public function setQuestionId($questionId) {
            $this->questionId = $questionId;
            return $this;
        }

        public function setQuestionText($questionText) {
            $this->questionText = $questionText;
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
