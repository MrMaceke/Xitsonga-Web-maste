<?php
    /**
     * Named Query
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
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
         * 
         * @return string sql query with parameters set
         */
        public function getQuery() {
            return $this->aSqlQuery;
        }
    }

?>
