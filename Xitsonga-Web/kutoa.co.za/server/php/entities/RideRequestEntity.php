<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "ride_requests"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class RideRequestEntity extends DbTable{
        private $userId;
        private $pickupName;
        private $destinationName;
        private $pickupLongitude;
        private $pickupLatitude;
        private $destinationLatitude;
        private $destinationLongitude;
        private $price;
        private $tripDate;
        private $requestType;
        private $status;
        
        public function RideRequestEntity() {
            parent::__construct(TableNamesConstants::RIDE_REQUESTS);

            $fieldList = array("user_id","pickup_name","destination_name","pickup_latitude","pickup_longitude","destination_latitude","destination_longitude","price","trip_date","request_type","status");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->userId),trim($this->pickupName),trim($this->destinationName),trim($this->pickupLatitude),trim($this->pickupLongitude),trim($this->destinationLatitude),trim($this->destinationLongitude),trim($this->price),trim($this->tripDate),trim($this->requestType),trim($this->status));
        }
        
        public function getUserId() {
            return $this->userId;
        }

        public function getPickupName() {
            return $this->pickupName;
        }

        public function getDestinationName() {
            return $this->destinationName;
        }

        public function getPickupLongitude() {
            return $this->pickupLongitude;
        }

        public function getPickupLatitude() {
            return $this->pickupLatitude;
        }

        public function getDestinationLatitude() {
            return $this->destinationLatitude;
        }

        public function getDestinationLongitude() {
            return $this->destinationLongitude;
        }

        public function getPrice() {
            return $this->price;
        }

        public function getTripDate() {
            return $this->tripDate;
        }

        public function getRequestType() {
            return $this->requestType;
        }

        public function getStatus() {
            return $this->status;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setPickupName($pickupName) {
            $this->pickupName = $pickupName;
        }

        public function setDestinationName($destinationName) {
            $this->destinationName = $destinationName;
        }

        public function setPickupLongitude($pickupLongitude) {
            $this->pickupLongitude = $pickupLongitude;
        }

        public function setPickupLatitude($pickupLatitude) {
            $this->pickupLatitude = $pickupLatitude;
        }

        public function setDestinationLatitude($destinationLatitude) {
            $this->destinationLatitude = $destinationLatitude;
        }

        public function setDestinationLongitude($destinationLongitude) {
            $this->destinationLongitude = $destinationLongitude;
        }

        public function setPrice($price) {
            $this->price = $price;
        }

        public function setTripDate($tripDate) {
            $this->tripDate = $tripDate;
        }

        public function setRequestType($requestType) {
            $this->requestType = $requestType;
        }

        public function setStatus($status) {
            $this->status = $status;
        }
    }