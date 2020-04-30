<?php

require_once 'constants.php';

/**
 * SQL connections & functions
 * 
 * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
 * @version 1.0
 */
class Sql {

    /**
     *  SQL connection
     * 
     * @var SQL connection 
     */
    private static $dbConnection = NULL;

    public function Sql() {
        $this->getDatabaseConnection();
    }

    public function getDatabaseConnection() {
        if (!isset(static::$dbConnection) || static::$dbConnection == NULL) {
            $dbAddress = DATABASE_HOST;
            $dbUsername = DATABASE_USER;
            $dbPassword = DATABASE_PASSWORD;
            $dbDatabase = DATABASE_NAME;

            static::$dbConnection = new mysqli($dbAddress, $dbUsername, $dbPassword, $dbDatabase);
            if (static::$dbConnection->connect_error && false) {
                if (split(',', getallheaders()['Accept'])[0] == "text/html") {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location:' . "overloaded");
                } else {
                    echo "{ "
                    . "\"status\":" . -999 . ","
                    . "\"errorMessage\":" . "\"" . "Sytem is overloaded. Please try again later" . "\""
                    . "}";
                    exit();
                }
            }
        }
        return static::$dbConnection;
    }

    /**
     * Cleans and prepares string for SQL formatting
     * 
     * @param type String
     * @return String
     */
    public function clean($string) {
        return mysql_real_escape_string(static::$dbConnection, stripslashes($string));
    }

    /**
     * Returns resultSet of query
     * 
     * @param type query
     * @return resultSet or null
     */
    public function getResultOfQuery($query) {
        $result = $this->executeQuery($query);

        return $result ? $result : NULL;
    }

    /**
     * Executes speficied query, e.i insert, update,remove
     * 
     * @param string query
     * @return result
     */
    private function executeQuery($query) {
        return static::$dbConnection->query($query);
    }

    /**
     * Executes speficied query, e.i insert, update,remove
     * 
     * @param string query
     * @return result
     */
    private function executeQueryV2($query, $fieldArray) {

        $prepared = static::$dbConnection->prepare($query);
        if ($prepared == false) {
            return false;
        }

        $bindFirstParam = "";

        foreach ($fieldArray as $aItem => $aValue) {
            $bindFirstParam .= "s";
        }
        switch (count($fieldArray)) {
            case 1: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0]);
                    break;
                }
            case 2: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1]);
                    break;
                }
            case 3: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1], $fieldArray[2]);
                    break;
                }
            case 4: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3]);
                    break;
                }
            case 5: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4]);
                    break;
                }
            case 6: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4], $fieldArray[5]);
                    break;
                }
            case 7: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4], $fieldArray[5], $fieldArray[6]);
                    break;
                }
            case 7: {
                    $result = $prepared->bind_param($bindFirstParam, $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4], $fieldArray[5], $fieldArray[6], $fieldArray[7]);
                    break;
                }
        }

        if ($result == false) {

            return false;
        }
        $result = $prepared->execute();

        $prepared->close();

        return $result;
    }

    /**
     * Inserts records as specified in query string
     * 
     * @param string query
     * @return resultSet
     */
    public function insertData($query) {
        return $this->executeQuery($query);
    }

    /**
     * Inserts records as specified in query string
     * 
     * @param string query
     * @return resultSet
     */
    public function insertDataV2($query, $array) {
        return $this->executeQueryV2($query, $array);
    }

    /**
     * Updates records as specified in query string
     * 
     * @param string query
     * @return resultSet
     */
    public function updateData($query) {
        return $this->executeQuery($query);
    }

    /**
     * Deletes records as specified in query string
     * 
     * @param string query
     * @return resultSet
     */
    public function removeData($query) {
        return $this->executeQuery($query);
    }

    /**
     * Converts resultSet into array elements for individual records
     * 
     * @param Array resultSet
     * @return Array
     */
    public function getRecordsInResults($result) {
        if ($result) {
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
    public function getRecordInResults($result) {
        if ($result) {
            if ($this->getResultCount($result) == 1) {
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
    public function getResultCount($result) {
        if ($result == NULL) {
            return 0;
        }

        if ($result) {
            return mysqli_num_rows($result);
        } else {
            return 0;
        }
    }

    /**
     * Counts the number of records affected by most recent update or delete function in a resultSet
     * 
     * @param Array $result
     * @return int
     */
    public function getAffectedResultCount($result) {
        if ($result) {
            return mysqli_affected_rows(static::$dbConnection);
        } else {
            return 0;
        }
    }

    /**
     * Get most recent error sqli error messsage
     * 
     * @return string
     */
    public function getMySqliError() {
        return mysqli_error(static::$dbConnection);
    }

    /**
     * Returns SQL database connection
     * 
     * @return connection
     */
    public function getDbConnection() {
        return static::$dbConnection;
    }

    /**
     * Turns on or off auto-committing database modifications
     * 
     * @param Boolean boolFlag
     * @return Boolean TRUE on sucess and FALSE on failure
     */
    public function setAutocommit($boolFlag) {
        return mysqli_autocommit(static::$dbConnection, $boolFlag);
    }

    /**
     * Commits current transcation
     * 
     * @return Boolean TRUE on sucess and FALSE on failure
     */
    public function commitTransaction() {
        $this->setAutocommit(true);
        return static::$dbConnection->commit();
    }

    /**
     * Allows user to start transcation and turns on or off auto-committing database modifications
     * 
     * @return Boolean TRUE on sucess and FALSE on failure
     */
    public function beginTransaction() {
        return $this->setAutocommit(false);
    }

    /**
     * Discards database modification attempts
     * 
     * @return Boolean TRUE on sucess and FALSE on failure
     */
    public function rollbackTransaction() {
        static::$dbConnection->rollback();
        return $this->setAutocommit(false);
    }

    public function closeConnection() {
        if (static::$dbConnection->connected) {
            static::$dbConnection->close();
            static::$dbConnection->connected = false;
        }
    }

    function __destruct() {
        $this->closeConnection();
    }
}
            
?>