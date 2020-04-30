<?php
    require_once __DIR__. '/../constants/FeedbackConstants.php';
    require_once __DIR__.'/../dao/UserDAO.php';
    require_once __DIR__.'/../dao/RideDAO.php';
    /**
     * Generates a JSON object
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class JSONUtils{
        /**
         * Formats input message to JSON
         * 
         * @param string message
         * @param NumberFormatter statusCode
         * @return JSON string
         */
        public function feedback($message, $statusCode) {
            return "{ "
                    ."\"status\":" . $statusCode .","
                    ."\"message\":"."\"".$message ."\""
                    . "}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHP Array records
         * @param string message
         * @return JSON string
         */
        public function request($pRecord, $pMessage) {
            $aUserDAO = new UserDAO();
             
            $aRide = $aRide."{"
                ."\"requestId\":\"" .  $pRecord['request_id'] ."\","
                ."\"requestType\":\"" .  $pRecord['request_type'] ."\","       
                ."\"pickupName\":\"" .  $pRecord['pickup_name'] ."\","
                ."\"destinationName\":\"" .  $pRecord['destination_name'] ."\","
                ."\"pickupLatitude\":\"" .  $pRecord['pickup_latitude'] ."\","
                ."\"pickupLongitude\":\"" .  $pRecord['pickup_longitude'] ."\","    
                ."\"destinationLatitude\":\"" .  $pRecord['destination_latitude'] ."\","
                ."\"destinationLongitude\":\"" .  $pRecord['destination_longitude'] ."\","
                ."\"tripDate\":\"" .  $pRecord['trip_date'] ."\","
                ."\"tripPrice\":\"" .  $pRecord['price'] ."\""     
            ."}";
            
            $aRequestorRecord = $aUserDAO->findUserByUserId($pRecord['user_id']);
            
            $aUser = $aUser."{"
                ."\"userId\":\"" .  $aRequestorRecord[record]['user_id'] ."\","   
                ."\"facebookId\":\"" .  $aRequestorRecord[record]['facebook_id'] ."\","    
                ."\"firstName\":\"" .  $aRequestorRecord[record]['firstname'] ."\","
                ."\"lastName\":\"" .  $aRequestorRecord[record]['lastname'] ."\","
                ."\"phoneNumber\":\"" .  $aRequestorRecord[record]['phone_number'] ."\""  
            ."}";
                  
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"request\": {"
                            ."\"user\":". $aUser.","   
                            ."\"details\":".$aRide
                        ."}"
                    ."}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHP Array records
         * @param string message
         * @return JSON string
         */
        public function trip($pRecord, $pMessage) {
            $aUserDAO = new UserDAO();
            $aRideDAO = new RideDAO();
            
            date_default_timezone_set('Africa/Johannesburg');
            $aHours = floor((strtotime($pRecord['trip_date']) - strtotime(date("Y-m-d H:i:s")))/(60*60));
            
            $aStartTrip = ($aHours < 120) ? 'true' : 'false';
            $aPickup = ($pRecord['pickup'] == 1) ? 'true' : 'false';
            $aDriverRated = (FALSE) ? 'true' : 'false';
            $aPassengerRated = (FALSE) ? 'true' : 'false';
             
            $aRatingReuslts = $aRideDAO->findRatingForTripId($pRecord['request_id']);
            if($aRatingReuslts[status]) {
                foreach($aRatingReuslts[records] as $aRecord){  
                    if($aRecord[user_id] == $pRecord['driver_id']) {
                        $aDriverRated = (TRUE) ? 'true' : 'false';
                    } else if($aRecord[user_id] == $pRecord['passenger_id']) {
                        $aPassengerRated = (TRUE) ? 'true' : 'false';
                    }
                }
            }
            
            $aRated = ($aRatingReuslts[status]) ? 'true' : 'false';
             
            $aRide = $aRide."{"
                ."\"requestId\":\"" .  $pRecord['request_id'] ."\","       
                ."\"startTrip\":" .  $aStartTrip .","
                ."\"pickup\":" .  $aPickup .","    
                ."\"rated\":" .  $aRated ."," 
                ."\"driverRated\":" .  $aDriverRated ."," 
                ."\"passengerRated\":" .  $aPassengerRated ."," 
                ."\"status\":\"" .  $pRecord['trip_status'] ."\","        
                ."\"pickupName\":\"" .  $pRecord['pickup_name'] ."\","
                ."\"destinationName\":\"" .  $pRecord['destination_name'] ."\","
                ."\"pickupLatitude\":\"" .  $pRecord['pickup_latitude'] ."\","
                ."\"pickupLongitude\":\"" .  $pRecord['pickup_longitude'] ."\","    
                ."\"destinationLatitude\":\"" .  $pRecord['destination_latitude'] ."\","
                ."\"destinationLongitude\":\"" .  $pRecord['destination_longitude'] ."\","
                ."\"tripDate\":\"" .  $pRecord['trip_date'] ."\","
                ."\"tripPrice\":\"" .  $pRecord['price'] ."\""     
            ."}";
            
            $aDriverRecord = $aUserDAO->findUserByUserId($pRecord['driver_id']);
            $aPassengerRecord = $aUserDAO->findUserByUserId($pRecord['passenger_id']);
            
            $aDriver = $aDriver."{"
                ."\"roleName\":\"" .  "Driver" ."\","       
                ."\"userId\":\"" .  $aDriverRecord[record]['user_id'] ."\","   
                ."\"facebookId\":\"" .  $aDriverRecord[record]['facebook_id'] ."\","    
                ."\"firstName\":\"" .  $aDriverRecord[record]['firstname'] ."\","
                ."\"lastName\":\"" .  $aDriverRecord[record]['lastname'] ."\","
                ."\"phoneNumber\":\"" .  $aDriverRecord[record]['phone_number'] ."\""  
            ."}";
            
            $aPassenger = $aPassenger."{"
                ."\"roleName\":\"" .  "Passenger" ."\","       
                ."\"userId\":\"" .  $aPassengerRecord[record]['user_id'] ."\","   
                ."\"facebookId\":\"" .  $aPassengerRecord[record]['facebook_id'] ."\","   
                ."\"firstName\":\"" .  $aPassengerRecord[record]['firstname'] ."\","
                ."\"lastName\":\"" .  $aPassengerRecord[record]['lastname'] ."\","
                ."\"phoneNumber\":\"" .  $aPassengerRecord[record]['phone_number'] ."\""  
            ."}";
            
                     
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"trip\": {"
                            ."\"driver\": ".$aDriver.","
                            ."\"passenger\":". $aPassenger.","   
                            ."\"details\":".$aRide
                        ."}"
                    ."}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHP Array records
         * @param string message
         * @return JSON string
         */
        public function user($pRecord, $pMessage) {
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aTripResults = $aRideDAO->findOldTripsForUserId($pRecord['user_id']);
            
            $aTrips = 0;
            if($aTripResults[status]) {
                $aTrips = count($aTripResults[records]);
            }
            
            $aRating = 0;
            $aRatingResults = $aRideDAO->findRatingForUserId($pRecord['user_id']);
            if($aRatingResults[status]) {
                $aNumberRating = count($aRatingResults[records]);
                $aTotalRating = 0;
                foreach ($aRatingResults[records] as $key => $aRating) {
                    $aTotalRating += $aRating[rating];
                }
                $aRating = $aTotalRating / $aNumberRating;
            }
            
            $aContactsUserResults = $aUserDAO->findContactsForUserId($pRecord['user_id']);
            $aPhoneNumber1 = "";
            $aPhoneNumber2 = "";
            if($aContactsUserResults[status]) {
                $aContactsUserRecord = $aContactsUserResults[record];
                $aPhoneNumber1 = $aContactsUserRecord[phone_number1];
                $aPhoneNumber2 = $aContactsUserRecord[phone_number2];
            }
            $aTrack = $aTrack."{"
                ."\"userId\":\"" .  $pRecord['user_id'] ."\","       
                ."\"facebookId\":\"" .  $pRecord['facebook_id'] ."\","    
                ."\"firstName\":\"" .  $pRecord['firstname'] ."\","
                ."\"lastName\":\"" .  $pRecord['lastname'] ."\","
                ."\"dateCreated\":\"" .  $pRecord['date_created'] ."\","
                ."\"phoneNumber\":\"" .  $pRecord['phone_number'] ."\","
                ."\"emergencyPhoneNumber1\":\"" .  $aPhoneNumber1 ."\","
                ."\"emergencyPhoneNumber2\":\"" .  $aPhoneNumber2 ."\","
                ."\"gender\":\"" . ucwords($pRecord['gender']) ."\","
                ."\"rating\":\"" .  $aRating ."\","
                ."\"trips\":\"" .  $aTrips ."\""      
            ."}";
            
                
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"user\":"
                        .$aTrack
                    ."}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHP Array records
         * @param string message
         * @return JSON string
         */
        public function track($pRecord, $pMessage) {    
            $aRideDAO = new RideDAO();
            
            $aTripResults = $aRideDAO->findTripByRequestId($pRecord['request_id']);
            $aPickup = (FALSE) ? 'true' : 'false';
            if($aTripResults[status]) {
                if($aTripResults[record][pickup] == 1) {
                    $aPickup = (TRUE) ? 'true' : 'false';
                }
            }
            
            $aTrack = $aTrack."{"
                ."\"requestId\":\"" .  $pRecord['request_id'] ."\","       
                ."\"pickup\":\"" .  $aPickup ."\","       
                ."\"driverLatitude\":\"" .  $pRecord['driver_latitude'] ."\","        
                ."\"driverLongitude\":\"" .  $pRecord['driver_longitude'] ."\""     
            ."}";
            
                
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"track\":"
                        .$aTrack
                    ."}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHP Array records
         * @param string message
         * @return JSON string
         */
        public function trips($pRecords, $pMessage) {
            $aUserDAO = new UserDAO();
            $aStartTrue = (TRUE) ? 'true' : 'false';
            foreach($pRecords as $pRecord){  
                $aRide = "{"
                    ."\"requestId\":\"" .  $pRecord['request_id'] ."\","   
                    ."\"startTrip\":" .  $aStartTrue .","
                    ."\"status\":\"" .  $pRecord['trip_status'] ."\","     
                    ."\"pickupName\":\"" .  $pRecord['pickup_name'] ."\","
                    ."\"destinationName\":\"" .  $pRecord['destination_name'] ."\","
                    ."\"pickupLatitude\":\"" .  $pRecord['pickup_latitude'] ."\","
                    ."\"pickupLongitude\":\"" .  $pRecord['pickup_longitude'] ."\","    
                    ."\"destinationLatitude\":\"" .  $pRecord['destination_latitude'] ."\","
                    ."\"destinationLongitude\":\"" .  $pRecord['destination_longitude'] ."\","
                    ."\"tripDate\":\"" .  $pRecord['trip_date'] ."\","
                    ."\"tripPrice\":\"" .  $pRecord['price'] ."\""     
                ."}";
            
                $aDriverRecord = $aUserDAO->findUserByUserId($pRecord['driver_id']);
                $aPassengerRecord = $aUserDAO->findUserByUserId($pRecord['passenger_id']);
            
                $aDriver = "{"
                    ."\"roleName\":\"" .  "Driver" ."\","       
                    ."\"userId\":\"" .  $aDriverRecord[record]['user_id'] ."\","   
                    ."\"facebookId\":\"" .  $aDriverRecord[record]['facebook_id'] ."\","    
                    ."\"firstName\":\"" .  $aDriverRecord[record]['firstname'] ."\","
                    ."\"lastName\":\"" .  $aDriverRecord[record]['lastname'] ."\","
                    ."\"phoneNumber\":\"" .  $aDriverRecord[record]['phone_number'] ."\""  
                ."}";
            
                $aPassenger = "{"
                    ."\"roleName\":\"" .  "Passenger" ."\","       
                    ."\"userId\":\"" .  $aPassengerRecord[record]['user_id'] ."\","   
                    ."\"facebookId\":\"" .  $aPassengerRecord[record]['facebook_id'] ."\","   
                    ."\"firstName\":\"" .  $aPassengerRecord[record]['firstname'] ."\","
                    ."\"lastName\":\"" .  $aPassengerRecord[record]['lastname'] ."\","
                    ."\"phoneNumber\":\"" .  $aPassengerRecord[record]['phone_number'] ."\""  
                ."}";
                
                $aFullDetails = $aFullDetails."{"
                            ."\"driver\": ".$aDriver.","
                            ."\"passenger\":". $aPassenger.","   
                            ."\"details\":".$aRide
                        ."}";
                $aFullDetails = $aFullDetails.",";
            }
            $aFullDetails = substr_replace($aFullDetails, "", -1);
                        
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"trips\": ["
                        .$aFullDetails
                        ."]"
                    ."}";
        }
        /**
         * Formats input message to JSON
         * 
         * @param PHP Array records
         * @param string message
         * @return JSON string
         */
        public function rideRequests($pRecords, $pMessage) {
            foreach($pRecords as $aRecord){  
                $aRides = $aRides."{"
                    ."\"userId\":\"" .  $aRecord['user_id'] ."\","
                    ."\"facebookId\":\"" .  $aRecord['facebook_id'] ."\","    
                    ."\"firstName\":\"" .  $aRecord['firstname'] ."\","
                    ."\"lastName\":\"" .  $aRecord['lastname'] ."\","
                    ."\"phoneNumber\":\"" .  $aRecord['phone_number'] ."\","  
                    ."\"requestId\":\"" .  $aRecord['request_id'] ."\","   
                    ."\"pickupName\":\"" .  $aRecord['pickup_name'] ."\","
                    ."\"destinationName\":\"" .  $aRecord['destination_name'] ."\","
                    ."\"pickupLatitude\":\"" .  $aRecord['pickup_latitude'] ."\","
                    ."\"pickupLongitude\":\"" .  $aRecord['pickup_longitude'] ."\","
                    ."\"destinationLatitude\":\"" .  $aRecord['destination_latitude'] ."\","
                    ."\"destinationLongitude\":\"" .  $aRecord['destination_longitude'] ."\","
                    ."\"requestType\":\"" .  $aRecord['request_type'] ."\","
                    ."\"status\":\"" .  $aRecord['status'] ."\"," 
                    ."\"price\":\"" .  $aRecord['price'] ."\","
                    ."\"tripDate\":\"" .  $aRecord['trip_date'] ."\""
                ."}";
                
                $aRides = $aRides.",";
            }
            $aRides = substr_replace($aRides, "", -1);
            
            return "{ "
                    ."\"status\":" . FeedbackConstants::SUCCESSFUL .","
                    ."\"message\":"."\"".$pMessage ."\","
                    ."\"rideRequests\":["
                       .$aRides 
                    ."]"
                    . "}";
        }
    }