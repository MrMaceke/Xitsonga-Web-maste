<?php
    if(!isset($aPHPFolder) || $aPHPFolder == null){
        require_once __DIR__. '/../dto/DTOSystemUser.php';
    }else {
        require_once "$aPHPFolder/dto/DTOSystemUser.php";
    }
    /**
     * Access Validator
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class AccessValidator {
        const NO_SESSION    = -1;
        const BASIC         = 0;
        const CLIENT        = 1;
        const BUSINESS      = 2;
        const DEVELOPER     = 3;
        const ADMIN         = 4;
        const ALL_EXPECT_CLIENT = 5;
        const ALL_EXPECT_DEVELOPER_CLIENT = 6;
        
        private $systemUser;
        private $pages;
        private $meta;
        
        public function AccessValidator() {
            $this->initUserSession();
            $this->pages = array ();
            
            $this->pages["index"] = $this->isAccessGranted(self::NO_SESSION);
            $this->pages["internal"] = $this->isAccessGranted(self::ALL_EXPECT_CLIENT);
            $this->pages["dashboard"] = $this->isAccessGranted(self::BASIC);
            $this->pages["resources"] = $this->isAccessGranted(self::BASIC);
            $this->pages["account"] = $this->isAccessGranted(self::BASIC);
            $this->pages["options"] = $this->isAccessGranted(self::BASIC);
            $this->pages["groups"] = $this->isAccessGranted(self::ADMIN);
            $this->pages["properties"] = $this->isAccessGranted(self::ADMIN);
            $this->pages["users"] = $this->isAccessGranted(self::ADMIN);
            $this->pages["clients"] = $this->isAccessGranted(self::ALL_EXPECT_DEVELOPER_CLIENT);
            $this->pages["client"] = $this->isAccessGranted(self::BASIC);
            $this->pages["tasks"] = $this->isAccessGranted(self::BASIC);
            $this->pages["projects"] = $this->isAccessGranted(self::BASIC);
            $this->pages["tickets"] = $this->isAccessGranted(self::BASIC);
            $this->pages["invoices"] = $this->isAccessGranted(self::BASIC);
            $this->pages["project"] = $this->isAccessGranted(self::BASIC);
            $this->pages["timeline"] = $this->isAccessGranted(self::BASIC);
            
            $this->pages["serverlinks"] = $this->isAccessGranted(self::BASIC);
        }
        /**
         * Checks if current user has access
         * 
         * @param String $aPageName
         * @return boolean
         */
        public function hasAccess($aPageName) {
            return $this->pages[$aPageName];
        }
        /**
         * Starts PHP session
         * 
         * @return boolean
         */
        public function startSession() {
            if (!isset($_SESSION['running'])){
            	$some_name = session_name("some_name");
                session_set_cookie_params(0, '/', '.waxbill.co.za');
		session_start();
                $_SESSION['running'] = true;
            }
            return true;
        }
        /**
         * Initialises PHP sesssion
         * 
         * @return DTOUser - Running User Session
         */
        public function initUserSession() {
            $this->startSession();
            
            $this->systemUser = unserialize($_SESSION['SystemUserSession']);
            if($this->systemUser == NULL){
                $this->systemUser = new DTOSystemUser();
            }
        }
        /**
         * Detemines the access level
         * 
         * @param Integer $aLevel
         * @return boolean
         */
        private function isAccessGranted($aLevel){ 
            $aReturn = false;
            if($aLevel == self::NO_SESSION){
                $aReturn = $this->systemUser->isActiveSession()? false : true;
            }elseif($aLevel == self::BASIC){
                $aReturn = $this->systemUser->isActiveSession()? true : false;
            }elseif($aLevel == self::CLIENT){
                $aReturn = $this->systemUser->getAccessLevel() == self::CLIENT? true : false;
            }elseif($aLevel == self::DEVELOPER){
                $aReturn = $this->systemUser->getAccessLevel() == self::DEVELOPER? true : false;
            }elseif($aLevel == self::BUSINESS){
                $aReturn = $this->systemUser->getAccessLevel() == self::BUSINESS? true : false;
            }elseif($aLevel == self::ADMIN){
                $aReturn = $this->systemUser->getAccessLevel() == self::ADMIN? true : false;
            }elseif($aLevel == self::ALL_EXPECT_CLIENT){
                $aReturn = ($this->systemUser->getAccessLevel() == self::ADMIN 
                    || $this->systemUser->getAccessLevel() == self::BUSINESS 
                    || $this->systemUser->getAccessLevel() == self::DEVELOPER) ? true : false;
            }elseif($aLevel == self::ALL_EXPECT_DEVELOPER_CLIENT){
                $aReturn = $this->systemUser->getAccessLevel() == self::ADMIN 
                    || $this->systemUser->getAccessLevel() == self::BUSINESS ? true : false;
            }
            return $aReturn;
        }
        /**
         * Returns current user DTO
         * 
         * @return DTOSystemUser
         */
        public function getSystemUser() {
            return $this->systemUser;
        }
    }
