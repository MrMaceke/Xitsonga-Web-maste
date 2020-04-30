<?php
    require_once __DIR__.'/../dao/SystemPropertyDAO.php';
    /**
     * Logging
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class Logging {
        private $mLogFile   = "../logging/syslogs.txt";
        private $mLogFlag   = false;
        
        const mMinor        = 3;
        
        private $mClassName = NULL;
        
        public function Logging($pClassName){
            $this->mClassName = $pClassName;
            
            if(!isset($_SESSION["system_log"])){
                $_SESSION["system_log"] = $this->mLogFlag;
                
                $aSystemPropertyDAO = new SystemPropertyDAO();

                $aLogFlagResults = $aSystemPropertyDAO->findRecordByPropertyName("Logs");
                $aLogStatus = strtolower($aLogFlagResults['resultsArray']['property_description']);

                if($aLogStatus == "on"){
                   $this->mLogFlag  = true;
                }else if($aLogStatus == "off"){
                   $this->mLogFlag  = false;
                }
            } else{
                $this->mLogFlag  = $_SESSION["system_log"];
            }            
        }
        
        public function startMethod($pMethodName) {
            $aLogMessage = $this->mClassName."::".$pMethodName." - start\n";            
            $this->writeToFile($aLogMessage);           
        }
        
        public function exitMethod($pMethodName) {
            $aLogMessage = $this->mClassName."::".$pMethodName." - end\n";            
            $this->writeToFile($aLogMessage);           
        }
        
        public function exitMethodWithError($pMethodName, $pCustomMessage) {
            $aLogMessage = $this->mClassName."::".$pMethodName." - end. ". $pCustomMessage. "\n";           
            $this->writeToFile($aLogMessage);           
        }
        
        public function debug($pMethodName,$pCustomMessage) {
            $aLogMessage = $this->mClassName."::".$pMethodName." ". $pCustomMessage. "\n";           
            $this->writeToFile($aLogMessage);
        }
        
        public function debugObject($pName, $pMessage) {
            $aLogMessage = $pName."\n{\n\t".json_encode($pMessage) ."\n}\n";
            $this->writeToFile($aLogMessage, false);
        }
        
        public function debugBoolean($pName, $pMessage) {
            $aMessage = $pMessage? "true":"false";
            $aLogMessage = $pName."\n{\n\t".$aMessage ."\n}\n";
            $this->writeToFile($aLogMessage, false);
        }
        
         public function debugPHPObject($pName, $pObject) {
            $aLogMessage = $pName."\n{\n\t".$pObject->toString() ."\n}\n";
            $this->writeToFile($aLogMessage, false);
        }
        
        public function error($pMethodName, $pCustomMessage) {
            $aLogMessage = $this->mClassName."::".$pMethodName." ". $pCustomMessage. "\n";           
            $this->writeToFile($aLogMessage);
        }
        
        public function setLogFilePath($param) {
            $this->mLogFile  = $param;
        }
        
        public function clearLogs() {
            file_put_contents($this->mLogFile, "");
        }
        
        private function writeToFile($pLogMessage, $pTime = true){
            date_default_timezone_set('Africa/Johannesburg');
            if($this->mLogFlag) {
                if($pTime){
                    $pLogMessage = (date("Y-m-d H:m:s",time()))." ".$pLogMessage;
                }
                
                $current = file_get_contents($this->mLogFile);
                $current = $current.$pLogMessage;
                file_put_contents($this->mLogFile, $current);
            }
        }
    }
