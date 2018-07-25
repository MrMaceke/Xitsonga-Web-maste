<?php
    /**
     * Generates a XML object
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class XMLUtils{
        /**
         * Formats input errorMessage to XML
         * 
         * @param string errorMessage
         * @param NumberFormatter statusCode
         * @return string
         */
        public function errorFeedback($errorMessage, $statusCode) {
            $aXML = new SimpleXMLElement('<xml/>');
            
            $aXML->addChild('status', $statusCode);
            $aXML->addChild('message', $errorMessage);
            
            Header('Content-type: text/xml');
            
            return $aXML->asXML();
        }
        /**
         * Formats input infoMessage to XML
         * 
         * @param string inroMessage
         * @param NumberFormatter statusCode
         * @return string
         */
        public function successFeedback($infoMessage, $statusCode) {
            $aXML = new SimpleXMLElement('<xml/>');
            
            $aXML->addChild('status', $statusCode);
            $aXML->addChild('message', $infoMessage);
            
            Header('Content-type: text/xml');
            
            return $aXML->asXML();
        }
    }
?>
