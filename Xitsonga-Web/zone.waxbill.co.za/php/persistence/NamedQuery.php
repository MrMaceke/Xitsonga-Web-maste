<?php
    /**
     * Named Query
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class NamedQuery {
        private $aSqlQuery = null;
        
        public function NamedQuery($sqlQuery) {
            $this->aSqlQuery = $sqlQuery;
        }
        /**
         * Replaces value holder with actual value
         * 
         * @param string named speficied as holder for value
         * @param string value to be set to param
         */
        public function setParameter($param, $value) {
            $this->aSqlQuery  = str_replace("?".$param."?", "\"".$value."\"", $this->aSqlQuery);
        }
        /**
         * Replaces value holder with actual value
         * 
         * @param string named speficied as holder for value
         * @param string value to be set to param
         */
        public function setParameterInteger($param, $value) {
            $this->aSqlQuery  = str_replace("?".$param."?", $value, $this->aSqlQuery);
        }
        /**
         * Replaces value holder with actual values
         * 
         * @param string named speficied as holder for values
         * @param string value to be set to param
         */
        public function setParameterArray($param, $values) {
            foreach ($values as $key => $value) {
                $aInClause = $aInClause. "\"".$value."\",";
            }
            $aInClause = substr_replace($aInClause, "", -1);
            $this->aSqlQuery  = str_replace("?".$param."?",$aInClause, $this->aSqlQuery);
        }
        /**
         * 
         * @return string sql query with parameters set
         */
        public function getQuery() {
            return $this->aSqlQuery;
        }
    }