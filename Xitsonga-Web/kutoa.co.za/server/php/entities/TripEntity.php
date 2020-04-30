<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "trips"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class TripEntity extends DbTable{
        private $driverId;
        private $passengerId;
        private $requestId;
        private $message;
        private $status;
        
        public function TripEntity() {
            parent::__construct(TableNamesConstants::TRIPS);

            $fieldList = array("driver_id","passenger_id","request_id","message","status");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->driverId),trim($this->passengerId),trim($this->requestId), trim($this->message),trim($this->status));
        }
        
        public function getDriverId() {
            return $this->driverId;
        }

        public function getPassengerId() {
            return $this->passengerId;
        }

        public function getMessage() {
            return $this->message;
        }

        public function getStatus() {
            return $this->status;
        }

        public function setDriverId($driverId) {
            $this->driverId = $driverId;
        }

        public function setPassengerId($passengerId) {
            $this->passengerId = $passengerId;
        }

        public function getRequestId() {
            return $this->requestId;
        }

        public function setRequestId($requestId) {
            $this->requestId = $requestId;
        }
      
        public function setMessage($message) {
            $this->message = $message;
        }

        public function setStatus($status) {
            $this->status = $status;
        }
    }