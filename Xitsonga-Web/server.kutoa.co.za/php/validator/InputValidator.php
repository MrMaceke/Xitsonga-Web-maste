<?php
    require_once __DIR__.'/../utils/GeneralUtils.php';
    /**
     * Input Validator
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0  
     */
    class InputValidator {
        
        const PHONE_NUMBER_LENGTH = 10;
        const DIGIT_CODE_LENGTH = 5;
        
        const NAME_LENGTH = 1;
        const FIREBASE_LENGTH = 1;
        
        public function validateAddUser($data){
            if($this->isEmpty($data->phoneNumber) || !$this->minLengthRequirement($data->phoneNumber, InputValidator::PHONE_NUMBER_LENGTH)) { 
                return array(status=> false, message=>"Phone number not populated properly"); 
            }else if($this->isEmpty($data->facebookId) || !$this->minLengthRequirement($data->facebookId, InputValidator::NAME_LENGTH)) { 
                return array(status=> false, message=>"Facebook Id not populated properly"); 
            }else if($this->isEmpty($data->firebaseId) || !$this->minLengthRequirement($data->firebaseId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Firebase Id not populated properly"); 
            }else if($this->isEmpty($data->firstName) || !$this->minLengthRequirement($data->firstName, InputValidator::NAME_LENGTH)) { 
                return array(status=> false, message=>"First name not populated properly"); 
            }else if($this->isEmpty($data->lastName) || !$this->minLengthRequirement($data->lastName, InputValidator::NAME_LENGTH)) { 
                return array(status=> false, message=>"Last name not populated properly"); 
            }
            
            return array(status=> true);
        }
        
        public function validateAddNewDigitCode($data){
            if($this->isEmpty($data->phoneNumber) || !$this->minLengthRequirement($data->phoneNumber, InputValidator::PHONE_NUMBER_LENGTH)) { 
                return array(status=> false, message=>"Phone number is not valid"); 
            }else if($this->isEmpty($data->digitCode) || !$this->minLengthRequirement($data->digitCode, InputValidator::DIGIT_CODE_LENGTH)) { 
                return array(status=> false, message=>"Digit code length too small"); 
            }
            return array(status=> true);
        }
        
        public function validateAcceptRideRequests($data){
            if($this->isEmpty($data->requestId) || !$this->minLengthRequirement($data->requestId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Request id not populated properly"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateRetrieveTracks($data){
            if($this->isEmpty($data->requestId) || !$this->minLengthRequirement($data->requestId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Request id not populated properly"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateAddNewTrackUpdateForTrip($data){
            if($this->isEmpty($data->requestId) || !$this->minLengthRequirement($data->requestId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Request id not populated properly"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }else if($this->isEmpty($data->driverId) || !$this->minLengthRequirement($data->driverId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Driver id not populated properly"); 
            }else if($this->isEmpty($data->passengerId) || !$this->minLengthRequirement($data->passengerId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Passenger id not populated properly"); 
            }else if($this->isEmpty($data->driverLatitude) || !$this->minLengthRequirement($data->driverLatitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Driver latitude id not populated properly"); 
            }else if($this->isEmpty($data->driverLongitude) || !$this->minLengthRequirement($data->driverLongitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Driver longitude id not populated properly"); 
            }else if($this->isEmpty($data->timeEstimate) || !$this->minLengthRequirement($data->timeEstimate, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Driver longitude id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateRetrieveRideRequests($data){
            if($this->isEmpty($data->requestType) || !$this->minLengthRequirement($data->requestType, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Request type not populated properly"); 
            }else if($this->isEmpty($data->kmLimit) || !$this->minLengthRequirement($data->kmLimit, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"KM limit not populated properly"); 
            }else if($this->isEmpty($data->currentLatitude) || !$this->minLengthRequirement($data->currentLatitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Current latitude not populated properly"); 
            }else if($this->isEmpty($data->currentLongitude) || !$this->minLengthRequirement($data->currentLongitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Current longitude not populated properly"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateCancelRequest($data){
            if($this->isEmpty($data->requestId) || !$this->minLengthRequirement($data->requestId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Request id not populated properly"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateRetrieveTrip($data){
            if($this->isEmpty($data->tripId) || !$this->minLengthRequirement($data->tripId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Trip id not populated properly"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateTripChat($data){
            if($this->isEmpty($data->tripId) || !$this->minLengthRequirement($data->tripId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Trip id not populated properly"); 
            }if($this->isEmpty($data->message) || !$this->minLengthRequirement($data->tripId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Message is too short"); 
            }else if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
         public function validateSubmitRating($data){
            if($this->isEmpty($data->tripId) || !$this->minLengthRequirement($data->tripId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Trip id not populated properly"); 
            }else if($this->isEmpty($data->ratingComment) || !$this->minLengthRequirement($data->ratingComment, InputValidator::PHONE_NUMBER_LENGTH)) { 
                return array(status=> false, message=>"Comment is too short"); 
            } else if($this->isEmpty($data->ratingScore) || !$this->minLengthRequirement($data->ratingScore, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Rating score is not populated properly"); 
            } else if($this->isEmpty($data->rateUserId) || !$this->minLengthRequirement($data->rateUserId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateRetrieveTrips($data){
            if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }
            return array(status=> true);
        }
        
        public function validateAddNewRideRequest($data){
            if($this->isEmpty($data->userId) || !$this->minLengthRequirement($data->userId, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"User id not populated properly"); 
            }else if($this->isEmpty($data->pickupName) || !$this->minLengthRequirement($data->pickupName, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Pickup location name is mandatory"); 
            }else if($this->isEmpty($data->destinationName) || !$this->minLengthRequirement($data->destinationName, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Destination location name is mandatory"); 
            }else if($this->isEmpty($data->pickupLatitude) || !$this->minLengthRequirement($data->pickupLatitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Pickup location name is mandatory"); 
            }else if($this->isEmpty($data->pickupLongitude) || !$this->minLengthRequirement($data->pickupLongitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Pickup longitude not populated properly"); 
            }else if($this->isEmpty($data->destinationLatitude) || !$this->minLengthRequirement($data->destinationLatitude, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Destination latitude not populated properly"); 
            }else if($this->isEmpty($data->destinationLongitude) || !$this->minLengthRequirement($data->destinationLongitude, InputValidator::FIREBASE_LENGTH)) { 
                 return array(status=> false, message=>"Destination longitude not populated properly");  
            }else if($this->isEmpty($data->price) || !$this->minLengthRequirement($data->price, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Trip price is mandatory"); 
            }else if($this->isEmpty($data->tripDate) || !$this->minLengthRequirement($data->tripDate, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Trip date is mandatory"); 
            }else if($this->isEmpty($data->tripTime) || !$this->minLengthRequirement($data->tripTime, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Trip time is mandatory"); 
            }else if($this->isEmpty($data->requestType) || !$this->minLengthRequirement($data->requestType, InputValidator::FIREBASE_LENGTH)) { 
                return array(status=> false, message=>"Request type not populated properly"); 
            }else if(!$this->isEmpty($data->numberOfPassengers) && $data->numberOfPassengers > 1) { 
                return array(status=> false, message=>"Kuto BETA only allows for one seat allocation"); 
            }
            
            date_default_timezone_set('Africa/Johannesburg');
            $aTime = date( "Y-m-d", strtotime($data->tripDate))." ".$data->tripTime.":00";
            $aHours = floor((strtotime($aTime) - strtotime(date("Y-m-d H:i:s")))/(60*60));
            
            if($aHours < 10) {
                return array(status=> false, message=>"Trip must be at least 10 hours away from now"); 
            }
            
            $aKM = GeneralUtils::distance($data->pickupLatitude, $data->pickupLongitude, $data->destinationLatitude, $data->destinationLongitude, "K");
            if($aKM < 90) {
                return array(status=> false, message=>"Kutoa BETA only allows long trips. +100KM"); 
            }
            return array(status=> true);
        }
        
        public function validateResendDigitCode($data){
            if($this->isEmpty($data->phoneNumber) || !$this->minLengthRequirement($data->phoneNumber, InputValidator::PHONE_NUMBER_LENGTH)) { 
                return array(status=> false, message=>"Phone number is not valid"); 
            }
            return array(status=> true);
        }
        
        public function maxLengthRequirement($aString,$aLength){
            $aString = trim($aString);
            if(strlen($aString) <= $aLength){
                return TRUE;
            }
            return FALSE;
        }
        
        public function minLengthRequirement($aString,$aLength){
            $aString = trim($aString);
            if(strlen($aString) >= $aLength){
                return TRUE;
            }
            return FALSE;
        }
        /**
         * Validates email address
         * 
         * @param String $aEmail
         * @return oolean
         */
        public function isValidEmailFormat($aEmail) {
            if(filter_var($aEmail, FILTER_VALIDATE_EMAIL)) {
                return TRUE;
            }
            return FALSE;
        }
        /**
         * Checks if string has special characters
         * 
         * @param String $param
         * @return boolean
         */
        public function hasSpecialCharacters($param) {
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $param)) {
                return TRUE;
            }
            return FALSE;
        }
        /**
         * Checks if string is empty
         * 
         * @param String aString
         * @return boolean
         */
        public function isEmpty($aString) {
            $aString = trim($aString);
            if(strlen($aString) == 0){
                return TRUE;
            }
            return FALSE;
        }
    }
?>