<?php
    require_once __DIR__. '/Sql.php';
    require_once __DIR__. '/../constants/SQLConnectionConstants.php';
    /**
     * Default table properties
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class DBTable{
        private $tableName       = null;     
        private $dbName          = null;           
        private $fieldList       = null;       
        private $dataArray       = null;        
        private $errors          = null; 
        private $isMultipleRecordsQuery = null;  
        
        public function __construct($tableName) {
            $this->tableName = $tableName;
            $this->dbName = SQLConnectionConstants::DATABASE_NAME;
        }
        
        public function getTableName() {
            return $this->tableName;
        }

        public function getDbName() {
            return $this->dbName;
        }

        public function getFieldList() {
            return $this->fieldList;
        }

        public function getDataArray() {
            return $this->dataArray;
        }

        public function getErrors() {
            return $this->errors;
        }

        public function getIsMultipleRecordsQuery() {
            return $this->isMultipleRecordsQuery;
        }

        public function setTableName($tableName) {
            $this->tableName = $tableName;
        }

        public function setDbName($dbName) {
            $this->dbName = $dbName;
        }

        public function setFieldList($fieldList) {
            $this->fieldList = $fieldList;
        }

        public function setDataArray($dataArray) {
            $this->dataArray = $dataArray;
        }

        public function setErrors($errors) {
            $this->errors = $errors;
        }

        public function setIsMultipleRecordsQuery($isMultipleRecordsQuery) {
            $this->isMultipleRecordsQuery = $isMultipleRecordsQuery;
        }
    }