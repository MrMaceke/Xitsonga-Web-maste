<?php
    require_once 'Sql.php';
    require_once 'DbTable.php';
    /**
     * Entity Manager
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class EntityManager{
        private $table = null;
        private $sql   = null;
        
        public function EntityManager($table){
             $this->sql = new Sql();
             $this->sql->Sql();
             $this->table = $table;
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
            
            $aResult = $this->sql->insertData($aQuery);
            if(mysqli_errno($this->sql->getDbConnection()) <> 0) {
                $results =  array(status=> false,message =>  $this->sql->getMySqliError());
                
                $this->closeConnectition();
                return $results;
            }
            
            if($aResult){
               $results = array(status=> true,message => 'Row added to '.$this->table->getTableName());
            }else{
                $results =  array(status=> false,message => $this->sql->getMySqliError() );
            }

            $this->closeConnectition();
            return $results;
         }
         /**
         * Insert and persists into specified table
         * 
         * @param Array aFieldArray
        * @return Array with status and message
         */
        public function addDataV2($aFieldArray) {
            $aFieldList = $this->table->getFieldList();
           
            //build SQL query
            $aQuery = "INSERT INTO `". $this->table->getTableName() ."` ";
            $aQuery =  $aQuery."(";
            foreach ($aFieldArray as $aItem => $aValue) {
                $aQuery .= "`$aFieldList[$aItem]`, ";
            }
            $aQuery = rtrim($aQuery, ', ');
            $aQuery =  $aQuery.")";
            
            $aQuery =  $aQuery." VALUES (";
            foreach ($aFieldArray as $aItem => $aValue) {
                $aQuery .= "?, ";
            }
            $aQuery = rtrim($aQuery, ', ');
            $aQuery =  $aQuery.");";
            
            $aResult = $this->sql->insertDataV2($aQuery,$aFieldArray);
            if(mysqli_errno($this->sql->getDbConnection()) <> 0) {
                $results =   array(status=> false,message =>  $this->sql->getMySqliError());
                
                $this->closeConnectition();
            return $results;
            }
            
            if($aResult){
               $results =   array(status=> true,message => 'Row added to '.$this->table->getTableName());
            }else{
                $results =   array(status=> false,message => $this->sql->getMySqliError());
            }
            
            $this->closeConnectition();
            return $results;
         }
         
         /**
         * Insert and persists into specified table
         * 
         * @param Array aFieldArray
        * @return Array with status and message
         */
        public function updateDataV2($aFieldArray, $uniqueField, $uniqueValue) {
            $aFieldList = $this->table->getFieldList();
           
            //build SQL query
            $aQuery = "UPDATE `". $this->table->getTableName() ."` SET ";
            foreach ($aFieldArray as $aItem => $aValue) {
                $aQuery .= "`$aFieldList[$aItem]` = ";
                 $aQuery .= "\"$aValue\", ";
            }
            $aQuery = rtrim($aQuery, ', ');
            $aQuery =  $aQuery." WHERE $uniqueField = $uniqueValue";
            
            $aResult = $this->sql->updateData($aQuery);
            if(mysqli_errno($this->sql->getDbConnection()) <> 0) {
                $results =  array(status=> false,message =>  $this->sql->getMySqliError());
            }
            
            if($aResult){
               $results =  array(status=> true,message => 'Row update to '.$this->table->getTableName());
            }else{
                $results =  array(status=> false,message => $this->sql->getMySqliError());
            }
            
            $this->closeConnectition();
            return $results;
            
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
                    $results =  array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordsInResults($aResult));
                }else{
                   $results =  array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordInResults($aResult));
                }
            } else {
                $results = array(status=> false,message => $this->sql->getMySqliError());
            }          
            $this->closeConnectition();
            return $results;
            
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
                $results =  array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $aResult);
            } else {
                $results = array(status=> false,message => $this->sql->getMySqliError());
            }
            $this->closeConnectition();
            return $results;
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
                    $results =  array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordsInResults($aResult));
                }else{
                   $results =  array(status=> true,message => $this->sql->getMySqliError(),resultsArray=> $this->sql->getRecordInResults($aResult));
                }
            } else {
                $results = array(status=> false,message => $this->sql->getMySqliError());
            }
            $this->closeConnectition();
            return $results;
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
        
        public function closeConnectition() {
            //$this->sql->closeConnection();
        }
    }
?>
