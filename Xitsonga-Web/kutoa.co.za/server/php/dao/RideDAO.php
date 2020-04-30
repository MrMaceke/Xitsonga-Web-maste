<?php
    require_once __DIR__. '/../persistence/EntityManager.php';
    require_once __DIR__. '/../entities/RideRequestEntity.php';
    require_once __DIR__. '/../entities/TripEntity.php';
    require_once __DIR__. '/../entities/TrackEntity.php';
    require_once __DIR__. '/../entities/RatingEntity.php';
    require_once __DIR__. '/../persistence/NamedQuery.php';
    require_once __DIR__. '/../persistence/NamedConstants.php';
    require_once __DIR__. '/../utils/GeneralUtils.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Access and modifies ride_requests, trips related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class RideDAO {
        private $aEntityManager = NULL;
        private $logging = NULL;
                
        public function RideDAO() {
            $this->aEntityManager = new EntityManager(NULL);
            $this->logging = new Logging(self::class);
        }
        /**
         * Adds a ride request
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addNewRideRequest($param) {
            $this->logging->startMethod("addNewRideRequest");
            
            $aRideRequestEntity = new RideRequestEntity();
            $aRideRequestEntity->setUserId($param->userId);
            $aRideRequestEntity->setPickupName($param->pickupName);
            $aRideRequestEntity->setDestinationName($param->destinationName);
            $aRideRequestEntity->setPickupLatitude($param->pickupLatitude);
            $aRideRequestEntity->setPickupLongitude($param->pickupLongitude);
            $aRideRequestEntity->setDestinationLatitude($param->destinationLatitude);
            $aRideRequestEntity->setDestinationLongitude($param->destinationLongitude);
            $aRideRequestEntity->setPrice($param->price);
            $aRideRequestEntity->setTripDate(date( "Y-m-d", strtotime($param->tripDate))." ".$param->tripTime.":00");
            $aRideRequestEntity->setStatus("0");
            $aRideRequestEntity->setRequestType($param->requestType);
            
            $this->aEntityManager->setTable($aRideRequestEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aRideRequestEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $aRideResults = $this->findLatestRequestForUserId($param->userId);
                if($aRideResults[status]) {
                    $this->logging->exitMethod("addNewRideRequest");
                    return $aRideResults;
                }
                $this->logging->exitMethod("addNewRideRequest");
                return array(status=> false, $aRideResults[message]);
            }

            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewRideRequest",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
        /**
         * Adds a new rating
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addTripRating($param) {
            $this->logging->startMethod("addTripRating");
            
            $aRatingEntity = new RatingEntity();
            $aRatingEntity->setRequestId($param->tripId);
            $aRatingEntity->setUserId($param->rateUserId);
            $aRatingEntity->setRatedBy($param->userId);
            $aRatingEntity->setRating($param->ratingScore);
            $aRatingEntity->setComment($param->ratingComment);
            
            $this->aEntityManager->setTable($aRatingEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aRatingEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $this->logging->exitMethod("addTripRating");
                return array(status=> true, message=>"Rating added");
            }
            
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addTripRating",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
        /**
         * Adds a new trip
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addNewTrip($param) {
            $this->logging->startMethod("addNewTrip");
            
            $aRideResults = $this->findRideRequestByRequestId($param->requestId);
            if($aRideResults[status]) {
                $aStatus = $aRideResults[record][status];
                if($aStatus == 1) {
                    $this->logging->exitMethodWithError("addNewTrip",$aRideResults[message]);
                    return array(status=> false, message=>"Request already accepted");
                }
            } else {
                $this->logging->exitMethodWithError("addNewTrip",$aRideResults[message]);
                return array(status=> false, message=>"Request has been cancelled");
            }
            
            if($aRideResults[record][user_id] == $param->userId) {
                $this->logging->exitMethodWithError("addNewTrip","You cannot accept your own request");
                return array(status=> false, message=>"You cannot accept your own request");
            }
            
            $aTripEntity = new TripEntity();
            $aTripEntity->setStatus("0");
            $aTripEntity->setMessage("");
            $aTripEntity->setRequestId($aRideResults[record][request_id]);
            if($aRideResults[record][request_type] == 2) {
                $aTripEntity->setDriverId($aRideResults[record][user_id]);
                $aTripEntity->setPassengerId($param->userId);
            } else if($aRideResults[record][request_type] == 1) {
                $aTripEntity->setDriverId($param->userId);
                $aTripEntity->setPassengerId($aRideResults[record][user_id]);
            }
            
            $this->aEntityManager->setTable($aTripEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aTripEntity->ToArray());
            if($aResult['status']) {
                $aRideUpdate = $this->updateRideRequestStatusByRequestId("1", $param->requestId);
                if($aRideUpdate['status']) {
                    $this->aEntityManager->getSql()->commitTransaction();
                    $this->logging->exitMethod("addNewTrip");
                    return $aRideResults;
                }
            }
            
            $this->aEntityManager->getSql()->rollbackTransaction();
            $this->logging->exitMethodWithError("addNewTrip",$aResult[message]);
            return array(status=> false, message=>$aResult[message]);
        }
         /**
         * Adds a new trip
         * 
         * @param JSON data
         * @return Array with status and message
         */
        public function addTrackForTrip($param) {
            $this->logging->startMethod("addTrackForTrip");
            
            $aTrackEntity = new TrackEntity();
            $aTrackEntity->setDriverId($param->driverId);
            $aTrackEntity->setPassengerId($param->passengerId);
            $aTrackEntity->setRequestId($param->requestId);
            $aTrackEntity->setDriverLatitude($param->driverLatitude);
            $aTrackEntity->setDriverLongitude($param->driverLongitude);
            $aTrackEntity->setTimeEstimate($param->timeEstimate);

            $this->aEntityManager->setTable($aTrackEntity);
            $this->aEntityManager->getSql()->beginTransaction();
            $aResult = $this->aEntityManager->addData($aTrackEntity->ToArray());
            if($aResult['status']) {
                $this->aEntityManager->getSql()->commitTransaction();
                $this->logging->exitMethod("addTrackForTrip");
                return array(status=> true, message=>"Track added to trip");
            }
            
            $this->logging->exitMethod("addTrackForTrip");
            return array(status=> false,message => $aResult[message]);
        }
        /**
        * Finds trip
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findTripByRequestId($param) {
            $this->logging->startMethod("findTripByRequestId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_TRIP_REQUEST_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findTripByRequestId");
                return array(status=> false,message => "Trip not found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findTripByRequestId");
                return array(status=> true,record => $aRecords);
            }
        }
        /**
        * Finds Track Record
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findTrackRecordByRequestId($param) {
            $this->logging->startMethod("findTrackRecordByRequestId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_TRACK_FOR_TRIP__BY_REQUEST_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findTrackRecordByRequestId");
                return array(status=> false,message => "Track not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findTrackRecordByRequestId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds trips
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findTripsForUserId($param) {
            $this->logging->startMethod("findTripsByUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_TRIPS_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            $aNameQuery->setParameter(2, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findTripsByUserId");
                return array(status=> false,message => "No trip found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findTripsByUserId");
                return array(status=> true,records => $aRecords);
            }
        }
        /**
        * Finds ratings for trips
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findRatingForTripId($param) {
            $this->logging->startMethod("findRatingForTripId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_RATINGS_FOR_TRIP__BY_REQUEST_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRatingForTripId");
                return array(status=> false,message => "No ratings found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRatingForTripId");
                return array(status=> true,records => $aRecords);
            }
        }
        /**
        * Finds ratings for user
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findRatingForUserId($param) {
            $this->logging->startMethod("findRatingForUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_RATINGS_FOR_TRIP__BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRatingForUserId");
                return array(status=> false,message => "No ratings found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRatingForUserId");
                return array(status=> true,records => $aRecords);
            }
        }
        /**
        * Finds old trips
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findOldTripsForUserId($param) {
            $this->logging->startMethod("findOldTripsForUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_OLD_TRIPS_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            $aNameQuery->setParameter(2, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findOldTripsForUserId");
                return array(status=> false,message => "No trip found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findOldTripsForUserId");
                return array(status=> true,records => $aRecords);
            }
        }
        /**
        * Finds trip
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findTripInProgressForUserId($param) {
            $this->logging->startMethod("findTripInProgressForUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_TRIPS_IN_PROGRESS_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findTripInProgressForUserId");
                return array(status=> false,message => "No trip found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findTripInProgressForUserId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds trip
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findLatestRequestForUserId($param) {
            $this->logging->startMethod("findLatestRequestForUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_LATEST_RIDE_REQUEST_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findLatestRequestForUserId");
                return array(status=> false,message => "Request not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findLatestRequestForUserId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds trip
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findTripInProgressForDriverId($param) {
            $this->logging->startMethod("findTripInProgressForDriverId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_TRIPS_IN_PROGRESS_BY_DRIVER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findTripInProgressForDriverId");
                return array(status=> false,message => "No trip found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findTripInProgressForDriverId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds trip
        * 
        * @param String requestId
        * @return Array with status and message
        */
        public function findTripInProgressForPassengerId($param) {
            $this->logging->startMethod("findTripInProgressForPassengerId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_TRIPS_IN_PROGRESS_BY_PASSENGER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findTripInProgressForPassengerId");
                return array(status=> false,message => "No trip found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findTripInProgressForPassengerId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
        * Finds ride requests
        * 
        * @param String user Id, request type
        * @return Array with status and message
        */
        public function findRideRequestsExcludingUserId($param) {
            $this->logging->startMethod("findRideRequestsExcludingUserId");
            
            if($param->ignoreCurrentLocation == "1") {
                $aNameQuery = new NamedQuery(NamedConstants::SELECT_RIDE_REQUESTS_EXCLUDE_USER_ID);
                $aNameQuery->setParameter(1, $param->requestType);
                $aNameQuery->setParameter(2, $param->userId);
            } else {
                $aNameQuery = new NamedQuery(NamedConstants::SELECT_RIDE_REQUEST_EXCLUDE_USER_ID_BY_DISTANCE);
                $aNameQuery->setParameter(1, $param->requestType);
                $aNameQuery->setParameter(2, $param->userId);
                $aNameQuery->setParameterInteger(3, $param->currentLatitude);
                $aNameQuery->setParameterInteger(4, $param->currentLongitude);
                $aNameQuery->setParameterInteger(5, $param->kmLimit);
            }
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRideRequestsExcludingUserId");
                return array(status=> false,message => "No ride requests found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRideRequestsExcludingUserId");
                return array(status=> true,records => $aRecords);
            }
        }
        /**
        * Finds pending requests
        * 
        * @param String user Id
        * @return Array with status and message
        */
        public function findRideRequestsByUserId($param) {
            $this->logging->startMethod("findRideRequestsByUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_RIDE_REQUESTS_BY_USER_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRideRequestsByUserId");
                return array(status=> false,message => "No pending requests found");
            } else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRideRequestsByUserId");
                return array(status=> true,records => $aRecords);
            }
        }
        /**
        * Finds ride request by request id
        * 
        * @param String request id
        * @return Array with status and message
        */
        public function findRideRequestByRequestId($param) {
            $this->logging->startMethod("findRideRequestsExcludingUserId");
            
            $aNameQuery = new NamedQuery(NamedConstants::SELECT_RIDE_REQUEST_BY_REQUEST_ID);
            $aNameQuery->setParameter(1, $param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            if($aCount == 0){
                $this->logging->exitMethod("findRideRequestByRequestId");
                return array(status=> false,message => "Request not found");
            } else{
                $aRecord = $this->aEntityManager->getSql()->getRecordInResults($aResult['resultsArray']);
                
                $this->logging->exitMethod("findRideRequestsExcludingUserId");
                return array(status=> true,record => $aRecord);
            }
        }
        /**
         * Updates status for ride request
         * 
         * @param String param1 - status
         * @param String param2 - request id
         * 
         * @return Array with status and message
         */
        public function updateRideRequestStatusByRequestId($param1, $param2) {
            $this->logging->startMethod("updateRideRequestStatusByRequestId");
 
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_RIDE_REQUEST_STATUS_BY_REQUEST_ID);
            $aNameQuery->setParameter(1,$param1);
            $aNameQuery->setParameter(2,$param2);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateRideRequestStatusByRequestId");
                return array(status=> true, message=> "Status updated");
            }
            $this->logging->exitMethod("updateRideRequestStatusByRequestId");
            return array(status=> false, message=> "Service failed to update status");
        }
        /**
         * Updates status for trip
         * 
         * @param String param1 - status
         * @param String param2 - request id
         * 
         * @return Array with status and message
         */
        public function updateTripStatusByRequestId($param1, $param2) {
            $this->logging->startMethod("updateTripStatusByRequestId");
 
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_TRIP_STATUS_BY_REQUEST_ID);
            $aNameQuery->setParameter(1,$param1);
            $aNameQuery->setParameter(2,$param2);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateTripStatusByRequestId");
                return array(status=> true, message=> "Status updated");
            }
            $this->logging->exitMethod("updateTripStatusByRequestId");
            return array(status=> false, message=> "Service failed to update status");
        }
        /**
         * Updates pickup for trip
         * 
         * @param String param1 - status
         * @param String param2 - request id
         * 
         * @return Array with status and message
         */
        public function updateTripPickupByRequestId($param1, $param2) {
            $this->logging->startMethod("updateTripPickupByRequestId");
 
            $aNameQuery = new NamedQuery(NamedConstants::UPDATE_TRIP_PICKUP_BY_REQUEST_ID);
            $aNameQuery->setParameter(1,$param1);
            $aNameQuery->setParameter(2,$param2);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("updateTripPickupByRequestId");
                return array(status=> true, message=> "Pickup updated");
            }
            $this->logging->exitMethod("updateTripPickupByRequestId");
            return array(status=> false, message=> "Service failed to update pickup");
        }
        /**
         * Removes ride request
         * 
         * @param String param - request id
         * 
         * @return Array with status and message
         */
        public function deleteRideRequestByRequestId($param) {
            $this->logging->startMethod("deleteRideRequestByRequestId");
            
            $aRequestsResults = $this->findRideRequestByRequestId($param);
            if(!$aRequestsResults[status]) {
                if($aRequestsResults[record][status] != 0) {
                    return array(status=> false, message=> "Request already accepted");
                }
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_REQUEST_BY_REQUEST_ID);
            $aNameQuery->setParameter(1,$param);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteTripByRequestId");
                return array(status=> true, message=> "Request deleted");
            }
            $this->logging->exitMethod("deleteRideRequestByRequestId");
            return array(status=> false, message=> "Service failed to delete request");
        }
        /**
         * Removes trip
         * 
         * @param String param1 - request id
         * 
         * @return Array with status and message
         */
        public function deleteTripByRequestId($param1) {
            $this->logging->startMethod("deleteTripByRequestId");
            
            $aRequestsResults = $this->updateRideRequestStatusByRequestId("0", $param1);
            if(!$aRequestsResults[status]) {
                return $aRequestsResults;
            }
            
            $aNameQuery = new NamedQuery(NamedConstants::DELETE_TRIP_BY_REQUEST_ID);
            $aNameQuery->setParameter(1,$param1);
            
            $this->logging->debug("SQL =",$aNameQuery->getQuery());
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            if($aResult){
                $this->logging->exitMethod("deleteTripByRequestId");
                return array(status=> true, message=> "Trip deleted");
            }
            $this->logging->exitMethod("deleteTripByRequestId");
            return array(status=> false, message=> "Service failed to delete trip");
        }
    }