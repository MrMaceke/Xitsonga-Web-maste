<?php
    /**
     * Generates a JSON object
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class JSONUtils{
        /**
         * Formats input errorMessage to JSON
         * 
         * @param string errorMessage
         * @param NumberFormatter statusCode
         * @return string
         */
        public function errorFeedback($errorMessage, $statusCode) {
            return "{ "
                    ."\"status\":" . $statusCode .","
                    ."\"errorMessage\":"."\"".$errorMessage ."\""
                    . "}";
        }
        /**
         * Formats input infoMessage to JSON
         * 
         * @param string inroMessage
         * @param NumberFormatter statusCode
         * @return string
         */
        public function successFeedback($infoMessage, $statusCode) {
             return "{ "
                    ."\"status\":" .  $statusCode .","
                    ."\"infoMessage\":"."\"".$infoMessage ."\""
                    . "}";
        }
    }
?>
