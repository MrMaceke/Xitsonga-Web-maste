<?php
    /**
     * Creates a JSONArray
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class PHPToJSONArray {
        
        public function newDigitCodeRequest($phoneNumber, $digitCode) {
            $aJSON = "\"phoneNumber\":\"$phoneNumber\","."\"digitCode\":\"$digitCode\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * @param type $userId
         * @param type $driverId
         * @param type $passengerId
         * @param type $requestId
         * @param type $driverLatitude
         * @param type $driverLongitude
         * @param type $timeEstimate
         * @return JSONArray
         */
        public function newTrackForTrip($userId, $driverId, $passengerId, $requestId, $driverLatitude, $driverLongitude, $timeEstimate) {
            $aJSON = "{"
                ."\"userId\":\"" .  $userId ."\","  
                ."\"driverId\":\"" .  $driverId ."\","       
                ."\"passengerId\":\"" .  $passengerId ."\","        
                ."\"requestId\":\"" .  $requestId ."\","       
                ."\"driverLatitude\":\"" .  $driverLatitude ."\"," 
                ."\"driverLongitude\":\"" .  $driverLongitude ."\"," 
                ."\"timeEstimate\":\"" .  $timeEstimate ."\""     
            ."}";
            
            return json_decode($aJSON);
        }
    }
