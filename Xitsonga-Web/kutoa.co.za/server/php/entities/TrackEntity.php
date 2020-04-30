<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "track"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class TrackEntity extends DbTable{
        private $driverId;
        private $passengerId;
        private $requestId;
        private $driverLongitude;
        private $driverLatitude;
        private $timeEstimate;
        
        public function TrackEntity() {
            parent::__construct(TableNamesConstants::TRACKS);

            $fieldList = array("driver_id","passenger_id","request_id","driver_latitude","driver_longitude","time_estimate");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->driverId),trim($this->passengerId),trim($this->requestId),trim($this->driverLatitude),trim($this->driverLongitude),trim($this->timeEstimate));
        }
        
        public function getDriverId() {
            return $this->driverId;
        }

        public function getPassengerId() {
            return $this->passengerId;
        }

        public function getRequestId() {
            return $this->requestId;
        }

        public function getDriverLongitude() {
            return $this->driverLongitude;
        }

        public function getDriverLatitude() {
            return $this->driverLatitude;
        }

        public function getTimeEstimate() {
            return $this->timeEstimate;
        }

        public function setDriverId($driverId) {
            $this->driverId = $driverId;
        }

        public function setPassengerId($passengerId) {
            $this->passengerId = $passengerId;
        }

        public function setRequestId($requestId) {
            $this->requestId = $requestId;
        }

        public function setDriverLongitude($driverLongitude) {
            $this->driverLongitude = $driverLongitude;
        }

        public function setDriverLatitude($driverLatitude) {
            $this->driverLatitude = $driverLatitude;
        }

        public function setTimeEstimate($timeEstimate) {
            $this->timeEstimate = $timeEstimate;
        }
    }