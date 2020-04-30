<?php
    require_once __DIR__. '/Sql.php';
    require_once __DIR__. '/DbTable.php';
    require_once __DIR__. '/../utils/Logging.php';
    /**
     * Entity Manager
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class EntityManager{
        private $table = NULL;
        private $sql   = NULL;
        private $logging = NULL;
        
        public function EntityManager($table){
            $this->sql = new Sql;
            $this->table = $table;
            $this->logging = new Logging(self::class);
        }
        
        /**
         * Insert and persists into specified table
         * 
         * @param Array aFieldArray
        * @return Array with status and message
         */
        public function addData($aFieldArray) {                    
            $aFieldList = $this->table->getFieldList();
           
            //build SQL query
            $aQuery = "INSERT INTO ". $this->table->getTableName() ." ";
            $aQuery =  $aQuery."(";
            foreach ($aFieldArray as $aItem => $aValue) {
                $aQuery .= "$aFieldList[$aItem], ";
            }
            $aQuery = rtrim($aQuery, ', ');
            $aQuery =  $aQuery.")";
            
            $aQuery =  $aQuery." VALUES (";
            foreach ($aFieldArray as $aItem => $aValue) {
                $aQuery .= "'$aValue', ";
            }
            $aQuery = rtrim($aQuery, ', ');
            $aQuery =  $aQuery.")";
            
            $this->logging->debug("SQL =",$aQuery);
            
            $aResult = $this->sql->insertData($aQuery);
            if(mysqli_errno($this->sql->getDbConnection()) <> 0) {
                return array(status=> false,message =>  $this->sql->getMySqliError());
            }
            
            if($aResult){
               return array(status=> true,message => 'Row added to '.$this->table->getTableName());
            }else{
                return array(status=> false,message => $this->sql->getMySqliError() );
            }
         }
        /**
         * 
         * @param type data
         * @return Array cotains the following status=TRUE or FALSE, message=string, resultsArray=Array
         */
        public function getData($data) {
            $this->table = new DBTable();
            $this->table->setDataArray(array());
            
            $aQuery = "SELECT * FROM ".$this->table->getTableName();
            $this->sql = new Sql();
            $aResult = $this->sql->getResultOfQuery($aQuery);
            if($aResult){
                if($this->table->isMultipleRecordsQuery()){
                    return array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordsInResults($aResult));
                }else{
                   return array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordInResults($aResult));
                }
            }
            return array(status=> false,message => $this->sql->getMySqliError());
        }
        /**
         * 
         * @param string sqlString
         * @param Boolean isMultipleRowQuery 
         * @param DBTable Mapper
         * 
         * @returns Array cotains the following status=TRUE or FALSE, message=string, resultsArray=MysqliResults
         */
        public function queryResults($sqlString) {
            $aResult = $this->sql->getResultOfQuery($sqlString);
            if($aResult){
                return array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $aResult);
            }
            return array(status=> false,message => $this->sql->getMySqliError());
        }
                /**
         * 
         * @param string sqlString
         * @param Boolean isMultipleRowQuery 
         * @param DBTable Mapper
         * 
         * @returns Array cotains the following status=TRUE or FALSE, message=string, resultsArray=Array
         */
        public function queryRows($sqlString, $isMultipleRowQuery) {
            $aResult = $this->sql->getResultOfQuery($sqlString);
            if($aResult){
                if($isMultipleRowQuery){
                    return array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordsInResults($aResult));
                }else{
                   return array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordInResults($aResult));
                }
            }
            return array(status=> false,message => $this->sql->getMySqliError());
        }
        
        public function getTable() {
            return $this->table;
        }

        public function getSql() {
            return $this->sql;
        }

        public function setTable($table) {
            $this->table = $table;
        }

        public function setSql($sql) {
            $this->sql = $sql;
        }
    }