<?php
    require_once __DIR__. '/utils/Logging.php';
    require_once __DIR__. '/dao/RideDAO.php';
    
    class WebBean {
        
        public $logging = null;
        
        public function WebBean() {}
        
        public function retrieveRideRequest($param) {
           $aRideDAO = new RideDAO();
           
           $aRideResults = $aRideDAO->findRideRequestByRequestId($param);
           if($aRideResults[status]) {
               return $aRideResults;
           }
           return array(status=> FALSE, message=>"Request has be cancelled or already accepted");
        }
    }