<?php
    require_once __DIR__. '/../constants/SQLConnectionConstants.php';
    /**
     * SQL connections & functions
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class Sql {
        /**
         *  SQL connection
         *
         * @var SQL connection 
         */
        private $dbConnection = NULL;

        public function Sql() {
            $dbAddress  = SQLConnectionConstants::DATABASE_HOST;
            $dbDatabase = SQLConnectionConstants::DATABASE_NAME;
            $dbUsername = SQLConnectionConstants::DATABASE_USER;
            $dbPassword = SQLConnectionConstants::DATABASE_PASSWORD;

            $this->dbConnection = new mysqli($dbAddress,$dbUsername,$dbPassword, $dbDatabase); 
            if($this->dbConnection->connect_error != ""){
                die("An error occured while obtaining essential system resource.");
            }
        }
        /**
         * Cleans and prepares string for SQL formatting
         * 
         * @param type String
         * @return String
         */
        public function clean($string) {
            return mysql_real_escape_string($this->dbConnection, stripslashes($string));
        }
        /**
         * Returns resultSet of query
         * 
         * @param type query
         * @return resultSet or null
         */
        public function getResultOfQuery($query){
            $result = $this->executeQuery($query);

            return $result ? $result : NULL;
        }
        /**
         * Executes speficied query, e.i insert, update,remove
         * 
         * @param string query
         * @return result
         */
        private function executeQuery($query){
            mysqli_refresh($this->dbConnection,MYSQLI_REFRESH_THREADS);
            
            $aReturn =  $this->dbConnection->query($query);
            
            return $aReturn;
        }
        /**
         * Inserts records as specified in query string
         * 
         * @param string query
         * @return resultSet
         */
        public function insertData($query){
          return $this->executeQuery($query);
        }
        /**
         * Updates records as specified in query string
         * 
         * @param string query
         * @return resultSet
         */
        public function updateData($query){
            return $this->executeQuery($query);
        }
        /**
         * Deletes records as specified in query string
         * 
         * @param string query
         * @return resultSet
         */     
        public function removeData($query){
            return $this->executeQuery($query);
        }
        /**
         * Converts resultSet into array elements for individual records
         * 
         * @param Array resultSet
         * @return Array
         */
        public function getRecordsInResults($result){
            if ($result){			
                while ($row = mysqli_fetch_array($result)) {
                    $rows[] = $row;
                }
                return $rows;
            }
            return array();
        }

        /**
         * Converts result into one individual records
         * 
         * @param Array resultSet
         * @return Array
         */
        public function getRecordInResults($result){
            if ($result){
                if ($this->getResultCount($result) == 1){
                    $row = mysqli_fetch_array($result);
                    return $row;
                }
            }
            return null;
        }
        /**
         * Counts the number of records in a resultSet
         * 
         * @param Array $result
         * @return int
         */
        public function getResultCount($result){
            if ($result){
                return mysqli_num_rows($result);
            }
            else{
                return 0;
            }
        }

        /**
         * Counts the number of records affected by most recent update or delete function in a resultSet
         * 
         * @param Array $result
         * @return int
         */
        public function getAffectedResultCount($result){
            if ($result){
                return mysqli_affected_rows($this->dbConnection);
            }
            else{
                return 0;
            }
        }
        /**
         * Get most recent error sqli error messsage
         * 
         * @return string
         */
        public function getMySqliError() {
            return mysqli_error( $this->dbConnection);
        }
        /**
         * Returns SQL database connection
         * 
         * @return connection
         */
        public function getDbConnection() {
            return $this->dbConnection;
        }
        /**
         * Turns on or off auto-committing database modifications
         * 
         * @param Boolean boolFlag
         * @return Boolean TRUE on sucess and FALSE on failure
         */
        public function setAutocommit($boolFlag){
            return mysqli_autocommit($this->dbConnection,$boolFlag);
        }
        /**
         * Commits current transcation
         * 
         * @return Boolean TRUE on sucess and FALSE on failure
         */
        public function commitTransaction(){
            return $this->dbConnection->commit();
        }
        /**
         * Allows user to start transcation and turns on or off auto-committing database modifications
         * 
         * @return Boolean TRUE on sucess and FALSE on failure
         */
        public function beginTransaction(){
            return $this->setAutocommit(false);
        }
        /**
         * Discards database modification attempts
         * 
         * @return Boolean TRUE on sucess and FALSE on failure
         */
        public function rollbackTransaction(){
            return $this->dbConnection->rollback();
        }
    }