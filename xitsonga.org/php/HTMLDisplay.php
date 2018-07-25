<?php
    require_once "EntityDetailsEntity.php";
    require_once "ItemTypeDAO.php";
    require_once "EntityDAO.php";
    require_once "AuditDAO.php";
    require_once "AnswersDAO.php";
    require_once "DefinationCacheDAO.php";
    require_once "Blockspring.php";
    require_once 'AudioReader.php';
    require_once 'AuditsAPICallsDAO.php';
    /**
     * Generates a HTML Display
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class HTMLDisplay{
        
        public static function GetActivatedUserHTML($aResult) {
            $aDisplay = $aDisplay."<h6>Activation success for ".ucfirst($aResult[firstname])." ".ucfirst($aResult[lastname]).". <a href ='login'>Please sign in here</a></h6><hr>";
            return $aDisplay;
        }
        /**
         * Get single surname HTML content view
         * 
         * @param PHPArray aResult
         * @return HTML content
         */
        public static function GetSingleSurnameEntityHTMLList($aResult) {
            
            return HTMLDisplay::GetSingleRichTextEntityHTMLList($aResult);
        }
        /**
         * Get single rich text HTML content view
         * 
         * @param PHPArray aResult
         * @return HTML content
         */
        public static function GetSingleRichTextEntityHTMLList($aResult) {
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aResult = $aResult[0];
            $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
            if($aDetailResults['status']){
                $array = array();
                foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                  $array[$aDetailResult[description]] = $aDetailResult;
                }
            }
            $aTemp = $aItemTypeDAO->getItemTypeByID($aResult[item_type]);
            $aType = strtolower($aTemp[resultsArray][2]);
            
            $aImage = $array[ItemTypeDAO::$IMAGE][content];
            $aVideo = $array[ItemTypeDAO::$YOUTUBE][content];
            if($aImage != "" AND $aVideo == ""){
                if(ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) == "-" or $aType == ""){
                    $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])."</h4>";
                    $aDisplay = $aDisplay."<a style = 'float:right;padding:15px;font-size:12px' id ='openSendUserMailModalButton' href ='$_SERVER[REQUEST_URI]#'>< Edit ></a></div>";

                }else{
                    $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])."</h4>";
                    $aDisplay = $aDisplay."<a style = 'float:right;padding:15px;font-size:12px' id ='openSendUserMailModalButton' href ='$_SERVER[REQUEST_URI]#'>< Edit ></a></div>";

                    $aDisplay = $aDisplay."<img width = '85%' src ='assets/images/entity/$aImage' title ='$aResult[entity_name]'/><hr>";
                }
            }else{
                $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' class ='red' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])."</h4>";
                
                $aDisplay = $aDisplay."<a style = 'float:right;padding:15px;font-size:12px' id ='openSendUserMailModalButton' href ='$_SERVER[REQUEST_URI]#'>< Edit ></a>";
                
                $aDisplay = $aDisplay."</div>";
            }

            if($aVideo != ""){
                $aVideo =  explode("?v=", $aVideo);
                
                $aDisplay  = $aDisplay.'<iframe id="player" type="text/html" width="100%" height="400"
                            src="http://www.youtube.com/embed/'.$aVideo[1].'?enablejsapi=1"
                            frameborder="0"></iframe><hr>';
            }
            
            $aRatingValue = 0;
            
            $aCurrentRatingArray = $array[ItemTypeDAO::$RATING][content];
            if($aCurrentRatingArray != ""){
                $aCurrentRatingArray = explode("_", $aCurrentRatingArray);
                
                $aRatingValue = ceil($aCurrentRatingArray[1] / $aCurrentRatingArray[0]);
            }
            
            $aRating = HTMLDisplay::getRatingPlugin($aResult[entity_id],$aRatingValue);
            $aDisplay = "<div class ='rating_div' style ='margin-bottom:-12px'>".$aDisplay.$aRating."</div>";
            
            foreach($aDetailResults['resultsArray'] as $aDetailResult){

                if($aDetailResult[description] == ItemTypeDAO::$ENGLISH_TRANS){
                    $aDisplay = $aDisplay."<div class ='richContentStyle'>";
                    $aDisplay = $aDisplay.ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]);
                    $aDisplay = $aDisplay."</div>";
                }elseif($aDetailResult[description] == ItemTypeDAO::$AUTHOR){
                    $aDisplay = $aDisplay."<div class ='richRefStyle'>";
                        $aDisplay = $aDisplay.ItemTypeDAO::$AUTHOR." : ".ucfirst($array[ItemTypeDAO::$AUTHOR][content])."";
                    $aDisplay = $aDisplay."</div>";
                }elseif($aDetailResult[description] == ItemTypeDAO::$URL_REFERENCE){
                    $aDisplay = $aDisplay."<div class ='richRefStyle'>";
                        $aDisplay = $aDisplay.ItemTypeDAO::$URL_REFERENCE." : ".ucfirst($array[ItemTypeDAO::$URL_REFERENCE][content])."";
                    $aDisplay = $aDisplay."</div>";
                }
                
            }
            
            return $aDisplay;
        }
        /**
         * Get single entity HTML content view
         * 
         * @param PHPArray aResult
         * @return HTML content
         */
        public static function GetSingleEntityHTMLList($aResult, $which = 0) {
            $aCallResult = $aResult;
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aAuditDAO = new AuditDAO();
            $aResult = $aResult[$which];
            $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
            if($aDetailResults['status']){
                $array = array();
                foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                  $array[$aDetailResult[description]] = $aDetailResult;
                }
            }
            $aTemp = $aItemTypeDAO->getItemTypeByID($aResult[item_type]);
            $aType = strtolower($aTemp[resultsArray][2]);
            
            if($aType == "surnames"){
                return HTMLDisplay::GetSingleSurnameEntityHTMLList($aCallResult);
            }elseif($aType == "jokes" or $aType == "system-messages"  or $aType == "poems" or $aType == "song-lyrics" or  $aType == "traditional-food"){
                return HTMLDisplay::GetSingleRichTextEntityHTMLList($aCallResult);
            }
            
            $aImage = $array[ItemTypeDAO::$IMAGE][content];
            $dictionaryType = $array[ItemTypeDAO::$DICTIONARY_TYPE][content];
 
            $aDescriptive = $aType;
            
            if($aDescriptive == "xitsonga"){
                $aDescriptive = "<span class ='translations'>Definition or translations  <b>xitsonga word to english</b></spn";
            }else if($aDescriptive == "english"){
                $aDescriptive = "Definition or translations  <b>english word to xitsonga</b>";
            }
            
            if($aImage != ""){
                if(ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) == "-" or $aType == "proverbs"){
                    $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' class ='red' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])."</h4>";
                    $aDisplay = $aDisplay."</div>";
                }else{
                    $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])." - ".$array[ItemTypeDAO::$ENGLISH_TRANS][content]."</h4><div class ='main_image_style'><img src ='assets/images/entity/$aImage' title ='$aResult[entity_name]'/></div>";
                    $aDisplay = $aDisplay."</div>";
                }
            }else{
                if(ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) == "-" or $aType == "proverbs"){
                    $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' class ='red' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])."</h4>";
                    $aDisplay = $aDisplay."<a style = 'float:right;padding:15px;font-size:12px' id ='openSendUserMailModalButton' href ='$_SERVER[REQUEST_URI]#'>< Edit ></a></div>";
                }else{
                    
                   $aSearch =  $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                   
                    if($aType == "xitsonga" OR $aType == "english") {
                        $aSearch = str_replace(",", ".", $aSearch);
                        $aSearch = str_replace("‚", ".", $aSearch);
                        $aSearch = str_replace("‚", ".", $aSearch);
                    }
                    //$aSearch = preg_replace('/([\.!\?]\s?\w)/e', "strtoupper('$1')", $aSearch);
                    //var_dump($aSearch);
                    $aTempDictionaryType = $aItemTypeDAO->getItemTypeByID($dictionaryType);
                    $dictionaryType = strtolower($aTempDictionaryType[resultsArray][2]);
                    if($dictionaryType !== "--default--" && $aType == "xitsonga") {
                        if($dictionaryType == "verbs"){
                            $dictionaryType = "V.";
                        }elseif($dictionaryType == "adverbs"){
                            $dictionaryType = "Adv.";
                        }else if($dictionaryType =="nouns"){
                            $dictionaryType = "N.";
                        }else if($dictionaryType =="pronouns"){
                            $dictionaryType = "Pron.";
                        }else if($dictionaryType == "adjectives"){
                            $dictionaryType = "Adj.";
                        } else if($dictionaryType == "conjunctions"){
                            $dictionaryType = "Conj.";
                        }else if($dictionaryType == "--default--"){
                            $dictionaryType = "";
                        }
                        $dictionaryType = "<span class ='type2'><i>".$dictionaryType."</i></span>";
                    } else {
                        $dictionaryType = "";
                    }
                    
                    $aDisplay = $aDisplay."<div class='desc_heading'><h4 style ='width:85%;float:left' class ='red' id='$aResult[entity_id]'>".ucfirst($aResult[entity_name])." - ".ucfirst($aSearch)." $dictionaryType</h4>";
                    $aDisplay = $aDisplay."<a style = 'float:right;padding:15px;font-size:12px' id ='openSendUserMailModalButton' href ='$_SERVER[REQUEST_URI]#'>< Edit ></a></div>";

                }
                
            }
            
            
            $aData[item] = ucfirst($aResult[entity_name]);
            $aData[translation] = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
            $aData[caller] = "Web";
            $aData[type] = $aType;
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            //$aAuditsAPICallsDAO->AddAuditAPITrail($aData);
            
            if($aType == "xitsonga") {
            	$title = ucfirst($aResult[entity_name])." is a Xitsonga word meaning &quot;".ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]). "&quot; in English. ";
            	$aDisplay = $aDisplay."<span class ='rating_message'>"."$title</span>";
            } else  if($aType == "english") {
		$title = ucfirst($aResult['entity_name'])." is an English word meaning &quot;".ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]). "&quot; in Xitsonga. ";
            	$aDisplay = $aDisplay."<span class ='rating_message'>"."$title</span>";
            }
            
            
            $aAudioReader = new AudioReader();
            
            $aAudioResults = $aAudioReader->audioReaderSetUp();
            
            if($aAudioResults[status]){
                $aAudioReaderArray = $aAudioReader->completeAudioConstructURLsArray(strtolower($aResult[entity_name]),$aAudioResults[resultsArray]);
                
                if($aAudioReaderArray != NULL){
                    if($aType == "xitsonga"){
                        $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $aDisplay = $aDisplay. "<a href ='$actual_link'><img id ='audio_button' class ='audio_button' src ='assets/images/volume_up-24.png' width ='20'> Listen to word </a><hr>";
                    }
                    $aDisplay = $aDisplay."<input type ='hidden' value ='".count($aAudioReaderArray)."' id ='audio_array_count'/>";
                    $aDisplay = $aDisplay.HTMLDisplay::getAudioScript($aAudioReaderArray);
                } else{
                    $aDisplay = $aDisplay. "<a><img title ='Audio not available' alt ='title ='Audio not available' id ='audio_button' class ='audio_button_not_avaiable' src ='assets/images/no_audio-24.png' width ='20'></a><br/>";
                }
            }
            
            if(count($aCallResult) > 1){
                $aDisplay = $aDisplay."<div><b>Same spellings</b>: ";
                $aSameType = 0;
               
                foreach ($aCallResult as $aCallResultValue){
                    
                    if($aSameType != $which){
                        $aCallDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aCallResultValue[entity_id]);
                        $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."&which=".$aSameType;
                        if($aCallDetailResults['status']){
                            $array_temp = array();
                            foreach($aCallDetailResults['resultsArray'] as $aCallDetailResult){ 
                              $array_temp[$aCallDetailResult[description]] = $aCallDetailResult;
                            }
                        }

                        $aDisplay = $aDisplay.ucfirst("<a href ='$actual_link'>".$aCallResultValue[entity_name])."</a> - ".ucfirst($array_temp[ItemTypeDAO::$ENGLISH_TRANS][content]). ", ";
                    }
                    $aSameType ++;
                }
                $aDisplay = rtrim($aDisplay, ", ");
                
                $aDisplay = $aDisplay."</div><hr>";
            }
            $aRatingValue = 0;
            
            $aCurrentRatingArray = $array[ItemTypeDAO::$RATING][content];
            if($aCurrentRatingArray != ""){
                $aCurrentRatingArray = explode("_", $aCurrentRatingArray);
                
                $aRatingValue = ceil($aCurrentRatingArray[1] / $aCurrentRatingArray[0]);
            }
            
            $aRating = HTMLDisplay::getRatingPlugin($aResult[entity_id],$aRatingValue, $aCurrentRatingArray[1],$aCurrentRatingArray[0]);
            $aDisplay = "<div class ='rating_div' style ='margin-bottom:-10px'>".$aDisplay.$aRating."</div>";

            $aBlockspring  = new Blockspring();
            $aWeb = new WebBackend();
            $aSearch = ucfirst($aType =="english"?$aResult[entity_name]: $array[ItemTypeDAO::$ENGLISH_TRANS][content]);
            $aSearch = str_replace(",", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = explode(".", $aSearch);
            $aSearch = $aSearch[0];
            if($aType =="english" OR $aType =="xitsonga"){
                $aFoundTense = FALSE;
                foreach($aDetailResults['resultsArray'] as $aDetailResult){
                    if($aDetailResult[description] != ItemTypeDAO::$DICTIONARY_TYPE
                        AND $aDetailResult[description] != ItemTypeDAO::$ENGLISH_TRANS
                        AND $aDetailResult[description] != ItemTypeDAO::$WEBSITE_LINK
                        AND $aDetailResult[description] != ItemTypeDAO::$RATING
                        AND $aDetailResult[description] != ItemTypeDAO::$IMAGE){
                        $aFoundTense = TRUE;
                   }
                }
                
                if(count($aDetailResults['resultsArray']) > 0 && $aFoundTense){
                    $aDisplay = $aDisplay."<hr><table id ='dictionary_data_table' class='display' cellspacing='0'>";
                    $aDisplay = $aDisplay
                            ."<thead>"
                            ."<tr>"
                            . "<th>Type</th>"
                            . "<th>Description</th>"
                            ."</tr>"
                            . "</thead>";
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){

                        if($aDetailResult[description] != ItemTypeDAO::$DICTIONARY_TYPE
                            AND $aDetailResult[description] != ItemTypeDAO::$ENGLISH_TRANS
                            AND $aDetailResult[description] != ItemTypeDAO::$WEBSITE_LINK
                            AND $aDetailResult[description] != ItemTypeDAO::$RATING
                            AND $aDetailResult[description] != ItemTypeDAO::$IMAGE){
                            
                            $description = strtolower($aDetailResult[description]);

                            $content = $aDetailResult[content];
                            if(is_numeric($content)){
                                $aTemp = $aItemTypeDAO->getItemTypeByID($aDetailResult[content]);

                                if($aTemp[resultsArray][2] == "--Default--"){
                                    $content = "Default";
                                }else{
                                    $content = $aTemp[resultsArray][2];
                                }
                            }

                            $aDisplay = $aDisplay."<tr>";

                            $aDisplay = $aDisplay."<td class ='first_td'>";
                            $aDisplay = $aDisplay.ucfirst($description);
                            $aDisplay = $aDisplay."</td>";

                            $aDisplay = $aDisplay."<td>";
                            $aDisplay = $aDisplay."".ucfirst($content);
                            $aDisplay = $aDisplay."</td>";

                            $aDisplay = $aDisplay."</tr>";
                        }
                    }
                    $aDisplay = $aDisplay."</table>";
                }
                $aSearch = str_replace(" ", "-", $aSearch);
                //return "getTranslationForWord(".file_get_contents('http://www.tshivenda.org/request.php?word='.$searchWord, true).")";
                //$aExtras = file_get_contents('http://dictionaryapi.net/api/definition/'.$aSearch, true);
 
                $post = file_get_contents('http://services.aonaware.com/DictService/DictService.asmx/Define',FALSE,stream_context_create(array(
                    'http' => array(
                        'protocol_version' => 1.1,
                        'user_agent'       => 'PHPExample',
                        'method'           => 'POST',
                        'header'           => "Connection: close rn" .
                                              "Content-length: " . (5  + strlen($aSearch)) . "rn",
                        'content'          => "word=$aSearch",
                        'timeout'          => 1,
                    ),
                )));

                $aExtras = simplexml_load_string($post);
                
                $aDisplay = $aDisplay."<br/><div class ='defination_extra' style ='font-size:13px;background:#FAFAFA'>";
                $aExtraCount = 0;
                $aTrue = FALSE;
                
                $aDisplay = $aDisplay."<span class =''><b>Definition of ".strtolower($aSearch)."</b></span><hr>";
                $aCache = "";
                if($aExtras != NULL){
                    $aSplitter = "1:";
                    for($index = 2; $index < 30; $index ++){
                        $aSplitter = $aSplitter."|$index:";
                    }
                    
                    $aSplitter2 = " 1.";
                    for($index = 2; $index < 30; $index ++){
                        $aSplitter2 = $aSplitter2."| $index".".";
                    }
                    
                    foreach ($aExtras->Definitions->Definition as $aExtra){
                        $aExtraCount ++;
                        if(count($aExtras->Definitions->Definition) == $aExtraCount){
                            $aArray =  preg_split("/$aSplitter/", $aExtra->WordDefinition);
                            
                            if($aExtra->Dictionary->Name == "The Collaborative International Dictionary of English v.0.44") {
                               $aArray =  preg_split("/$aSplitter2/", $aExtra->WordDefinition); 
                            }
                            for($index = 0; $index < count($aArray); $index ++){
                                $indexCount = $index + 1;
                                $aDisplay = $aDisplay."- ".ucfirst(trim($aArray[$index]))."<br/>";
                                $aCache =  $aCache."- ".ucfirst(trim($aArray[$index]))."<br/>";
                            }
                        }
  
                        $aTrue = TRUE;
                        
                    }
                }
                if(!$aTrue){
                     $aDisplay = $aDisplay."We couldn't find a definition for the word.<br/>";
                } else {
                    $aDefinationCacheDAO = new DefinationCacheDAO();
                    $aDefinationCacheDAO->AddCache($aSearch,  $aCache,"website");
                   
                }
                $aDisplay = $aDisplay."</div>";
            }else{
                $aFoundTense = FALSE;
                  foreach($aDetailResults['resultsArray'] as $aDetailResult){
                      if($aDetailResult[description] != ItemTypeDAO::$DICTIONARY_TYPE
                          AND $aDetailResult[description] != ItemTypeDAO::$IMAGE
                          AND $aDetailResult[description] != ItemTypeDAO::$RATING
                          AND $aDetailResult[description] != ItemTypeDAO::$WEBSITE_LINK){
                          
                          $aFoundTense = TRUE;
                     }
                  }
               
                if($aFoundTense){
                    $aDisplay = $aDisplay."<table id ='dictionary_data_table' class='display' cellspacing='0'>";
                    $aDisplay = $aDisplay
                            ."<thead>"
                            ."<tr>"
                            . "<th>Type</th>"
                            . "<th>Description</th>"
                            ."</tr>"
                            . "</thead>";
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){

                          if($aDetailResult[description] != ItemTypeDAO::$DICTIONARY_TYPE
                            AND $aDetailResult[description] != ItemTypeDAO::$IMAGE
                            AND $aDetailResult[description] != ItemTypeDAO::$RATING
                            AND $aDetailResult[description] != ItemTypeDAO::$WEBSITE_LINK){
                              
                              $description = strtolower($aDetailResult[description]);

                                if($description == "english translation"){
                                    $aTemp = $aItemTypeDAO->getItemTypeByID($aResult[item_type]);
                                    $aType = strtolower($aTemp[resultsArray][2]);

                                    if($aType =="english"){
                                        $description = "Xitsonga";
                                    }else{
                                        $description = "English";
                                    }
                                }elseif($description == "dictionary type"){
                                    $description = "Type";
                                }elseif($description == "website link"){
                                    $description = "Sub type";
                                }elseif($description == "url reference"){
                                    $description = "Reference";
                                }

                                $content = $aDetailResult[content];
                                if(is_numeric($content)){
                                    $aTemp = $aItemTypeDAO->getItemTypeByID($aDetailResult[content]);

                                    if($aTemp[resultsArray][2] == "--Default--"){
                                        $content = "Default";
                                    }else{
                                        $content = $aTemp[resultsArray][2];
                                    }
                                }

                                $aDisplay = $aDisplay."<tr>";

                                $aDisplay = $aDisplay."<td class ='first_td'>";
                                $aDisplay = $aDisplay.ucfirst($description);
                                $aDisplay = $aDisplay."</td>";

                                $aDisplay = $aDisplay."<td>";
                                $aDisplay = $aDisplay."".ucfirst($content);
                                $aDisplay = $aDisplay."</td>";

                                $aDisplay = $aDisplay."</tr>";
                            }
                        }
                        $aDisplay = $aDisplay."</table><br/>";
                    }
            }
            
            $aAuditResults = $aAuditDAO->listAuditTrail($aResult[entity_id]);
            if($aAuditResults[status] == true && false){
                $aDisplay = $aDisplay."<div class ='audit_trail'>";
                foreach($aAuditResults['resultsArray'] as $aAuditResult){ 
                    $aPrevious = $aAuditResult[previous];
                    $aNew = $aAuditResult[new_value];
                    $editted = "edited";
                    if (is_numeric($aAuditResult[previous]) || is_numeric($aAuditResult[new_value])) {
                        $aPreviousArray = $aItemTypeDAO->getItemTypeByID($aAuditResult[previous]);
                        $aNewArray = $aItemTypeDAO->getItemTypeByID($aAuditResult[new_value]);
                        
                        $aPrevious = $aPreviousArray[resultsArray][2] ;
                        $aNew = $aNewArray[resultsArray][2] ;
                        
                        $editted ="edited type";
                    }
                    $check = $aNew;
                    $array_check = explode("_", $check);
                    if($array_check[1] == ""){
                        $aDisplay = $aDisplay. "<span><a href ='contributor/$aAuditResult[user_id]'>$aAuditResult[firstname] $aAuditResult[lastname]</a></span> $editted <span style ='color:gray'>$aPrevious</span> to <span style ='color:green'>$aNew</span> - $aAuditResult[date_created]<br/>";
                    }
                }
                $aDisplay = $aDisplay."</div>";
            }else{
                $aAuditResults[message] = "Item has never been editted. ";
                $aDisplay = $aDisplay. "<div class ='audit_trail'><span>$aAuditResults[message]</span></div>"; 
            }
            
            return $aDisplay;
        }
        
        public static function GetQuestionsHTMLTable($aResults) {
            $aDisplay = "<table id ='exercises_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Title</th>"
                    . "<th>Creator</th>"
                    . "<th>Answers type</th>"
                    . "<th>Action</th>"
                    ."</tr>"
                    . "</thead>";
            if($aResults != NULL){
                foreach($aResults as $aResult){
                    $aAnswersDAO = new AnswersDAO();

                    $aCount = $aAnswersDAO->getAnswersByQuestionIDCount($aResult[question_id]);

                    $aDisplay = $aDisplay."<tr>";

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.ucfirst(strtolower($aResult[question_text]));
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.ucfirst(strtolower($aResult[firstname]));
                    $aDisplay = $aDisplay."</td>";

                    $published = "Radio";
                    if($aResult[published] == 1){
                        $published = "Check";
                    }

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.$published;
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay."<a id ='$aResult[question_id]' class ='add_answers' href ='$_SERVER[REQUEST_URI]'>+ $aCount[itemsCount] answers</a>";
                    $aDisplay = $aDisplay." - <a id ='$aResult[question_id]' class ='edit_question' href ='$_SERVER[REQUEST_URI]'>Edit</a>";
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."</tr>";
                }
            }
            return $aDisplay. "</table>";
        }
        
        public static function GetExerciseReport($aResults) {
            $data = $_SESSION[current_test];
            $count = 1;
            $correctAnswers = 0;
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<div class ='question_div' id = '$aResult[exercise_id]'>";
                
                $aAnswersDAO = new AnswersDAO();
                
                $aTitle = ucfirst(strtolower($aResult[question_text]));
                
                $aDisplay = $aDisplay."<div>";
                $aDisplay = $aDisplay."<b class ='title' id ='$aResult[question_id]'>".$count.". $aTitle</b>";
                $aDisplay = $aDisplay."</div>";
                
                $aAnswersResult = $aAnswersDAO->getAnswersByQuestionID($aResult[question_id]);
                
                if($aAnswersResult[status]){
                    foreach($aAnswersResult[resultsArray] as $aAnswer){
                        $aDisplay = $aDisplay."<div class ='answersStyle'>";
                        $image = "";
                        foreach($data->answers as $key => $value) {
                            if($value->questionId == $aResult[question_id]
                                && $value->answerId == $aAnswer[answer_id]){
                                
                                if($aAnswer[correct] == 1){
                                    $image = "<img src ='assets/images/correct.png' title ='Correct answer' width ='15px'/>";
                                    $correctAnswers ++;
                                }else{
                                    $image = "<img src ='assets/images/incorrect.png' title ='Incorrect answer' width ='15px'/>";
                                }
                                
                            }
                        }
                        
                        $aDisplay = $aDisplay."$image $aAnswer[answer_text]";
                        $aDisplay = $aDisplay."</div><hr>";
                    }
                }
                $aDisplay = $aDisplay."</div>";
                $count ++;
            }
            $total = $count - 1;
            $percent = ($correctAnswers / $total) * 100;
            $aDisplay = $aDisplay."<div class ='results_div'>Test score is <b>$percent%</b> - <a href ='learn'>Take more exercises</a></div><hr>";
            return $aDisplay;
        }
        
        
         public static function GetQuestionsAndAnswersForUser($aResults) {
            
            $count = 1;
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<div class ='question_div' id = '$aResult[exercise_id]'>";
                
                $aAnswersDAO = new AnswersDAO();
                
                $aTitle = ucfirst(strtolower($aResult[question_text]));
                
                $aDisplay = $aDisplay."<div>";
                $aDisplay = $aDisplay."<b class ='title' id ='$aResult[question_id]'>".$count.". $aTitle</b>";
                $aDisplay = $aDisplay."</div>";
                
                $aAnswersResult = $aAnswersDAO->getAnswersByQuestionID($aResult[question_id]);
                
                if($aAnswersResult[status]){
                    foreach($aAnswersResult[resultsArray] as $aAnswer){
                        $aDisplay = $aDisplay."<div class ='answersStyle'>";
                        
                        $aDisplay = $aDisplay."<input type ='radio' value ='$aAnswer[answer_id]' name='$aResult[question_id]'/> $aAnswer[answer_text]";
                        $aDisplay = $aDisplay."</div><hr>";
                    }
                }
                $aDisplay = $aDisplay."</div>";
                $count ++;
            }
            return $aDisplay;
        }
        
        public static function GetExercisesForUsers($aResults) {
            $aDisplay = "";
            
            foreach($aResults as $aResult){
                
                $title = ucfirst(strtolower($aResult[exercise_title]));
                $text = ucfirst(strtolower($aResult[exercises_text]));
                $date = date("Y-m-d", strtotime($aResult[date_created]));
                $creator = ucwords(strtolower($aResult[firstname]. " ".$aResult[lastname]));
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[exercise_title])));
                $aDisplay = $aDisplay.'
                                <div style ="margin-left:-5px;margin-right:-5px;border:1px solid #E5E5E5;background:#FCFCFC;padding:10px;margin-bottom:10px;">
                                    <table width ="100%">
                                        <tr>
                                            <td>
                                                <a  class="red" href ="learn?_='.$url.'" style ="font-size:14px">'.$title.'</a> 
                                                <p style ="font-size: 14px">
                                                    <span style ="color:#999999">'.$date.'</span> - '.$text.'
                                                </p>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                     ';
                
            }
            return $aDisplay;
        }
        
        public static function GetExercisesHTMLTable($aResults) {
            $aDisplay = "<table id ='exercises_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th style ='width:25%'>Title</th>"
                    . "<th>Desc</th>"
                    . "<th>Creator</th>"
                    . "<th style ='width:12%'>Date</th>"
                    . "<th>Published</th>"
                    . "<th style ='width:12%'>Action</th>"
                    ."</tr>"
                    . "</thead>";
            
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";
                
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[exercise_title])));

                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay."<a id ='$aResult[exercise_id]' class ='' href ='manage/exercises?_=$url'>".ucfirst(trim(strtolower($aResult[exercise_title]))."</a>");
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst(strtolower($aResult[exercises_text]));
                $aDisplay = $aDisplay."</td>";

                $aDisplay = $aDisplay."</td>";
                   
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst(strtolower($aResult[firstname]));
                $aDisplay = $aDisplay."</td>";
                
                $date = date("Y-m-d", strtotime($aResult[date_created]));
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$date;
                $aDisplay = $aDisplay."</td>";
                
                $published = "No";
                if($aResult[published] == 1){
                    $published = "Yes";
                }
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$published;
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay."<a id ='$aResult[exercise_id]' class ='edit_exercise' href ='$_SERVER[REQUEST_URI]'>Edit Exercise</a>";
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."</tr>";
            }
            return $aDisplay. "</table>";
        }
        
        public static function GetManageUsersHTMLTable($aResults) {
            $aDisplay = "<table id ='item_types_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Names</th>"
                    . "<th>Email</th>"
                    . "<th>Activated</th>"
                    . "<th>Admin</th>"
                    . "<th>Action</th>"
                    ."</tr>"
                    . "</thead>";
            
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst(strtolower($aResult[firstname]))." ".ucfirst(strtolower($aResult[lastname]));
                $aDisplay = $aDisplay."</td>";
                   
            
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.strtolower($aResult[email]);
                $aDisplay = $aDisplay."</td>";
                
                $activate = "No";
                if($aResult[activation_status] == 1){
                    $activate = "Yes";
                }
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$activate;
                $aDisplay = $aDisplay."</td>";
                
                $admin = "No";
                if($aResult[admin_user] == 1){
                    $admin = "Yes";
                }
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$admin;
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td> - ";
                $aDisplay = $aDisplay."<a id ='$aResult[user_id]' class ='edit_user' href ='$_SERVER[REQUEST_URI]'>Edit Access</a>";
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."</tr>";
            }
            return $aDisplay. "</table>";
        }
        /**
         * 
         * @param type $aResults
         * @return type
         */
        public static function GetEntityHTMLList($aResults,$letter) {
            $aDisplay = "<table id ='entity_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay."<p id='large'></p>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th width ='25%'>Description</th>"
                    . "<th width ='25%'>Translation</th>"
                    . "<th width ='5%'>Type</th>"
                    . "<th width ='5%'>Creator</th>"
                    . "<th width ='15%'>Created</th>"
                    . "<th width ='25%'>Action</th>"
                    ."</tr>"
                    . "</thead>";
            
            $aEntityDetails = new EntityDetailsDAO();
            if(is_array ($aResults)){
                foreach($aResults as $aResult){
                    $aDisplay = $aDisplay."<tr>";

                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }

                    $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay."<a target ='_tab' href ='dictionary/xitsonga?_=$url'>".ucfirst($aResult[entity_name])."</a>";
                    $aDisplay = $aDisplay."</td>";

                    $aContent = strip_tags($array[ItemTypeDAO::$ENGLISH_TRANS][content]);

                    $aContent = substr($aContent, 0, 300);

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.ucfirst($aContent);
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.$aResult[description];
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.$aResult[firstname];
                    $aDisplay = $aDisplay."</td>";

                    $date = date("Y-m-d", strtotime($aResult[date_created]));
                    $aDisplay = $aDisplay."<td>";
                    $aDisplay = $aDisplay.$date;
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."<td>";

                    $aDisplay = $aDisplay."<a id ='$aResult[entity_id]' class ='edit_entity' href ='$_SERVER[REQUEST_URI]'>Edit Entity</a>";


                    $aImage = $array[ItemTypeDAO::$IMAGE][content];

                    if($aImage == ""){
                        $aDisplay = $aDisplay." - <a id ='$aResult[entity_id]' class ='edit_image' href ='$_SERVER[REQUEST_URI]'>Add Image</a>";

                    }else{
                        $aDisplay = $aDisplay." - <a id ='$aResult[entity_id]' class ='edit_image' href ='$_SERVER[REQUEST_URI]'>Edit Image</a>";
                        $aDisplay = $aDisplay."<img rel = '".ucfirst($aResult[entity_name])."' src ='assets/images/entity/".$aImage."' alt ='assets/images/entity/".$aImage."' align ='right' width ='30'/>";


                    }
                    $aDisplay = $aDisplay."</td>";

                    $aDisplay = $aDisplay."</tr>";

                    $array = array();
                }
            }
            return $aDisplay. "</table>";
        }
        
        public static function GetDisplayImageHTMLList($aResults, $aPageName, $aSubName) {
            $aDisplay = "";
            $aItemTypeDAO = new ItemTypeDAO();
            $aEntityDetails = new EntityDetailsDAO();
            foreach($aResults as $aResult){
            
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                
                $aImg = $array[ItemTypeDAO::$IMAGE][content];
                if($aImg == ""){
                    $aImg = "no_image.png";
                }
                
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$WEBSITE_LINK][content]);
                
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                $description = ucfirst($typeRecord[resultsArray][2]);
                $aDisplay = $aDisplay."<div class ='list_with_image_div'><p><div style ='max-height:60px;overflow:hidden' class ='pull-left img_div'><img src='assets/images/entity/$aImg' alt='' class='img-rounded pull-left' width='80' /></div><h4><a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a> - ".ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content])."</h4>$description</p><hr></div>";

            }
            return $aDisplay. "";
        }
        
        
        public static function GetDisplayVideoListHTMLList($aResults, $aPageName, $aSubName) {
            $aDisplay = "";
            $aItemTypeDAO = new ItemTypeDAO();
            $aEntityDetails = new EntityDetailsDAO();
            foreach($aResults as $aResult){
            
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                
                $aImg = $array[ItemTypeDAO::$IMAGE][content];
                if($aImg == ""){
                    $aImg = "no_image.png";
                }
                
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$WEBSITE_LINK][content]);
                
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                $description = ucfirst($typeRecord[resultsArray][2]);
                $aDisplay = $aDisplay."<div><p><div style ='max-height:60px;overflow:hidden' class ='pull-left'><img src='assets/images/entity/$aImg' alt='' class='img-rounded pull-left' width='80' /></div><h5><a href ='$aPageName"."?_=$url'>".ucfirst($aResult[entity_name])."</a>"."</h5>$description</p><hr></div>";

            }
            return $aDisplay. "";
        }
        /**
         * 
         * @param type $aResults
         * @param type $aPageName
         * @param type $aSubName
         * @return type
         */
        public static function GetNymsDisplayTABLEHTMLList($aResults, $aPageName,$aSubName) {
            $aDisplay = "<table id ='dictionary_data_table'>";

            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr colspan ='2'>"
                    . "<th>Word</th>"
                    . "<th>".ucfirst($aSubName)."</th>"
                    ."</tr>"
                    . "</thead>";
       
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";

                $aEntityDetails = new EntityDetailsDAO();
                 
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                
                $aDisplay = $aDisplay."<td class ='first_td'>";
       
                $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a> - ";
                $aDisplay = $aDisplay.$array[ItemTypeDAO::$ENGLISH_TRANS][content];
                $aDisplay = $aDisplay."</td>";
                
                $aTypeRequest = ucfirst(strtolower($aSubName));

                $content = $array[$aTypeRequest][content];
                
                if($content == ""){
                    $content = "-";
                }
                $count = 100;
                
                $content = HTMLDisplay::ucfirstSentence(str_replace(",",". ",$content,$count));
                $content = HTMLDisplay::ucfirstSentence(str_replace("‚",". ",$content,$count));

                $output = rtrim($content , '.');
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst($output );
                
                
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."</tr>";
                
                $array = array();
            }
            return $aDisplay. "</table>";
        }
        
        public static function GetVerbsDisplayTABLEHTMLList($aResults, $aPageName,$aSubName) {
            $aDisplay = "<table id ='dictionary_data_table'>";
           
            $aItemTypeDAO = new ItemTypeDAO();
            
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Verb</th>"
                    . "<th>".ucfirst($aSubName)." tense</th>"
                    ."</tr>"
                    . "</thead>";
       
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";

                $aEntityDetails = new EntityDetailsDAO();
                 
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                
                $aDisplay = $aDisplay."<td class ='first_td'>";
       
                $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a>";
                $aDisplay = $aDisplay."</td>";
                
                $content = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                if(strtolower($aSubName) == "continuous"){
                    $content = $array[ItemTypeDAO::$FUTURE_TENSE][content];
                } else  if(strtolower($aSubName) == "present"){
                    $content = $array[ItemTypeDAO::$PRESENT_TENSE][content];
                } else if(strtolower($aSubName) == "past"){
                    $content = $array[ItemTypeDAO::$PAST_TENSE][content];
                }
                if($content == ""){
                    $content = "-";
                }
                $count = 100;
                $content = HTMLDisplay::ucfirstSentence(str_replace(",",". ",$content,$count));
                $content = HTMLDisplay::ucfirstSentence(str_replace("‚",". ",$content,$count));
                 
                
                $output = rtrim($content , '.');
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst($output );
                $aDisplay = $aDisplay."</td>";
                
                
                $aDisplay = $aDisplay."</tr>";
                
                $array = array();
            }
            return $aDisplay. "</table>";
        }
        
        public static function GetEntityDisplayTABLEHTMLList($aResults, $aPageName, $aSubName) {           
            $aItemTypeDAO = new ItemTypeDAO();
            
            $aItemType = strtolower($aResults[0][description]);
           $aDisplay = $aDisplay."<ul class ='dictionary_list2'>";
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<li class =''>";
                $aEntityDetails = new EntityDetailsDAO();
                 
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
                $type = lcfirst($typeRecord[resultsArray][2]);
                
                $content = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                $count = 100;
                $content = HTMLDisplay::ucfirstSentence(str_replace(",",". ",$content,$count));
                $content = HTMLDisplay::ucfirstSentence(str_replace("‚",". ",$content,$count));
                 
                $output = rtrim($content , '.');
                
                $explaination = "";
                if($type == "verbs"){
                    $type = "V.";
                }elseif($type == "adverbs"){
                    $type = "Adv.";
                }else if($type =="nouns"){
                    $type = "N.";
                }else if($type =="pronouns"){
                    $type = "Pron.";
                }else if($type == "adjectives"){
                    $type = "Adj.";
                } else if($type == "conjunctions"){
                    $type = "Conj.";
                }else if($type == "--Default--"){
                    $type = "";
                }
                
                if(strlen($array[ItemTypeDAO::$ORIGINAL][content]) > 2){
                    $original = "{ <span class ='type'>Origin - "."<a href ='$aPageName/$aSubName"."?_=".$array[ItemTypeDAO::$ORIGINAL][content]."'>".ucfirst($array[ItemTypeDAO::$ORIGINAL][content])."</a></span> }";
                }else{
                   $original = "";
                }
                
                
                if(strlen(trim($array[ItemTypeDAO::$WEBSITE_LINK][content])) > 0){
                    $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$WEBSITE_LINK][content]);
                    $desc = lcfirst($typeRecord[resultsArray][2]);
                    if($desc != "--Default--"){
                        if(strtolower($desc) == "names") {
                            $explaination = " { <span class ='type'><a href ='people/$desc'>".ucfirst($desc)."</a></span> } "; 
                        }elseif(strtolower($desc) == "greeting" || strtolower($desc) == "conjunctions") {
                            $explaination = " { <span class ='type'><a href ='grammar/$desc'>".ucfirst($desc)."</a></span> } "; 
                        }else {
                            $explaination = " { <span class ='type'><a href ='dictionary/$desc'>".ucfirst($desc)."</a></span> } ";
                        }
                    }else {
                        $explaination ="";
                    }
                }else{
                    $explaination = "";
                }
                
                if($type != ""){
                    $type = "<span class ='type2'>".ucfirst($type)."</span>";
                }
                
                if($aItemType == "xitsonga" OR $aItemType == "english"){
                    $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>"."<b class ='firstLetter'>".substr(ucfirst($aResult[entity_name]),0,  1)."</b>".  substr(ucfirst($aResult[entity_name]),1,  strlen($aResult[entity_name]))."</a> - ";
                }else{
                    $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a> - ";
                }
                $aDisplay = $aDisplay.ucfirst($output);
                
                if($aItemType == "xitsonga" OR $aItemType == "english"){
                    $aDisplay = $aDisplay." ";
                    $aDisplay = $aDisplay." <span class ='type'>".ucfirst($type)."$explaination</span>". $original; 
                }
                
                $aDisplay = $aDisplay."</li>";
                
                $array = array();
            }
            $aDisplay = $aDisplay."</ul>";
            return $aDisplay;
        }
        
        public static function GetPhrasesDisplayTABLEHTMLList($aResults, $aPageName, $aSubName) {           
            $aItemTypeDAO = new ItemTypeDAO();
            
            $aItemType = strtolower($aResults[0][description]);
            $aDisplay = "<table id ='dictionary_data_table' class='display' cellspacing='0'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Xitsonga</th>"
                    . "<th>English</th>"
                    ."</tr>"
                    . "</thead>";
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";
                $aEntityDetails = new EntityDetailsDAO();
                 
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
                $type = lcfirst($typeRecord[resultsArray][2]);
                
                $content = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                if($aItemType == "proverbs" || $aItemType == "idioms"){
                    $explaination = $array[ItemTypeDAO::$EXPLAINATION][content];
                    if($explaination != ""){
                        $content = "<b>Direct - </b>".$array[ItemTypeDAO::$ENGLISH_TRANS][content]. " <br/><b>Meaning - </b>$explaination";
                    } 
                } else {
                    $count = 100;
                    $content = HTMLDisplay::ucfirstSentence(str_replace(",",". ",$content,$count));
                    $content = HTMLDisplay::ucfirstSentence(str_replace("‚",". ",$content,$count));
                }
                 
                $output = rtrim($content , '.');
                
                $aDisplay = $aDisplay."<td style ='width:40%'>";
                $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a>";
                $aDisplay = $aDisplay."</td>";
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst($output );

                $aDisplay = $aDisplay."</td>";
                $aDisplay = $aDisplay."</tr>";
                
                $array = array();
            }
            $aDisplay = $aDisplay."</table>";
            return $aDisplay;
        }
        
       public static function ucfirstSentence($str){
	     $str = ucfirst(($str));
	     $str = preg_replace_callback('/([.!?])\s*(\w)/', 
	       create_function('$matches', 'return strtoupper($matches[0]);'), $str);
	     return $str;
	}
        
        public static function GetSearchEntityDisplayTABLEHTMLList($aResults, $aPageName, $aSubName,$width = NULL) {
            $aDisplay = "<div class = 'row'>";
           
            $aItemTypeDAO = new ItemTypeDAO();
                        
            $aDisplay = $aDisplay."<ul class ='dictionary_list' style='padding:10px;padding-left:25px;'>";
       
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<li>";

                $aEntityDetails = new EntityDetailsDAO();
                 
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                $type = "";
                if(count_chars($array[ItemTypeDAO::$DICTIONARY_TYPE][content]) > 0){
                    $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
                    $type = lcfirst($typeRecord[resultsArray][2]);
                    
                    if($type == "verbs"){
                        $type = "V.";
                    }else if($type =="nouns"){
                         $type = "N.";
                    }else if($type == "adjectives"){
                        $type = "Adj.";
                    } else if($type == "adverbs"){
                        $type = "Adv.";
                    }else if($type == "conjuctions"){
                        $type = "Conj.";
                    }else{
                        $type ="";
                    }
                    
                    if($type ==""){
                        $type = "<span class ='type2'>".$type."</span>";
                    }
                }
                
                //$aDisplay = $aDisplay."<td style ='width:20%'>";
                //$aDisplay = $aDisplay.ucfirst($aResult[description]);
                //$aDisplay = $aDisplay."</td>";
                $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a> - ";
                //$aDisplay = $aDisplay."</td>";
                
                //$aDisplay = $aDisplay."<td>";
                 $aDisplay = $aDisplay.ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content])." ".$type;
                //$aDisplay = $aDisplay."</td>";
                
                //$aDisplay = $aDisplay."<td>";
               // $aDisplay = $aDisplay.ucfirst($type);
                //$aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."</li>";
                
                $array = array();
            }
            $aDisplay = $aDisplay."<ul>";
            return $aDisplay. "</div>";
        }
        
        public static function GetRichTextEntityDisplayTABLEHTMLList($aResults, $aPageName, $aSubName) {
            $aDisplay = "<ul  class ='dictionary_list2'>";
            
            foreach($aResults as $aResult){
                $aEntityDetails = new EntityDetailsDAO();
                 
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                
                $aDisplay = $aDisplay."<li>";
                
                $aAuthor = $aResult[firstname];
                
                $aContent = strip_tags($array[ItemTypeDAO::$ENGLISH_TRANS][content]);
                
                $aContent = substr($aContent, 0, 100);
                
                $aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a>";
           
                $aDisplay = $aDisplay."</li>";
                
                $array = array();
            }
            return $aDisplay."</ul>";
        }
        
        public static function GetTABLEHTMLList($xi,$en) {
            $aDisplay = "<table id ='dictionary_data_table' class='display' cellspacing='0'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Xitsonga</th>"
                    . "<th>English</th>"
                    ."</tr>"
                    . "</thead>";
             $aNumberCount = 0;
            while($aNumberCount < count($en)){                
                $aDisplay = $aDisplay."<tr>";
                
                $aDisplay = $aDisplay."<td class ='first_td'>";
                //$aDisplay = $aDisplay."<a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a>";
                $aDisplay = $aDisplay.ucfirst($xi[$aNumberCount]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay."".ucfirst($en[$aNumberCount]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."</tr>";
                $aNumberCount ++;
            }

            return $aDisplay. "</table>";
        }
        
        
        public static function GetEntityDisplayHTMLList($aResults, $aPageName, $aSubName) {
            $aDisplay = "<ul class ='list'>";
       
            foreach($aResults as $aResult){
                 $aEntityDetails = new EntityDetailsDAO();
            
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                $type = "";
                if(count_chars($array[ItemTypeDAO::$DICTIONARY_TYPE][content]) > 0){
                    $aItemTypeDAO = new ItemTypeDAO();
                    $typeRecord = $aItemTypeDAO->getItemTypeByID($array[ItemTypeDAO::$DICTIONARY_TYPE][content]);
                    $type = lcfirst($typeRecord[resultsArray][2]);
                    
                    if($type == "verbs"){
                        $type = "v. ";
                    }else if($type =="nouns"){
                         $type = "n. ";
                    }else if($type == "adjectives"){
                        $type = "adj. ";
                    }else{
                        $type ="";
                    }
                }
                
                $aDisplay = $aDisplay."<li><a href ='$aPageName/$aSubName"."?_=$url'>".ucfirst($aResult[entity_name])."</a> &mdash; $type".ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content])."</li>";
                $array = array();
            }
            return $aDisplay. "</ul>";
        }
        
         public static function GetSearchDisplayHTMLList($aResults) {
            $aDisplay = "";
       
            foreach($aResults as $aResult){
                 $aEntityDetails = new EntityDetailsDAO();
            
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                
                if(ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) == "-"){
                    $aDisplay = $aDisplay."<div class='saying_div'><span><a href ='$aPageName/$aSubName?_=$url'>".ucfirst($aResult[entity_name])."</a>"."</span></div>";
                }else{
                    $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=".$array[ItemTypeDAO::$ENGLISH_TRANS][content]));
                    $city = $geo["geoplugin_city"];
                    
                    if($city == ""){
                        $city ="Unknown City";
                    }else{
                        $city =$city.", ".$geo["geoplugin_regionName"]. ", ".$geo["geoplugin_countryName"];
                    }
                    $aDisplay = $aDisplay."<div class='saying_div'><span><b style ='color:gray'>".ucfirst($aResult[entity_name])."</b> &mdash; ".($city)."</span></div>";
                }
                $array = array();
            }
            return $aDisplay. "";
        }
        
       public static function GetSayingDisplayHTMLList($aResults, $aPageName, $aSubName) {
            $aDisplay = "";
       
            foreach($aResults as $aResult){
                 $aEntityDetails = new EntityDetailsDAO();
            
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[entity_name])));
                
                if(ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) == "-" OR $aSubName =="surnames"){
                    $aDisplay = $aDisplay."<div class='saying_div'><span><a href ='$aPageName/$aSubName?_=$url'>".ucfirst($aResult[entity_name])."</a>"."</span></div>";
                }else{
                    $aDisplay = $aDisplay."<div class='saying_div'><b style ='color:gray'>$aResult[description]</b> - <span><a href ='$aPageName/$aSubName?_=$url'>".ucfirst($aResult[entity_name])."</a> &mdash; ".ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content])."</span></div>";
                }
                $array = array();
            }
            return $aDisplay. "";
        }
        
        public static function GetAuditTrailHTMLList($aResults) {
            $aDisplay = "<table id ='dictionary_data_table'>";
             $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Entity</th>"
                    . "<th>Updated</th>"
                    . "<th>Previous</th>"
                    . "<th>Current public copy</th>"
                    . "<th>Datetime</th>"
                    . "</tr>"
                    . "</thead>";
            $aDisplay = $aDisplay."<tbody>";

            $aEntityDAO = new EntityDAO();
            $aEntityDetail = new EntityDetailsDAO();
            foreach($aResults as $aResult){
                $aValueCheck = str_replace("_", "", $aResult[new_value]);
                if(!is_numeric($aValueCheck)){
                    if(!is_numeric($aResult[new_value])){
                        $data["id"] = $aResult[item_id];
                        $aEntityResults = $aEntityDAO->getEntityById($data);
                        
                        $aEntityName = "";
                        $aEditedType = "";
                        if($aEntityResults[status]){
                            $aEntityName = $aEntityResults[resultsArray][entity_name];
                            
                            $aDisplay = $aDisplay."<tr>";

                            $aDisplay = $aDisplay."<td>";
                            $aDisplay = $aDisplay."<a target ='_tab' href='dictionary/xitsonga?_=$aEntityName'>".ucfirst($aEntityName)."</a>";
                            $aDisplay = $aDisplay."</td>";

                            $aDisplay = $aDisplay."<td>";
                            $aDisplay = $aDisplay.ucfirst($aResult[new_value]);
                            $aDisplay = $aDisplay."</td>";

                            $aDisplay = $aDisplay."<td>";
                            $aDisplay = $aDisplay.ucfirst($aResult[previous]);
                            $aDisplay = $aDisplay."</td>";

                            $aDisplay = $aDisplay."<td>";
                            
                            if(ucfirst($aEntityName) == ucfirst($aResult[new_value])){
                                $aDisplay = $aDisplay."Yes";
                            }else{
                                $aDisplay = $aDisplay."Possibly changed";
                            }
                            
                            $aDisplay = $aDisplay."</td>";
                            
                            $aDisplay = $aDisplay."<td>";
                            $aDisplay = $aDisplay.ucfirst($aResult[date_created]);
                            $aDisplay = $aDisplay."</td>";

                            $aDisplay = $aDisplay."</tr>";
                        }
                    }
                }
            }
            return $aDisplay. "</table>";
        }
        
        public static function GetAuditAPICallsList($aResults) {
            $aDisplay = "<ul class ='dictionary_list2' style ='font-size:14px'>";
            
            foreach($aResults as $aResult){
                $url = HTMLDisplay::generateURL(strtolower(str_replace(" ","_",$aResult[item])));
                
                $aDisplay = $aDisplay."<li>";
                $aDisplay = $aDisplay."<a href ='dictionary/xitsonga?_=$url'>".ucfirst($aResult[item])."</a>";
                
                $aDisplay = $aDisplay." - ";
                
                 $aEntityDAO = new EntityDAO();
            
	
	            
	            $aResultsItem = $aEntityDAO->getEntityByName($aResult[item]);
                   if($aResultsItem ['status']){
	                $aEntityDetails = new EntityDetailsDAO();
	                
	            	$aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResultsItem[resultsArray][0][entity_id]);
	            	if($aDetailResults['status']){
	               	 	$array = array();
	               		 foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
		                  $array[$aDetailResult[description]] = $aDetailResult;
	                	}
	            	}
	                
	             
	                $aContent = ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]);
	              
	                $aDisplay = $aDisplay.$aContent." (".$aResult[views].")";
                   } else {
                   	$aDisplay = $aDisplay."<span style ='color:red'>Deleted</span>";
                   }
  
                $aDisplay = $aDisplay."</li>";
            }
            $aDisplay = $aDisplay."</tbody>";

            return $aDisplay. "</ul>";
        }
        
        public static function GetAuditAPICallsHTMLList($aResults) {
            $aDisplay = "<table id ='audits_api_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>#</th>"
                    . "<th>Item</th>"
                    . "<th>Translation</th>"
                    . "<th>Type</th>"
                    . "<th>API Caller</th>"
		    . "<th>Date created</th>"
                    ."</tr>"
                    . "</thead>";
            $index = count($aResults);
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";
                
		$aDisplay = $aDisplay."<td width ='7%'>";
                $aDisplay = $aDisplay.$index; 
                $aDisplay = $aDisplay."</td>";

                $aDisplay = $aDisplay."<td width ='25%'>";
                $aDisplay = $aDisplay.ucfirst($aResult[item]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.ucfirst(htmlspecialchars($aResult[translation]));
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td width ='15%'>";
                $aDisplay = $aDisplay.ucfirst($aResult[type]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td width ='12%'>";
                $aDisplay = $aDisplay.ucfirst($aResult[caller]);
                $aDisplay = $aDisplay."</td>";
				
		$aDisplay = $aDisplay."<td width ='18%'>";
                $aDisplay = $aDisplay.$aResult[date_created];
                $aDisplay = $aDisplay."</td>";
   
                $aDisplay = $aDisplay."</tr>";
				
		$index --;
            }
            $aDisplay = $aDisplay."</tbody>";

            return $aDisplay. "</table>";
        }
        
        
        public static function GetTranslationConfigHTMLList($aResults) {
            $aDisplay = "<table id ='audits_api_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>#</th>"
                    . "<th>Item</th>"
                    . "<th>Replace</th>"
                    . "<th>Pattern</th>"
                    . "<th>Language</th>"
                    . "<th>SwapLeft</th>"
                    . "<th>SwapRight</th>"
                    . "<th>PushFirst</th>"
                    . "<th>PushLast</th>"
                    ."</tr>"
                    . "</thead>";
            $index = count($aResults);
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";
                
		$aDisplay = $aDisplay."<td width ='7%'>";
                $aDisplay = $aDisplay.$index; 
                $aDisplay = $aDisplay."</td>";

                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[item]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[replacement]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[pattern]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[language]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[swap_left]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[swap_right]);
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.($aResult[push_first]);
                $aDisplay = $aDisplay."</td>";
				
		$aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$aResult[push_last];
                $aDisplay = $aDisplay."</td>";
   
                $aDisplay = $aDisplay."</tr>";
				
		$index --;
            }
            $aDisplay = $aDisplay."</tbody>";

            return $aDisplay. "</table>";
        }
        
        
        public static function GetItemTypesHTMLList($aResults) {
            $aDisplay = "<table id ='item_types_data_table' class='display' cellspacing='0' width='100%'>";
            $aDisplay = $aDisplay
                    ."<thead>"
                    ."<tr>"
                    . "<th>Description</th>"
                    . "<th>Type</th>"
                    . "<th>Creator</th>"
                    . "<th>Date created</th>"
                    . "<th>Action</th>"
                    ."</tr>"
                    . "</thead>";
            
            foreach($aResults as $aResult){
                $aDisplay = $aDisplay."<tr>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$aResult[description];
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                if($aResult[type] == '1'){
                    $aDisplay = $aDisplay."Main";
                }elseif($aResult[type] == '2'){
                    $aDisplay = $aDisplay."Content";
                }elseif($aResult[type] == '3'){
                    $aDisplay = $aDisplay."Type";
                }elseif($aResult[type] == '4'){
                    $aDisplay = $aDisplay."Tag";
                }
                elseif($aResult[type] == '5'){
                    $aDisplay = $aDisplay."Description";
                }
                
                $aDisplay = $aDisplay."</td>";
                   
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$aResult[firstname]. " ".$aResult[lastname];
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay.$aResult[date_created];
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."<td>";
                $aDisplay = $aDisplay."<a id ='$aResult[item_type]' class ='edit_type' href ='$_SERVER[REQUEST_URI]'>Edit Type</a>";
                $aDisplay = $aDisplay."</td>";
                
                $aDisplay = $aDisplay."</tr>";
            }
            return $aDisplay. "</table>";
        }
        
        public static function getMAPScript($aAddress, $aEntityName, $ID) {
             return "<script>
                  $(document).ready(function(e){
                    GMaps.geocode({
                        address:\" $aAddress\",
                        callback: function(results, status) {
                            if (status == 'OK') {
                                var latlng = results[0].geometry.location;
                                var vLat = latlng.lat();
                                var vLan = latlng.lng();
                                var map = new GMaps({el: '#$ID', lat: vLat, lng: vLan});
                                map.addMarker({
                                    lat: latlng.lat(),
                                    lng: latlng.lng(),
                                    infoWindow: { content: \"$aEntityName\" },
                                    title: \"$aEntityName\"
                                });

                            }
                        }
                    })
                   });
            </script>";
        }
        /**
         * 
         * @param type $aURL
         */
        public static function generateURL($aURL) {
            return str_replace("'","*", $aURL);
        }
        /**
         * Returns script for playing audio
         * 
         * @param PHPArray aArray
         * @return HTML content
         */
        public static function getAudioScript($aArray) {
            $aReturn = "";
            
            foreach ($aArray as $key => $value) {
                if($key == 0 && $key < count($aArray) - 1) {
                    $aReturn = $aReturn.'{alias:"s'.($key + 1).'",name:"'.$value.'_0"},';
                }else if($key == count($aArray) - 1) {
                    $aReturn = $aReturn.'{alias:"s'.($key + 1).'",name:"'.$value.'_2"},';
                }else{
                    $aReturn = $aReturn.'{alias:"s'.($key + 1).'",name:"'.$value.'_1"},';
                }
            }
             
            return '<script>
                       ion.sound({
                          sounds: [
                            '.$aReturn.'
                          ],
                          path: "assets/audio/reader/",
                          preload: true,
                          multiplay: false,
                          volume: 0.9,
                          scope: this // optional scope
                      });
            </script>';
        }
        /**
         * Returns div for rating
         * 
         * @param String aID
         * @param String aRatingValue
         * @return HTML content
         */
        public static function getRatingPlugin($aID,$aRatingValue, $rating = NULL, $people = NULL){
            $aRatingMesssage = "Item has no rating. ";
            if($aRatingValue != 0){
                if($people > 1) {
                    $aRatingMesssage = "Item rated $aRatingValue"." by $people people. ";
                } else {
                    $aRatingMesssage = "Item rated $aRatingValue"." by $people person. ";
                }
            }
            $aReturn = $aReturn."<span class ='rating_message'>$aRatingMesssage</span><span class ='rating_message'>Help improve content quality by rating below or <a id ='openSendUserMailModalButton' href ='$_SERVER[REQUEST_URI]#'>sending a suggestion</a></span>";
            $aReturn = $aReturn.'<div id="star-rating" class ="star-rating">';
            
            for($count = 1; $count < 6;$count ++){
                if($aRatingValue == $count){
                    $aReturn = $aReturn."<input type='radio' name='example' class='rating' value='$count' checked='checked'/>";
                }else{
                    $aReturn = $aReturn."<input type='radio' name='example' class='rating' value='$count' />";
                }
            }
            
            $aReturn = $aReturn."</div>";
            
            $aReturn = $aReturn.HTMLDisplay::getDialogScript();
            
            return $aReturn;
        }
        
        public static function getDialogScript() {
            return '<script>
                 $(document).ready(function(e){
                 
                    $("#send_suggestion_email").click(function(e){
                        e.preventDefault();
                        
                        var link = window.location.href.split("#");
                        
                        var name = $(".user_suggestions_send_form #main").val();
                        var aID = $(".user_suggestions_send_form #aID").val();
                        var vType = $(".user_suggestions_send_form #Type").val();
                        var vTypeValue = $(".user_suggestions_send_form #TypeValue").val();
                        
                        var email = $(".user_suggestions_send_form #email").val();
                        var suggestion = $(".user_suggestions_send_form #suggestion").val();
                        
                        var content = $(".user_suggestions_send_form #translation").val();
                        var aDetailTypeId = $(".user_suggestions_send_form #aDetailTypeId").val();
                        var aDetailTypeName = $(".user_suggestions_send_form #aDetailTypeName").val();
                        
                        var url = link;
                        
                        var vDetail  = {};
                        vDetail[0] = {
                            "id": aDetailTypeId,
                            "itemType":aDetailTypeName,
                            "content": content
                        };
                        
                        var vItemJSON = {
                            "email": email,
                            "suggestion": suggestion,
                            "url":url[0],
                            "id": aID,
                            "name": name,
                            "itemType": vType,
                            "typeValue": vTypeValue,
                            "deleteItem": 1,
                            "details" : vDetail
                        };

                        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.send_suggestion_email,vItemJSON);
                    });
            
                    
                    $(".cancel").click(function(e){
                        $("#overlay").remove();
                    });
                    
                    $(".close").click(function(e){
                        $("#overlay").remove();
                    });
                    
                    $(document).on("click","#openSendUserMailModalButton",function(e){
                        e.preventDefault(); 

                        var aLink = window.location.href;
                            
                        aLink = aLink.split("#");
                        var overlay = jQuery('."'".'<div id="overlay"> </div>'."'".');
                        overlay.appendTo(document.body);
                        
                        window.location = aLink[0]  + "#openSendUserMailModal";
                    });
                  });
            </script>';
        }
        
        public static function CalculateTimeSpan($date){
            $seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime($date);

            $months = floor($seconds / (3600*24*30));
            $day = floor($seconds / (3600*24));
            $hours = floor($seconds / 3600);
            $mins = floor(($seconds - ($hours*3600)) / 60);
            $secs = floor($seconds % 60);

            if($seconds < 60){
                $time = $secs." seconds ago";
            }else if($seconds < 60*60 ){
                $time = $mins." min ago";
            }else if($seconds < 24*60*60){
                $time = $hours." hours ago";
            }else if($seconds < 24*60*60){
                $time = $day." day ago";
            }else{
                $time = $months." month ago";
            }
            return $time;
        }
    }

?>
