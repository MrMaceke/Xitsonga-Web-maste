<?php
    require_once 'HTMLDisplay.php';
    require_once "EntityDetailsEntity.php";
    require_once "ItemTypeDAO.php";
    require_once "EntityDAO.php";
    require_once "AuditDAO.php";
    /**
     * Generates a HTML Display
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class JSONDisplay{
        /**
         * 
         * @param PHPArray aResults
         * @param Integer statusCode
         * @param String infoMessage
         * 
         * @return JSON
         */
        public static function GetAidioURLsJSON($aResults, $statusCode, $infoMessage) {
            
           foreach($aResults as $aResult){               
                $construct =  $aResult;
                
                $aDetailJSON = $aDetailJSON."{"
                 ."\"url\":\"" .  $construct ."\"";        
                
                $aDetailJSON = $aDetailJSON."}";
                
                $aDetailJSON = $aDetailJSON.",";
            }
            $aDetailJSON = substr_replace($aDetailJSON, "", -1);
            
            return "{ "
                   ."\"status\":" .  $statusCode .","
                   ."\"infoMessage\":"."\"".$infoMessage ."\","
                   ."\"urls\":["
                       .$aDetailJSON 
                   ."]"
                   . "}";
        }
        /**
         * 
         * @param PHPArray aResults
         * @param Integer statusCode
         * @param String infoMessage
         * 
         * @return JSON
         */
        public static function GetAnswersJSON($aResults, $statusCode, $infoMessage) {
            
           foreach($aResults as $aResult){               
                $answerText =  $aResult['answer_text'];
                $correct = $aResult['correct'];
                
                $aDetailJSON = $aDetailJSON."{"
                 ."\"answerText\":\"" .  $answerText ."\","
                 ."\"correct\":\"" .  $correct ."\"";        
                
                $aDetailJSON = $aDetailJSON."}";
                
                $aDetailJSON = $aDetailJSON.",";
            }
            $aDetailJSON = substr_replace($aDetailJSON, "", -1);
            
            return "{ "
                   ."\"status\":" .  $statusCode .","
                   ."\"infoMessage\":"."\"".$infoMessage ."\","
                   ."\"answers\":["
                       .$aDetailJSON 
                   ."]"
                   . "}";
        }
        
        
        
        public static function GetEntityDetailJSON($aResults, $itemType, $statusCode, $infoMessage) {

            foreach($aResults as $aResult){
                $names = $aResult['firstname']. " ".$aResult['lastname'];
                $id = $aResult['entity_details_id'];
                $description = $aResult['description'];
                $content = ucfirst($aResult['content']);
               
                if(is_numeric($content)){
                    $aItemTypeDAO = new ItemTypeDAO();
                    
                    $aTemp = $aItemTypeDAO->getItemTypeByID($content);
                    
                    if($aTemp[resultsArray][2] != "--Default--"){
                        $content = ucfirst($aTemp[resultsArray][2]);
                    }else{
                        $content = "--Default--";
                    }
                }
                $htmlContent = 1;
                $content = JSONDisplay::formSafeJSON($content);
                
                if(strip_tags($content) != $content){
                    $htmlContent = 2;
                }
                
                $entity_id =  $aResult['entity_id']. "";
                $dateCreated = $aResult['date_created'];
                $aDetailJSON = $aDetailJSON."{"
                 ."\"id\":" .  $id .","
                 ."\"typeName\":\"" .  $description ."\","
                 ."\"htmlContent\":\"" .  $htmlContent ."\","
                 ."\"dateCreated\":\"" .  $dateCreated ."\","
                 ."\"creator\":\"" .  $names ."\","
                 ."\"content\":\"" .  $content ."\"";        

                $aDetailJSON = $aDetailJSON."}";
                
                $aDetailJSON = $aDetailJSON.",";
            }
            $aDetailJSON = substr_replace($aDetailJSON, "", -1);
            
            return "{ "
                    ."\"entity_id\":\"" .  $entity_id ."\","
                    ."\"itemType\":\"" .  $itemType ."\","
                    ."\"status\":" .  $statusCode .","
                    ."\"infoMessage\":"."\"".$infoMessage ."\","
                    ."\"entityDetails\":["
                        .$aDetailJSON 
                    ."]"
                    . "}";
        }
        
        
        public static function GetEntitiesJSON($aResults, $statusCode, $infoMessage) {
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aCount = 0;
            foreach($aResults as $aResult){
                $id = $aResult['entity_id'];
                $aCount ++;
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($id);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$WEBSITE_LINK][content]);
                $typeRecord2 = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
                
                
                /*
                if($array[ItemTypeDAO::$DICTIONARY_TYPE][content] == 12){
                   $typeRecord2 = "nouns"; 
                }else if($array[ItemTypeDAO::$DICTIONARY_TYPE][content] == 13){
                   $typeRecord2 = "verbs"; 
                }else{
                    $typeRecord2 = "other"; 
                }
                
                if($array[ItemTypeDAO::$WEBSITE_LINK][content] == 15){
                   $typeRecord = "animals"; 
                }else if($array[ItemTypeDAO::$WEBSITE_LINK][content] == 45){
                   $typeRecord = "colors"; 
                }else if($array[ItemTypeDAO::$WEBSITE_LINK][content] == 21){
                   $typeRecord2 = "fruits"; 
                }else if($array[ItemTypeDAO::$WEBSITE_LINK][content] == 48){
                   $typeRecord = "vegetables"; 
                }else{
                    $typeRecord2 = "other"; 
                }
                */
                $type = strtolower($aResult['description']);
                $description = $aResult['entity_name'];
                $subtype = lcfirst($typeRecord[resultsArray][2]);
                $dictionaryType = lcfirst($typeRecord2[resultsArray][2]);
                
                $content = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                $count = 100;
                if($type == 'xitsonga' OR $type =='english') {
                 $content = JSONDisplay::formSafeJSON(HTMLDisplay::ucfirstSentence(str_replace(",",".",$content,$count)));
                 $content = JSONDisplay::formSafeJSON(HTMLDisplay::ucfirstSentence(str_replace("‚",".",$content,$count)));
                } 

                $output = rtrim($content , '.');
                
                if($dictionaryType == ""){
                    $dictionaryType = "-";
                }
                
                if($subtype == ""){
                    $subtype = "-";
                }
                
                $antonyms = ($array[ItemTypeDAO::$ANTONYMS][content]);
                $sysnonyms = ($array[ItemTypeDAO::$SYSNONYMS][content]);
                $homonyms = ($array[ItemTypeDAO::$HOMONYMS][content]);
                $explaination = ($array[ItemTypeDAO::$EXPLAINATION][content]);
                
                if(strlen($antonyms) > 2) {
                  $subtype = "antonyms";
                  $explaination = "Antonym: ".$antonyms;
                }
                
                 if(strlen($sysnonyms) > 2) {
                  $subtype = "synonyms";
                  $explaination = "Sysnonym: ".$sysnonyms;
                }
                
                if(strlen($homonyms) > 2) {
                  $subtype = "homonyms";
                  $explaination = "Homonym: ".$homonyms;
                }

                $image = ($array[ItemTypeDAO::$IMAGE][content]);
                
                $dateCreated = $aResult['date_created'];
                $aEntityJSON = $aEntityJSON."{"
                 ."\"id\":\"" .  $id ."\","
                 ."\"description\":\"" .  ucfirst(JSONDisplay::formSafeJSON($description)) ."\","
                 ."\"type\":\"" .  $type ."\","
                 ."\"subtype\":\"" .  $subtype ."\","
                 ."\"dictionaryType\":\"" . $dictionaryType ."\","
                 ."\"extra\":\"" . JSONDisplay::formSafeJSON($explaination)."\","
                 ."\"image\":\"" . $image ."\","
                 ."\"dateCreated\":\"" .  $dateCreated ."\","
                 ."\"translation\":\"" .  JSONDisplay::formSafeJSON($output) ."\"";        

                $aEntityJSON = $aEntityJSON."}";
                
                $aEntityJSON = $aEntityJSON.",";
                
                if($aCount > 7000){
                    break;
                }
            }
            $aEntityJSON = substr_replace($aEntityJSON, "", -1);
            
            return "{ "
                    ."\"status\":" .  $statusCode .","
                    ."\"InfoMessage\":"."\"".$infoMessage."\","
                    ."\"entities\":["
                        .$aEntityJSON 
                    ."]"
                    . "}";
        }
        
        public static function GetTranslationConfigsJSON($aResults, $statusCode, $infoMessage) {
            $aCount = 0;
            foreach($aResults as $aResult){
                
                $item = strtolower($aResult['item']);
                $replacement = strtolower($aResult['replacement']);
                $pattern = strtolower($aResult['pattern']);
                $push_first = strtolower($aResult['push_first']);
                $push_last = strtolower($aResult['push_last']);
                $swap_right = strtolower($aResult['swap_right']);
                $swap_left = strtolower($aResult['swap_left']);
                
                $aEntityJSON = $aEntityJSON."{"
                 ."\"item\":\"" .  $item ."\","
                 ."\"replacement\":\"" . $replacement ."\","
                 ."\"pattern\":\"" . $pattern ."\","
                 ."\"push_first\":\"" . $push_first ."\","
                 ."\"push_last\":\"" . $push_last ."\","        
                 ."\"swap_right\":\"" . $swap_right ."\"," 
                 ."\"swap_left\":\"" . $swap_left ."\"";

                $aEntityJSON = $aEntityJSON."},";
            }
            $aEntityJSON = substr_replace($aEntityJSON, "", -1);
            
            return "{ "
                    ."\"status\":" .  $statusCode .","
                    ."\"InfoMessage\":"."\"".$infoMessage."\","
                    ."\"configs\":["
                        .$aEntityJSON 
                    ."]"
                    . "}";
        }
        
        public static function GetEntitiesAPIJSON($aResults,$language, $statusCode, $infoMessage) {
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aCount = 0;
            foreach($aResults as $aResult){
                $id = $aResult[1];
                
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($id);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$WEBSITE_LINK][content]);
                $typeRecord2 = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
               
                $description = $aResult['entity_name'];
                $subtype = lcfirst($typeRecord[resultsArray][2]);
                $dictionaryType = lcfirst($typeRecord2[resultsArray][2]);
                
                $content = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                $count = 100;
                $content = JSONDisplay::formSafeJSON(HTMLDisplay::ucfirstSentence(str_replace(",",". ",$content,$count)));
                $content = JSONDisplay::formSafeJSON(HTMLDisplay::ucfirstSentence(str_replace("‚",". ",$content,$count)));

                $output = rtrim($content , '.');
                
                if($dictionaryType == ""){
                    $dictionaryType = "-";
                }
                
                if($subtype == ""){
                    $subtype = "-";
                }
                $type = strtolower($aResult['description']);
               
                if($type == $language) {
                    $aEntityJSON = $aEntityJSON."{"
                     ."\"language\":"."\"".JSONDisplay::formSafeJSON($language)."\","
                     ."\"description\":\"" .  JSONDisplay::formSafeJSON(strtolower($description)) ."\","
                     ."\"translation\":\"" .  JSONDisplay::formSafeJSON(strtolower($content)) ."\"";        

                    $aEntityJSON = $aEntityJSON."}";

                    $aEntityJSON = $aEntityJSON.",";
                    
                    $aCount ++;
                }
                
                if($aCount > 5){
                    break;
                }
            }
            $aEntityJSON = substr_replace($aEntityJSON, "", -1);
            
            return "{ "
                    ."\"status\":" .  $statusCode .","
                    ."\"message\":"."\"".$infoMessage."\","
                    ."\"translations\":["
                        .$aEntityJSON 
                    ."]"
                    . "}";
        }
        
        public static function GetEntitiesAPIXML($aResults,$language, $statusCode, $infoMessage) {
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aCount = 0;
            
            $aXML = new SimpleXMLElement('<xml/>');
            $aXML->addChild('status', $statusCode);
            $aXML->addChild('message', $infoMessage);
            $aTranslations = $aXML->addChild('translations');
            foreach($aResults as $aResult){
                $id = $aResult[1];
                
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($id);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$WEBSITE_LINK][content]);
                $typeRecord2 = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
               
                $description = $aResult['entity_name'];
                $subtype = lcfirst($typeRecord[resultsArray][2]);
                $dictionaryType = lcfirst($typeRecord2[resultsArray][2]);
                
                $content = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                $count = 100;
                $content = JSONDisplay::formSafeJSON(HTMLDisplay::ucfirstSentence(str_replace(",",". ",$content,$count)));
                $content = JSONDisplay::formSafeJSON(HTMLDisplay::ucfirstSentence(str_replace("‚",". ",$content,$count)));

                $output = rtrim($content , '.');
                
                if($dictionaryType == ""){
                    $dictionaryType = "-";
                }
                
                if($subtype == ""){
                    $subtype = "-";
                }
                $type = strtolower($aResult['description']);
               
                if($type == $language) {
                    $aTranslation = $aTranslations->addChild('translation');
                    
                    $aTranslation->addChild('language',JSONDisplay::formSafeJSON($language));
                    $aTranslation->addChild('description',JSONDisplay::formSafeJSON(strtolower($description)));
                    $aTranslation->addChild('translation', JSONDisplay::formSafeJSON(strtolower($content)));

                    $aCount ++;
                }
                
                if($aCount > 5){
                    break;
                }
            }
            
            Header('Content-type: text/xml');
            
            return $aXML->asXML();
        }
        
        public static function GetNumbersJSON($aResults, $statusCode, $infoMessage) {
            foreach($aResults as $aResult){
                
                $description = $aResult[description];
                $content = $aResult[translation];
                $aEntityJSON = $aEntityJSON."{"
                 ."\"description\":\"" .  $description ."\","
                 ."\"translation\":\"" .  $content ."\"";        

                $aEntityJSON = $aEntityJSON."}";
                
                $aEntityJSON = $aEntityJSON.",";
            }
            $aEntityJSON = substr_replace($aEntityJSON, "", -1);
            
            return "{ "
                    ."\"status\":" .  $statusCode .","
                    ."\"InfoMessage\":"."\"".$infoMessage."\","
                    ."\"entities\":["
                        .$aEntityJSON 
                    ."]"
                    . "}";
        }
        
        public static function GetEntityJSON($aResult,$aDetailResult, $statusCode, $infoMessage) {
            $id = $aResult['entity_id'];
            $content = ucfirst($aResult['entity_name']);
            $dateCreated = $aResult['date_created'];
            $aTypeId = $aResult['item_type'];
            $aTypeDescription = $aResult['description'];
            
            $aDetailJSON = $aDetailJSON."{"
             ."\"entity_id\":\"" .  $id ."\","
             ."\"item_type\":\"" .  $aTypeId ."\","
             ."\"typeDescription\":\"" .  $aTypeDescription ."\","
             ."\"dateCreated\":\"" .  $dateCreated ."\","
             ."\"description\":\"" .  $content ."\"";        

            $aDetailJSON = $aDetailJSON."}";

            
            return "{ "
                    ."\"entity_id\":\"" .  $id ."\","
                    ."\"status\":" .  $statusCode .","
                    ."\"infoMessage\":"."\"".$infoMessage ."\","
                    ."\"entity\":"
                        .$aDetailJSON 
                    .","
                    ."\"detail\":"
                        .JSONDisplay::GetEntityDetailJSON($aDetailResult,"", $statusCode, $infoMessage) 
                    .""
                    . "}";
        }
       
        public static function GetServerMessageJSON($status, $subject, $content,$update, $id) {

            return "{ "
                    ."\"status\":" .  $status .","
                    ."\"subject\":"."\"".$subject ."\","
                    ."\"content\":"."\"".$content ."\","
                    ."\"update\":" .  $update .","
                    ."\"id\": \"" .  $id ."\""
                    . "}";
        }
        
        public static function formSafeJSON($json) {
            $json = empty($json) ? '[]' : $json ;
            $search = array('\\',"\n","\r","\f","\t","\b",'"') ;
            $replace = array('\\\\',"\\n", "\\r","\\f","\\t","\\b", "'");
            $json = str_replace($search,$replace,$json);

            return $json;
        }
        
        public static function mres($value)
        {
            $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
            $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

            return str_replace($search, $replace, $value);
        }
        
        public static function IsJson($string) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
    
    }
?>
