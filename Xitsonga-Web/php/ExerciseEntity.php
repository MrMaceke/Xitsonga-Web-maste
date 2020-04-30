<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "exercises"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class ExerciseEntity extends DbTable{
        private $userId;
        private $exerciseId;
        private $exerciseTitle;
        private $exerciseText;
        private $dateCreated;
        
        public function ExerciseEntity() {
            parent::__construct(DATABASE_NAME,TABLE_EXERCISE);

            $fieldList = array("user_id","exercise_id","exercise_title","exercises_text","date_created");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->exerciseId,$this->exerciseTitle,$this->exerciseText,$this->dateCreated);
        }
        public function getUserId() {
            return $this->userId;
        }

        public function getExerciseId() {
            return $this->exerciseId;
        }

        public function getExerciseText() {
            return $this->exerciseText;
        }

        public function getDateCreated() {
            return $this->dateCreated;
        }

        public function getExerciseTitle() {
            return $this->exerciseTitle;
        }

        public function setExerciseTitle($exerciseTitle) {
            $this->exerciseTitle = $exerciseTitle;
            return $this;
        }
        
        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function setExerciseId($exerciseId) {
            $this->exerciseId = $exerciseId;
            return $this;
        }

        public function setExerciseText($exerciseText) {
            $this->exerciseText = $exerciseText;
            return $this;
        }

        public function setDateCreated($dateCreated) {
            $this->dateCreated = $dateCreated;
            return $this;
        }
    }

?>
