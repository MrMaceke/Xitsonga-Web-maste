<?php
    require_once 'Sql.php';
    /**
     * Default table properties
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class DBTable{
        private $tableName       = null;     
        private $dbName          = null;           
        private $rowsPerPage     = 10;    
        private $pageNo          = 1;           
        private $lastPage        = 1;         
        private $fieldList       = null;       
        private $dataArray       = null;        
        private $errors          = null; 
        private $isMultipleRecordsQuery = null;  
        
        public function __construct($dbName,$tableName) {
            $this->tableName = $tableName;
            $this->dbName = $dbName;
        }
        
        public function getTableName() {
            return $this->tableName;
        }

        public function getDbName() {
            return $this->dbName;
        }

        public function getRowsPerPage() {
            return $this->rowsPerPage;
        }

        public function getPageNo() {
            return $this->pageNo;
        }

        public function getLastPage() {
            return $this->lastPage;
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
        
        public function isMultipleRecordsQuery() {
            return $this->isMultipleRecordsQuery;
        }
        public function setTableName($tableName) {
            $this->tableName = $tableName;
        }

        public function setDbName($dbName) {
            $this->dbName = $dbName;
        }

        public function setRowsPerPage($rowsPerPage) {
            $this->rowsPerPage = $rowsPerPage;
        }

        public function setPageNo($pageNo) {
            $this->pageNo = $pageNo;
        }

        public function setLastPage($lastPage) {
            $this->lastPage = $lastPage;
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

        public function setIsMultipleRecordsQuery($isMultipleRecordsQuery ) {
            $this->isMultipleRecordsQuery = $isMultipleRecordsQuery;
        }

    }

?>
