<?php

/**
 * Generates a XML object
 * 
 * @author Sneidon Dumela <sneidon@yahoo.com>
 * @version 1.0
 */
class XMLUtils {

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
    public function trilioSMSResponse($aMessage) {
        $aXML = new SimpleXMLElement('<Response/>');

        $aXML->addChild('Message', $aMessage);

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
    public function trilioCallResponse($aMessage) {
        $aXML = new SimpleXMLElement('<Response/>');

        $aXML->addChild('Play', $aMessage);

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
    public function trilioMediaSMSResponse($aMessage, $aMedia) {
        $aXML = new SimpleXMLElement('<Response/>');
        $aMessageTag = new SimpleXMLElement('<Message/>');

        $aMessageTag->addChild('Body', $aMessage);
        $aMessageTag->addChild('Media', $aMedia);
        
        $this->appendSimpleXMLElement($aXML, $aMessageTag);
        Header('Content-type: text/xml');
        return $aXML->asXML();
    }

    public function appendSimpleXMLElement(SimpleXMLElement $to, SimpleXMLElement $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }
}

?>
