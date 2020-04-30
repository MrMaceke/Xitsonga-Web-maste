<?php
    date_default_timezone_set('Africa/Johannesburg');
     
    require_once './constants/FeedbackConstants.php';
    
    require_once './utils/JsonUtils.php';
    require_once './utils/GeneralUtils.php';
    require_once './utils/NotificationUtils.php';
    require_once './utils/PHPToJSONArray.php';
    require_once './utils/SMSUtils.php';
    
    require_once './notify/Firebase.php';
    require_once './notify/Push.php';
    
    require_once './utils/Logging.php';
    require_once './validator/InputValidator.php';
    require_once './validator/BusinessDataValidator.php';
    
    require_once './dao/UserDAO.php';
    require_once './dao/RideDAO.php';
      
    $aFunction = $_REQUEST['type'];
    $aJSONData = $_REQUEST['data'];
    $aSystem   = $_REQUEST['system'];
    $aVersion = $_REQUEST['v'];
    
    if(isset($aSystem) && $aSystem == "android" && $aVersion >= 30){
        $aJSONData = trim(file_get_contents('php://input'));
        $aKutoaBean = new KutoaBean();
        
        if(method_exists($aKutoaBean , $aFunction)){
           echo $aKutoaBean->functionName($aFunction, $aJSONData);
        } else {
            echo $aKutoaBean->jsonFeedback->feedback("Function is not supported", FeedbackConstants::FAILED);
        }
    } else {
        $aKutoaBean = new KutoaBean();
        
        echo $aKutoaBean->jsonFeedback->feedback("API $aVersion: Your app version is outdated.", FeedbackConstants::FAILED);
    }

    /**
     * @author Sneidon Dumela <sneidon@yahoo.com>
     */
    class KutoaBean {
        public $jsonFeedback = null;
        public $logging = null;
        
        public $EXCLUDE_DEVICE_VALIDATION = array("sendOneTimePIN","verifyDigitCode","registerUser","resendOneTimePIN");
        
        public function KutoaBean() {
            $this->logging = new Logging(self::class);
            $this->jsonFeedback = new JSONUtils();
        }
        /**
         * Dynamically calls a function
         * 
         * @param type $functionName
         * @param type $param
         * @return JSONString
         */
        public function functionName($functionName, $param) {
            if($param == null){
               return $this->$functionName();
            }else{
                $param = json_decode($param);
                $aBusinessDataValidator = new BusinessDataValidator();
                
                If(!in_array($functionName, $this->EXCLUDE_DEVICE_VALIDATION)){
                    $aValidation = $aBusinessDataValidator->validateAccess($param);
                    if(!$aValidation[status]) {
                        $this->logging->debugObject("Param",$param);
                        $this->logging->debugObject("validateAccess",$aValidation[message]);
                        return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::SESSION_EXPIRED);
                    }
                }
                
                return $this->$functionName($param);
            }
        }
        
        /**
         *  Creates new ride request
         * 
         * @param type $param
         * @return JSONString
         */
        private function requestRide($param) {
            $this->logging->startMethod("requestRide");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aBusinessDataValidator = new BusinessDataValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateAddNewRideRequest($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("requestRide");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aBusinessValidation = $aBusinessDataValidator->validateContactAvailable($param);
            if(!$aBusinessValidation[status]) {
                $this->logging->exitMethod("requestRide");
                return $this->jsonFeedback->feedback($aBusinessValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->addNewRideRequest($param);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                $this->logging->exitMethod("requestRide");
                return $this->jsonFeedback->request($aRecord, FeedbackConstants::SUCCESS_MESSAGE_RIDE_REQUEST);
            }
            
            $this->logging->exitMethod("requestRide");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        /**
         *  Creates new passenger request
         * 
         * @param type $param
         * @return JSONString
         */
        private function requestTravelBuddy($param) {
            $this->logging->startMethod("requestTravelBuddy");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aBusinessDataValidator = new BusinessDataValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateAddNewRideRequest($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("requestTravelBuddy");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aBusinessValidation = $aBusinessDataValidator->validateContactAvailable($param);
            if(!$aBusinessValidation[status]) {
                $this->logging->exitMethod("requestRide");
                return $this->jsonFeedback->feedback($aBusinessValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->addNewRideRequest($param);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                $this->logging->exitMethod("requestTravelBuddy");
                return $this->jsonFeedback->request($aRecord, FeedbackConstants::SUCCESS_MESSAGE_PASSENGER_REQUEST);
            }
            
            $this->logging->exitMethod("requestTravelBuddy");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves ride requests
         * 
         * @param type $param
         * @return JSONString
         */
        private function acceptTravelBuddyOffer($param) {
            $this->logging->startMethod("acceptTravelBuddyOffer");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateAcceptRideRequests($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("acceptTravelBuddyOffer");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessValidation = $aBusinessDataValidator->validateContactAvailable($param);
            if(!$aBusinessValidation[status]) {
                $this->logging->exitMethod("acceptTravelBuddyOffer");
                return $this->jsonFeedback->feedback($aBusinessValidation[message], FeedbackConstants::FAILED);
            }
            
            $aAcceptingUserResults = $aUserDAO->findUserByUserId($param->userId);
            if(!$aAcceptingUserResults[status]) {
                $this->logging->exitMethod("acceptTravelBuddyOffer");
                return $this->jsonFeedback->feedback($aAcceptingUserResults[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->addNewTrip($param);
            if($aResults[status]) {
                $aNotifyUserRecord = $aResults[record];
                $aAcceptingUserRecord = $aAcceptingUserResults[record];
                
                /**
                 * Sends notification
                 */
                $aRequestId = $aNotifyUserRecord[request_id];
                $aPassangerName = $aAcceptingUserRecord[firstname]." ".$aAcceptingUserRecord[lastname];
                
                $aMessage = NotificationUtils::getNotification(FeedbackConstants::ANDROID_ACTIVITY_SCHEDULED_RIDE, FeedbackConstants::ANDROID_ACTION_OPEN_TRIP, sprintf(FeedbackConstants::NOTIFICATION_MESSAGE_TRAVEL_BUDDY_ACCEPTED, $aPassangerName), $aRequestId);

                $aFirebase = new Firebase();
                $aPush = new Push();
                $aPayload = array();
                $aPayload['team'] = 'India';
                $aPayload['score'] = '5.6';
                
                $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                $aPush->setMessage($aMessage);
                $aPush->setImage("");
                $aPush->setIsBackground(TRUE);
                $aPush->setPayload($aPayload);
                
                $aResponse = $aFirebase->send($aNotifyUserRecord[firebase_id], $aPush->getPush());
                
                $this->logging->debug("Firebase response", $aResponse);
                $this->logging->debug("Firebase id", $aNotifyUserRecord[firebase_id]);
                
                $this->logging->exitMethod("acceptTravelBuddyOffer");
                return $this->jsonFeedback->feedback(FeedbackConstants::SUCCESS_MESSAGE_ACCEPT_TRAVEL_BUDDY_REQUEST, FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("acceptTravelBuddyOffer");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
         /**
         *  Add new track for a trip
         * 
         * @param type $param
         * @return JSONString
         */
        private function addNewTrackUpdateForTrip($param) {
            $this->logging->startMethod("addNewTrackUpdateForTrip");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            $aPHPToJSONArray = new PHPToJSONArray();
            
            $aPassengerTripResults = $aRideDAO->findTripInProgressForPassengerId($param->userId);
            if($aPassengerTripResults[status]) {
                $aPassengerTripRecord = $aPassengerTripResults[record];
                $aLastTrackResults = $aRideDAO->findTrackRecordByRequestId($aPassengerTripRecord[request_id]);
                if($aLastTrackResults[status]) {
                    $aLastTrackRecord = $aLastTrackResults[record];
                    $aMeters = GeneralUtils::distance($param->driverLatitude, $param->driverLongitude, $aLastTrackRecord[driver_latitude], $aLastTrackRecord[driver_longitude], "K") * 1000;
                    
                    $this->logging->debug("Meters",$aMeters);
                    
                    if($aMeters <= 10 && $aPassengerTripRecord[pickup] == 0) {
                        $aUpdateResults = $aRideDAO->updateTripPickupByRequestId("1", $aPassengerTripRecord[request_id]);
                        if($aUpdateResults[status]) {
                            $aDriverResults = $aUserDAO->findUserByUserId($aPassengerTripRecord[driver_id]);
                            if($aDriverResults[status]) {
                                $aDriverRecord = $aDriverResults[record];
                                $aRequestId = $aPassengerTripRecord[request_id];

                                $aMessage = NotificationUtils::getNotification(FeedbackConstants::ANDROID_ACTIVITY_SCHEDULED_RIDE, FeedbackConstants::ANDROID_ACTION_OPEN_TRIP_NAVIGATE, FeedbackConstants::NOTIFICATION_MESSAGE_TRIP_PICKUP, $aRequestId);

                                $aFirebase = new Firebase();
                                $aPush = new Push();
                                $aPayload = array();
                                $aPayload['team'] = 'India';
                                $aPayload['score'] = '5.6';

                                $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                                $aPush->setMessage($aMessage);
                                $aPush->setImage("");
                                $aPush->setIsBackground(TRUE);
                                $aPush->setPayload($aPayload);

                                $aResponse = $aFirebase->send($aDriverRecord[firebase_id], $aPush->getPush());

                                $this->logging->debug("Firebase response", $aResponse);
                                $this->logging->debug("Firebase id", $aDriverRecord[firebase_id]);

                                $this->logging->exitMethod("addNewTrackUpdateForTrip");
                                return $this->jsonFeedback->feedback("Pickup updated", FeedbackConstants::SUCCESSFUL);
                            }
                        }
                    }
                }
                $this->logging->exitMethodWithError("addNewTrackUpdateForTrip",$aLastTrackResults[message]);
                return $this->jsonFeedback->feedback($aLastTrackResults[message], FeedbackConstants::FAILED);
            }
            
            $aTripResults = $aRideDAO->findTripInProgressForDriverId($param->userId);
            if(!$aTripResults[status]) {
                $this->logging->exitMethod("addNewTrackUpdateForTrip");
                return $this->jsonFeedback->feedback($aTripResults[message], FeedbackConstants::FAILED);
            }
            $aTripRecord = $aTripResults[record];
            $aData =  $aPHPToJSONArray->newTrackForTrip($param->userId, $aTripRecord[driver_id], $aTripRecord[passenger_id], $aTripRecord[request_id], $param->driverLatitude, $param->driverLongitude, "No estimate");
            
            $this->logging->debugObject("Param",$aData);
            
            $aValidation = $aInputValidator->validateAddNewTrackUpdateForTrip($aData);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("addNewTrackUpdateForTrip");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->addTrackForTrip($aData);
            if($aResults[status]) {
                $this->logging->exitMethod("addNewTrackUpdateForTrip");
                return $this->jsonFeedback->feedback("Track added", FeedbackConstants::SUCCESSFUL);
            }
            $this->logging->exitMethod("addNewTrackUpdateForTrip");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves ride requests
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveLatestTrackForTrip($param) {
            $this->logging->startMethod("retrieveLatestTrackForTrip");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aValidation = $aInputValidator->validateRetrieveTracks($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveLatestTrackForTrip");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTrackRecordByRequestId($param->requestId);
            if($aResults[status]) {
                $this->logging->exitMethod("retrieveLatestTrackForTrip");
                return $this->jsonFeedback->track($aResults[record],"Track");
            }
            
            $this->logging->exitMethod("retrieveLatestTrackForTrip");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        
        /**
         *  Accepts ride request
         * 
         * @param type $param
         * @return JSONString
         */
        private function acceptRideOffer($param) {
            $this->logging->startMethod("acceptRideOffer");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateAcceptRideRequests($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("acceptRideOffer");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessValidation = $aBusinessDataValidator->validateContactAvailable($param);
            if(!$aBusinessValidation[status]) {
                $this->logging->exitMethod("acceptRideOffer");
                return $this->jsonFeedback->feedback($aBusinessValidation[message], FeedbackConstants::FAILED);
            }
            
            $aAcceptingUserResults = $aUserDAO->findUserByUserId($param->userId);
            if(!$aAcceptingUserResults[status]) {
                $this->logging->exitMethod("acceptRideOffer");
                return $this->jsonFeedback->feedback($aAcceptingUserResults[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->addNewTrip($param);
            if($aResults[status]) {
                $aNotifyUserRecord = $aResults[record];
                $aAcceptingUserRecord = $aAcceptingUserResults[record];
                
                /**
                 * Sends notification
                 */
                $aRequestId = $aNotifyUserRecord[request_id];
                $aPassangerName = $aAcceptingUserRecord[firstname]." ".$aAcceptingUserRecord[lastname];
                
                $aMessage = NotificationUtils::getNotification(FeedbackConstants::ANDROID_ACTIVITY_SCHEDULED_RIDE,FeedbackConstants::ANDROID_ACTION_OPEN_TRIP, sprintf(FeedbackConstants::NOTIFICATION_MESSAGE_RIDE_ACCEPTED, $aPassangerName), $aRequestId);

                $aFirebase = new Firebase();
                $aPush = new Push();
                $aPayload = array();
                $aPayload['team'] = 'India';
                $aPayload['score'] = '5.6';
                
                $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                $aPush->setMessage($aMessage);
                $aPush->setImage("");
                $aPush->setIsBackground(TRUE);
                $aPush->setPayload($aPayload);
                
                $aResponse = $aFirebase->send($aNotifyUserRecord[firebase_id], $aPush->getPush());
                
                $this->logging->debug("Firebase response", $aResponse);
                $this->logging->debug("Firebase id", $aNotifyUserRecord[firebase_id]);
                
                $this->logging->exitMethod("acceptRideOffer");
                return $this->jsonFeedback->feedback(FeedbackConstants::SUCCESS_MESSAGE_ACCEPT_RIDE_REQUEST, FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("acceptRideOffer");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves ride requests
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveRides($param) {
            $this->logging->startMethod("retrieveRides");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveRideRequests($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveRides");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findRideRequestsExcludingUserId($param);
            if($aResults[status]) {
                $aRecords = $aResults[records];
                $this->logging->exitMethod("retrieveRides");
                return $this->jsonFeedback->rideRequests($aRecords, "Ride requests");
            }
            $this->logging->exitMethod("retrieveRides");
            return $this->jsonFeedback->feedback("It's lonely here", FeedbackConstants::FAILED);
        }
        
        /**
         *  Retrieves request
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveRideRequest($param) {
            $this->logging->startMethod("retrieveRideRequest");
            $this->logging->debugObject("Param",$param);
            
            $aRideDAO = new RideDAO();
            $aResults = $aRideDAO->findRideRequestByRequestId($param->requestId);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                $this->logging->exitMethod("retrieveRideRequest");
                return $this->jsonFeedback->request($aRecord, "Request record");
            }
            $this->logging->exitMethod("retrieveRideRequest");
            return $this->jsonFeedback->feedback("Trip was cancelled", FeedbackConstants::FAILED);
        }

        /**
         *  Retrieves trip
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveConfirmedRide($param) {
            $this->logging->startMethod("retrieveConfirmedRide");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrip($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveConfirmedRide");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTripByRequestId($param->tripId);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                $this->logging->exitMethod("retrieveConfirmedRide");
                return $this->jsonFeedback->trip($aRecord, "Trip record");
            }
            $this->logging->exitMethod("retrieveConfirmedRide");
            return $this->jsonFeedback->feedback("Trip was cancelled", FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves trip in progress for user
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveTripInProgress($param) {
            $this->logging->startMethod("retrieveTripInProgress");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrips($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveTripInProgress");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTripInProgressForUserId($param->userId);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                $this->logging->exitMethod("retrieveTripInProgress");
                return $this->jsonFeedback->trip($aRecord, "Trip record");
            }
            
            $this->logging->exitMethod("retrieveTripInProgress");
            return $this->jsonFeedback->feedback("You have no running trip", FeedbackConstants::FAILED);
            
        }
        /**
         *  Retrieves trips for user
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveConfirmedRides($param) {
            $this->logging->startMethod("retrieveConfirmedRides");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrips($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveConfirmedRides");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTripsForUserId($param->userId);
            if($aResults[status]) {
                $aRecords = $aResults[records];
                $this->logging->exitMethod("retrieveConfirmedRides");
                return $this->jsonFeedback->trips($aRecords, "Trip records");
            }
            $this->logging->exitMethod("retrieveConfirmedRides");
            return $this->jsonFeedback->feedback("You have no trips scheduled", FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves trip history for user
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveTripHistory($param) {
            $this->logging->startMethod("retrieveTripHistory");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrips($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveTripHistory");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findOldTripsForUserId($param->userId);
            if($aResults[status]) {
                $aRecords = $aResults[records];
                $this->logging->exitMethod("retrieveTripHistory");
                return $this->jsonFeedback->trips($aRecords, "Trip records");
            }
            $this->logging->exitMethod("retrieveTripHistory");
            return $this->jsonFeedback->feedback("You have no ride history", FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves trips for user
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrievePendingRideRequests($param) {
            $this->logging->startMethod("retrievePendingRideRequests");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrips($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrievePendingRideRequests");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findRideRequestsByUserId($param->userId);
            if($aResults[status]) {
                $aRecords = $aResults[records];
                $this->logging->exitMethod("retrievePendingRideRequests");
                return $this->jsonFeedback->rideRequests($aRecords, "Pending ride requests");
            }
            $this->logging->exitMethod("retrievePendingRideRequests");
            return $this->jsonFeedback->feedback("You have no pending requests", FeedbackConstants::FAILED);
        }
        /**
         *  Retrieves passenger requests
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveTravelBuddies($param) {
            $this->logging->startMethod("retrieveTravelBuddies");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateRetrieveRideRequests($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("retrieveTravelBuddies");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findRideRequestsExcludingUserId($param);
            if($aResults[status]) {
                $aRecords = $aResults[records];
                $this->logging->exitMethod("retrieveTravelBuddies");
                return $this->jsonFeedback->rideRequests($aRecords, "Travel buddy requests");
            }
            
            $this->logging->exitMethod("retrieveTravelBuddies");
            return $this->jsonFeedback->feedback("It's lonely here", FeedbackConstants::FAILED);
        }
        /**
         *  Cancel ride requests
         * 
         * @param type $param
         * @return JSONString
         */
        private function cancelRideRequest($param) {
            $this->logging->startMethod("cancelRideRequest");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            
            $aValidation = $aInputValidator->validateCancelRequest($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("cancelRideRequest");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aUpdateResults = $aRideDAO->deleteRideRequestByRequestId($param->requestId);
            if(!$aUpdateResults) {
                $this->logging->exitMethod("startTrip");
                return $this->jsonFeedback->feedback("Service failed to cancel request", FeedbackConstants::FAILED);
            }
            
            return $this->jsonFeedback->feedback("Cancelled", FeedbackConstants::SUCCESSFUL);
        }
         /**
         *  Complete trip
         * 
         * @param type $param
         * @return JSONString
         */
        private function completeTrip($param) {
            $this->logging->startMethod("completeTrip");
            
            $aReturn = $this->dropOff($param);
            
            $this->logging->exitMethod("completeTrip");
            return $aReturn;
        }
        /**
         *  Drop off
         * 
         * @param type $param
         * @return JSONString
         */
        private function dropOff($param) {
            $this->logging->startMethod("dropOff");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrip($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("dropOff");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aTripResults = $aRideDAO->findTripByRequestId($param->tripId);
            if($aTripResults[status]) {
                $aRecord = $aTripResults[record];
                if($aRecord[status] == 2) {
                    $this->logging->exitMethod("dropOff");
                    return $this->jsonFeedback->feedback(FeedbackConstants::SUCCESS_MESSAGE_TRIP_ALREADY_DROPOFF, FeedbackConstants::FAILED);
                }
                
                $aResults = $aRideDAO->updateTripStatusByRequestId("2", $param->tripId);
                if($aResults[status]) {
                    $aPassengerResults = $aUserDAO->findUserByUserId($aRecord[passenger_id]);
                    $aDriverResults = $aUserDAO->findUserByUserId($aRecord[driver_id]);
                    if($aPassengerResults[status] && $aDriverResults[status]) {
                        $aPassengerRecord = $aPassengerResults[record];
                        $aDriverRecord = $aDriverResults[record];

                        /**
                        * Sends notification
                        */
                        if($param->userId === $aDriverRecord[user_id]) {
                            $aOtherUserRecord = $aPassengerRecord;
                            $aCancellingUserRecord = $aDriverRecord;
                        }else if($param->userId === $aPassengerRecord[user_id]) {
                            $aOtherUserRecord = $aDriverRecord;
                            $aCancellingUserRecord = $aPassengerRecord;
                        }

                        $aRequestId = $param->tripId;

                        $aMessage = NotificationUtils::getNotification(FeedbackConstants::ANDROID_ACTIVITY_SCHEDULED_RIDE, FeedbackConstants::ANDROID_ACTION_RATE_DRIVER, sprintf(FeedbackConstants::NOTIFICATION_MESSAGE_TRIP_DROPOFF, $aCancellingUserRecord[firstname]), $aRequestId);

                        $aFirebase = new Firebase();
                        $aPush = new Push();
                        $aPayload = array();
                        $aPayload['team'] = 'India';
                        $aPayload['score'] = '5.6';

                        $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                        $aPush->setMessage($aMessage);
                        $aPush->setImage("");
                        $aPush->setIsBackground(TRUE);
                        $aPush->setPayload($aPayload);

                        $aResponse = $aFirebase->send($aOtherUserRecord[firebase_id], $aPush->getPush());

                        $this->logging->debug("Firebase response", $aResponse);
                        $this->logging->debug("Firebase id", $aOtherUserRecord[firebase_id]);

                        $this->logging->exitMethod("dropOff");
                        return $this->jsonFeedback->feedback(FeedbackConstants::SUCCESS_MESSAGE_TRIP_DROPOFF, FeedbackConstants::SUCCESSFUL);     
                    } 
                } else {
                    $this->logging->exitMethod("dropOff");
                    return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::SUCCESSFUL);     
                }
            }
                
            $this->logging->exitMethod("dropOff");
            return $this->jsonFeedback->feedback($aTripResults[message], FeedbackConstants::SUCCESSFUL);     
        }
        /**
         *  Submits rating
         * 
         * @param type $param
         * @return JSONString
         */
        private function submitRating($param) {
            $this->logging->startMethod("submitRating");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateSubmitRating($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("submitRating");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aUserResults = $aUserDAO->findUserByUserId($param->rateUserId);
            if(!$aUserResults[status]) {
                 $this->logging->exitMethod("submitRating");
                return $this->jsonFeedback->feedback("User to rate is no longer avaiable", FeedbackConstants::FAILED);
            }
            
            $aRatingResults = $aRideDAO->addTripRating($param);
            if($aRatingResults[status]) {
                $this->logging->exitMethod("submitRating");
                return $this->jsonFeedback->feedback("Rating added", FeedbackConstants::SUCCESSFUL);        
            }
            
            $this->logging->exitMethod("submitRating");
            return $this->jsonFeedback->feedback("We failed to update our system.", FeedbackConstants::FAILED);        
        }
        
        private function sendChat($param) {
            $this->logging->startMethod("sendChat");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateTripChat($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("sendChat");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTripByRequestId($param->tripId);
            if($aResults[status]) {
                $aTripRecord = $aResults[record];
                $aDriverId = $aTripRecord[driver_id];
                $aPassengerId = $aTripRecord[passenger_id];
                
                $aNotifyUserId = $aDriverId;
                if($aDriverId == $param->userId) {
                    $aNotifyUserId = $aPassengerId;
                } elseif($aPassengerId == $param->userId) {
                    $aNotifyUserId = $aDriverId;
                }
                
                $aNotityUserResults = $aUserDAO->findUserByUserId($aNotifyUserId);
                $aCallingUserResults = $aUserDAO->findUserByUserId($param->userId);
                if($aNotityUserResults[status] && $aCallingUserResults[status]) {
                    $aOtherUserRecord = $aNotityUserResults[record];
                    $aCallingUserRecord = $aCallingUserResults[record];
                    $aRequestId = $param->tripId;

                    $aMessage = NotificationUtils::getChatNotification(FeedbackConstants::ANDROID_ACTIVITY_CHAT, FeedbackConstants::ANDROID_ACTION_PENDING_REQUESTS, sprintf(FeedbackConstants::NOTIFICATION_MESSAGE_CHAT_MESSAGE, $aCallingUserRecord[firstname]), $param->message, $aRequestId);

                    $aFirebase = new Firebase();
                    $aPush = new Push();
                    $aPayload = array();
                    $aPayload['team'] = 'India';
                    $aPayload['score'] = '5.6';

                    $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                    $aPush->setMessage($aMessage);
                    $aPush->setImage("");
                    $aPush->setIsBackground(TRUE);
                    $aPush->setPayload($aPayload);
                    
                    $aResponse = $aFirebase->send($aOtherUserRecord[firebase_id], $aPush->getPush());
                    
                    $this->logging->debug("Firebase response", $aResponse);
                    $this->logging->debug("Firebase id", $aOtherUserRecord[firebase_id]);
                    $this->logging->exitMethod("sendChat");
                    return $this->jsonFeedback->feedback("Message sent", FeedbackConstants::SUCCESSFUL);
                }
                $this->logging->exitMethod("sendChat");
                return $this->jsonFeedback->feedback($aNotityUserResults[message], FeedbackConstants::FAILED);
            }
            $this->logging->exitMethod("sendChat");
            return $this->jsonFeedback->feedback("Trip was cancelled", FeedbackConstants::FAILED);  
        }

        /**
         *  Cancel trip
         * 
         * @param type $param
         * @return JSONString
         */
        private function cancelTrip($param) {
            $this->logging->startMethod("cancelTrip");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrip($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("cancelTrip");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTripByRequestId($param->tripId);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                
                $aUpdateResults = $aRideDAO->deleteTripByRequestId($param->tripId);
                if(!$aUpdateResults) {
                    $this->logging->exitMethod("startTrip");
                    return $this->jsonFeedback->feedback("Service failed to cancel trip", FeedbackConstants::FAILED);
                }
                
                $aPassengerResults = $aUserDAO->findUserByUserId($aRecord[passenger_id]);
                $aDriverResults = $aUserDAO->findUserByUserId($aRecord[driver_id]);
                if($aPassengerResults[status] && $aDriverResults[status]) {
                    $aPassengerRecord = $aPassengerResults[record];
                    $aDriverRecord = $aDriverResults[record];
                    
                    /**
                    * Sends notification
                    */
                    if($param->userId === $aDriverRecord[user_id]) {
                        $aOtherUserRecord = $aPassengerRecord;
                        $aCancellingUserRecord = $aDriverRecord;
                    }else if($param->userId === $aPassengerRecord[user_id]) {
                        $aOtherUserRecord = $aDriverRecord;
                        $aCancellingUserRecord = $aPassengerRecord;
                    }
                    
                    $aRequestId = $param->tripId;
                    $aOtherUserName = $aOtherUserRecord[firstname]." ".$aOtherUserRecord[lastname];

                    $aMessage = NotificationUtils::getNotification(FeedbackConstants::ANDROID_ACTIVITY_PENDING_RIDE_REQUESTS, FeedbackConstants::ANDROID_ACTION_PENDING_REQUESTS, sprintf(FeedbackConstants::NOTIFICATION_MESSAGE_TRIP_CANCELLED, $aCancellingUserRecord[firstname]), $aRequestId);

                    $aFirebase = new Firebase();
                    $aPush = new Push();
                    $aPayload = array();
                    $aPayload['team'] = 'India';
                    $aPayload['score'] = '5.6';

                    $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                    $aPush->setMessage($aMessage);
                    $aPush->setImage("");
                    $aPush->setIsBackground(TRUE);
                    $aPush->setPayload($aPayload);
                    
                    $aResponse = $aFirebase->send($aOtherUserRecord[firebase_id], $aPush->getPush());
                    
                    $this->logging->debug("Firebase response", $aResponse);
                    $this->logging->debug("Firebase id", $aOtherUserRecord[firebase_id]);
                    
                    $this->logging->exitMethod("startTrip");
                    return $this->jsonFeedback->feedback(sprintf(FeedbackConstants::SUCCESS_MESSAGE_TRIP_CANCELLED,$aOtherUserName), FeedbackConstants::SUCCESSFUL);
                } else {
                    $this->logging->exitMethod("startTrip");
                    return $this->jsonFeedback->feedback("Service failed to send notifcation", FeedbackConstants::FAILED);
                }
            }
            
            $this->logging->exitMethod("startTrip");
            return $this->jsonFeedback->feedback("Trip already cancelled", FeedbackConstants::FAILED);        
        }
        /**
         *  Starts trip
         * 
         * @param type $param
         * @return JSONString
         */
        private function startTrip($param) {
            $this->logging->startMethod("startTrip");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateRetrieveTrip($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("startTrip");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aRideDAO->findTripByRequestId($param->tripId);
            if($aResults[status]) {
                $aRecord = $aResults[record];
                
                $aUpdateResults = $aRideDAO->updateTripStatusByRequestId("1", $param->tripId);
                if(!$aUpdateResults) {
                    $this->logging->exitMethod("startTrip");
                    return $this->jsonFeedback->feedback("Service failed to start trip", FeedbackConstants::FAILED);
                }
                
                $aPassengerResults = $aUserDAO->findUserByUserId($aRecord[passenger_id]);
                $aDriverResults = $aUserDAO->findUserByUserId($aRecord[driver_id]);
                if($aPassengerResults[status] && $aDriverResults[status]) {
                    $aPassengerRecord = $aPassengerResults[record];
                    $aDriverRecord = $aDriverResults[record];
                    
                    /**
                    * Sends notification
                    */
                    $aRequestId = $param->tripId;
                    $aDriverName = $aDriverRecord[firstname]." ".$aDriverRecord[lastname];

                    $aMessage = NotificationUtils::getNotification(FeedbackConstants::ANDROID_ACTIVITY_SCHEDULED_RIDE, FeedbackConstants::ANDROID_ACTION_CHECK_PROGRESS, sprintf(FeedbackConstants::NOTIFICATION_MESSAGE_TRIP_STARTED, $aDriverName), $aRequestId);

                    $aFirebase = new Firebase();
                    $aPush = new Push();
                    $aPayload = array();
                    $aPayload['team'] = 'India';
                    $aPayload['score'] = '5.6';

                    $aPush->setTitle(FeedbackConstants::NOTIFICATION_TITLE_RIDE_ACCEPTED);
                    $aPush->setMessage($aMessage);
                    $aPush->setImage("");
                    $aPush->setIsBackground(TRUE);
                    $aPush->setPayload($aPayload);
                    
                    if($aRecord[trip_status] == "1") {
                        $this->logging->exitMethod("startTrip");
                        return $this->jsonFeedback->trip($aRecord, sprintf(FeedbackConstants::SUCCESS_MESSAGE_TRIP_ALREADY_STARTED, $aPassengerRecord[firstname]));
                    }

                    $aResponse = $aFirebase->send($aPassengerRecord[firebase_id], $aPush->getPush());
                    
                    $this->logging->debug("Firebase response", $aResponse);
                    $this->logging->debug("Firebase id", $aPassengerRecord[firebase_id]);
                    
                    $this->logging->exitMethod("startTrip");
                    return $this->jsonFeedback->trip($aRecord, sprintf(FeedbackConstants::SUCCESS_MESSAGE_TRIP_STARTED, $aPassengerRecord[firstname]));
                } else {
                    $this->logging->exitMethod("startTrip");
                    return $this->jsonFeedback->feedback("Service failed to send notification", FeedbackConstants::FAILED);
                }
            }
            $this->logging->exitMethod("startTrip");
            return $this->jsonFeedback->feedback("Trip was cancelled", FeedbackConstants::FAILED);
        }
        /**
         * 
         * @param type $param
         * @return type
         */
        private function submitEmergencyNumbers($param) {
            $this->logging->startMethod("submitEmergencyNumbers");
            $this->logging->debugObject("Param",$param);
            
            $aUserDAO = new UserDAO();
            $aResults = $aUserDAO->addEmergencyContacts($param);
            if($aResults[status]) {
                return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::SUCCESSFUL);
            }
            $this->logging->exitMethod("submitEmergencyNumbers");
            return $this->jsonFeedback->feedback("Emergency contact update failed", FeedbackConstants::FAILED);
        }
        /**
         * Generates and sends a digit code
         * 
         * @param type $param
         * @return JSONString
         */
        private function sendOneTimePIN($param) {
            $this->logging->startMethod("sendOneTimePIN");
            $this->logging->debugObject("Param",$param);
            
            $aPHPToJSONArray = new PHPToJSONArray();
            $aInputValidator = new InputValidator();
            $aUserDAO = new UserDAO();
            
            $aOneTimePin = GeneralUtils::generateOneTimePin();
            $data = $aPHPToJSONArray->newDigitCodeRequest($param->phoneNumber, $aOneTimePin);
            
            $aValidation = $aInputValidator->validateAddNewDigitCode($data);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("sendOneTimePIN");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aUserDAO->addNewDigitCode($data);
            if($aResults[status]) {
                /**
                 * Send SMS call
                 */
                //$aResponse = SMSUtils::sendSMS($param->phoneNumber, sprintf(FeedbackConstants::SMS_KUTOA_DIGIT_CODE, $aOneTimePin));
                
                //$this->logging->debug("SMS code status for ".$param->phoneNumber," ",$aResponse);
                
                $this->logging->exitMethod("sendOneTimePIN");
                return $this->jsonFeedback->feedback($aOneTimePin, FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("sendOneTimePIN");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        /**
        * Resends a digit code
        * 
        * @param type $param
        * @return JSONString
        */
        private function resendOneTimePIN($param) {
            $this->logging->startMethod("resendOneTimePIN");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateResendDigitCode($param);
            if(!$aValidation[status]) {
                $this->logging->exitMethod("resendOneTimePIN");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            $aResults = $aUserDAO->findPreviousDigitCodeByPhoneNumber($param->phoneNumber);
            if($aResults[status]) {
                $aOneTimePin = $aResults[record][digit_code];
                /**
                 * Send SMS call
                 */
                $aResponse = SMSUtils::sendSMS($param->phoneNumber, sprintf(FeedbackConstants::SMS_KUTOA_DIGIT_CODE, $aOneTimePin));
                
                $this->logging->debug("SMS code status for ".$param->phoneNumber," ",$aResponse);
                
                $this->logging->exitMethod("resendOneTimePIN");
                return $this->jsonFeedback->feedback($aOneTimePin, FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("resendOneTimePIN");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
        /**
        * Verifies phone number and digit code
        * 
        * @param type $param
        * @return JSONString
        */
        private function verifyDigitCode($param) {
            $this->logging->startMethod("verifyDigitCode");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateAddNewDigitCode($param);
             if(!$aValidation[status]) {
                $this->logging->exitMethod("verifyDigitCode");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            $aResults = $aUserDAO->findPreviousDigitCodeByPhoneNumber($param->phoneNumber);
            if($aResults[status]) {
                $aOneTimePin = $aResults[record][digit_code];
                if($aOneTimePin === trim($param->digitCode) || true) {
                    $this->logging->exitMethod("verifyDigitCode");
                    return $this->jsonFeedback->feedback("Digit code is invalid or expired", FeedbackConstants::SUCCESSFUL);
                }
            }
            $this->logging->exitMethod("verifyDigitCode");
            return $this->jsonFeedback->feedback("Digit code is invalid or expired", FeedbackConstants::FAILED);
        }
        /**
        * Adds new user to system
        * 
        * @param type $param
        * @return JSONString
        */
        private function registerUser($param) { 
            $this->logging->startMethod("registerUser");
            $this->logging->debugObject("Param",$param);
            
            $aInputValidator = new InputValidator();
            $aUserDAO = new UserDAO();
            
            $aValidation = $aInputValidator->validateAddUser($param);
             if(!$aValidation[status]) {
                $this->logging->exitMethod("registerUser");
                return $this->jsonFeedback->feedback($aValidation[message], FeedbackConstants::FAILED);
            }
            
            /**
             * Check if Login
             */
            $aLoginResults = $aUserDAO->findUserByPhoneNumberFacebookIdAndFirebaseId($param);
            if($aLoginResults[status]) {
                $aUserId = $aLoginResults[record][user_id];
                $aUserUpdateResults = $aUserDAO->updateUserByUserId($aUserId, $param);
                if($aUserUpdateResults[status]) {
                    $this->logging->exitMethod("registerUser");
                    return $this->jsonFeedback->feedback($aUserId, FeedbackConstants::SUCCESSFUL);
                }
                $this->logging->exitMethod("registerUser");
                return $this->jsonFeedback->feedback("Service could not complete setup", FeedbackConstants::FAILED);
            }
            
            $aResults = $aUserDAO->addNewUser($param);
            if($aResults[status]) {
                $aUserId = $aResults[record][user_id];
                $this->logging->exitMethod("registerUser");
                return $this->jsonFeedback->feedback($aUserId, FeedbackConstants::SUCCESSFUL);
            }
            
            $this->logging->exitMethod("registerUser");
            return $this->jsonFeedback->feedback($aResults[message], FeedbackConstants::FAILED);
        }
       /**
        * Delete user from system
        * 
        * @param type $param
        * @return JSONString
        */
        private function deregisterUser($param) {
            $this->logging->startMethod("deregisterUser");
            $this->logging->debugObject("Param",$param);
            
            $aRideDAO = new RideDAO();
            $aUserDAO = new UserDAO();
            
            $aRideResults = $aRideDAO->findLatestRequestForUserId($param->userId);
            if($aRideResults[status]) {
                $this->logging->exitMethod("deregisterUser");
                return $this->jsonFeedback->feedback("You must cancel your requests before deleting account", FeedbackConstants::FAILED);
            }
            
            $aTripResults = $aRideDAO->findTripsForUserId($param->userId);
            if($aTripResults[status]) {
                $this->logging->exitMethod("deregisterUser");
                return $this->jsonFeedback->feedback("You must cancel or complete your trips before deleting account", FeedbackConstants::FAILED);
            }
            
            $aDeleteResults = $aUserDAO->deleteUserByUserId($param->userId);
            if($aDeleteResults[status]) {
                $this->logging->exitMethod("deregisterUser");
                return $this->jsonFeedback->feedback("Account has been deleted", FeedbackConstants::SUCCESSFUL);
            }
            $this->logging->exitMethod("deregisterUser");
            return $this->jsonFeedback->feedback("Service failed to delete account", FeedbackConstants::FAILED);
        }
        /**
         * 
         * @param type $param
         * @return JSONString
         */
        private function retrieveUserProfile($param) {
            $this->logging->startMethod("retrieveUserProfile");
            $this->logging->debugObject("Param",$param);
            
            $aUserDAO = new UserDAO();
            $aUserResults = $aUserDAO->findUserByUserId($param->profileId);
            if($aUserResults[status]) {
                $this->logging->exitMethod("retrieveUserProfile");
                return $this->jsonFeedback->user($aUserResults[record],"user profile");
            }
            $this->logging->exitMethod("retrieveUserProfile");
            return $this->jsonFeedback->feedback("Service could not retrieve user profile", FeedbackConstants::FAILED);
        }
    }
