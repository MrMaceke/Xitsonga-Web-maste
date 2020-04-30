<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "trip_rating"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class RatingEntity extends DbTable{
        private $requestId;
        private $userId;
        private $ratedBy;
        private $rating;
        private $comment;
        
        public function RatingEntity() {
            parent::__construct(TableNamesConstants::TRACK_RATING);

            $fieldList = array("request_id","user_id","rated_by","rating","comment");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->requestId),trim($this->userId),trim($this->ratedBy),trim($this->rating),trim($this->comment));
        }
        
        public function getRequestId() {
            return $this->requestId;
        }

        public function setRequestId($requestId) {
            $this->requestId = $requestId;
        }
      
        public function getUserId() {
            return $this->userId;
        }

        public function getRatedBy() {
            return $this->ratedBy;
        }

        public function getRating() {
            return $this->rating;
        }

        public function getComment() {
            return $this->comment;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setRatedBy($ratedBy) {
            $this->ratedBy = $ratedBy;
        }

        public function setRating($rating) {
            $this->rating = $rating;
        }

        public function setComment($comment) {
            $this->comment = $comment;
        }
    }