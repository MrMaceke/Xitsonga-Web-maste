<?php
    require_once "EntityDetailsEntity.php";
    require_once "ItemTypeDAO.php";
    require_once "EntityDAO.php";
    require_once "AuditDAO.php";
    require_once "AnswersDAO.php";
    require_once "DefinationCacheDAO.php";
    require_once "Blockspring.php";
    require_once 'AudioReader.php';
    require_once 'TranslationDAO.php';
    require_once 'TranslationConfigDAO.php';
    require_once 'constants.php';
    require_once 'JsonUtils.php';
    /**
     * 
     */
    class TranslatorUtil{
        public function translateEnglishToXitsonga($textString, $data) { 
            $aJsonUtils = new JsonUtils();
            $aEntityDAO = new EntityDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aTranslationDAO = new TranslationDAO();

            $text = trim(strtolower(str_replace("_","",$textString)));
            $text = trim(strtolower(str_replace("?","",$textString)));
            $text = trim(strtolower(str_replace(".","",$textString)));
            $text = trim(strtolower(str_replace("’","'",$textString)));
            
            if(str_word_count($text) > 30) {
                return $aJsonUtils->successFeedback("<we cannot handle more than 30 words>",OPERATION_SUCCESS);
            }
            
            $fromLanguage = $data->langauge;
            $aExclude = FALSE;
            $aResults = $aEntityDAO->findBestEntitySearchByName($text,$fromLanguage,$aExclude);
            if($aResults[status]){
                foreach($aResults['resultsArray'] as $aResult){
                    $aEntityDetails = new EntityDetailsDAO();
            
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                    
                    $aTemp = $aItemTypeDAO->getItemTypeByID($aResult[item_type]);
                    $type = strtolower($aTemp[resultsArray][2]);
                    $word = $aResult[entity_name];
                    $translation = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                    if(strtolower($fromLanguage) != strtolower($type) && $type != "phrases"){
                        $translation = $word;
                    }
                    
                    if(strtolower($translation) == strtolower($text)) {
                        $translation = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                    }
                    
                    if(strtolower($translation) == strtolower($text)) {
                        $translation = $word;
                    }
                    
                    if(strtolower($fromLanguage) == strtolower($type)){
                        break;
                    }
                }               
            }
            
            if($translation != "-" && $translation != "") {
                $aSearch = TranslatorUtil::firstWord($translation);
   
                $aTranslationDAO->AddTranslation($data->text, strtolower($aSearch), $data->langauge, "Direct Translation", 5);
                
                return $aJsonUtils->successFeedback(strtolower($aSearch), OPERATION_SUCCESS);
            } else {
                $format = $text;
                if($data->langauge == "english") {
                    $aTranslationConfigDAO = new TranslationConfigDAO();
                    $results = $aTranslationConfigDAO->getTranslationConfigs("english");
                    if(!$results[status]) {
                        return $aJsonUtils->successFeedback("System unavailable at this time", OPERATION_SUCCESS);
                    }
                    
                    $format = TranslatorUtil::replaceKnown($format, $results[resultsArray]);    
                    $common = TranslatorUtil::commonHash($results[resultsArray]);
                    
                    $known = array();
                    $words = explode(" ", $format);
                    for($wordIndex = 0; $wordIndex < count($words); $wordIndex ++){
                        $tempWord = $words[$wordIndex];
                        if(array_key_exists($tempWord, $common)){
                           $known[$tempWord] = $common[$tempWord];
                        }
                    }
                } else if($data->langauge == "xitsonga") {
                    $aTranslationConfigDAO = new TranslationConfigDAO();
                    $results = $aTranslationConfigDAO->getTranslationConfigs("xitsonga");
                    if(!$results[status]) {
                        return $aJsonUtils->successFeedback("Translator unavailable at this time", OPERATION_SUCCESS);
                    }
                    
                    $format = TranslatorUtil::replaceKnown($format, $results[resultsArray]); 
                    $common = TranslatorUtil::commonHash($results[resultsArray]);
                                       
                    $singular = array (
                        "my"=>"mine",
                        "your"=>"yours",
                        "their"=>"theirs",
                        "our"=>"ours"
                    );
                    
                    $known = array();
                    $words = explode(" ", $format);
                    for($wordIndex = 0; $wordIndex < count($words); $wordIndex ++){
                        $tempWord = $words[$wordIndex];
                        if(array_key_exists($tempWord, $common)){
                           $known[$tempWord] = $common[$tempWord];
                        }
                    }
                }
                
                $build = "";
                for($index = 0; $index < count($words); $index ++){
                    $config = $known[$words[$index]];
                    if(array_key_exists($words[$index], $common)){
                        $currentWord = $config["pattern"];
                    } else {
                       $currentWord = $this->liveTranslateInternal($words[$index], $fromLanguage); 
                    }
                    
                    if($currentWord == "-" || $currentWord == "") {
                       $translation = $translation." <".$words[$index].">";
                    } else {
                        $build = $build." ".$words[$index];
                        
                        $aSearch = TranslatorUtil::firstWord($currentWord);
                        
                        $translation = $translation." ".$aSearch;
                    }
                }
                
                $translation = trim(TranslatorUtil::replaceUnusedConfigs(strtolower($translation)));
                $translation = trim(TranslatorUtil::pushFirstAndLast(strtolower($translation), $known));
                $translation = trim(TranslatorUtil::swapRight(strtolower($translation), $known));
                $translation = trim(TranslatorUtil::swapLeft(strtolower($translation), $known));
                $translation = trim(TranslatorUtil::exchangeLeftAndRight(strtolower($translation), $known));
                $translation = trim(TranslatorUtil::replaceBelongingToConjuction(strtolower($translation)));
                $translation = trim(TranslatorUtil::replaceInquireConjuction(strtolower($translation)));
                $translation = trim(TranslatorUtil::replacVowelsConfigs(strtolower($translation)));
                
                if($translation != "-" && $translation != "") {
                    $aTranslationDAO->AddTranslation($data->text, strtolower($translation), $data->langauge, $build, 3);
                  
                    
                    return $aJsonUtils->successFeedback(strtolower($translation), OPERATION_SUCCESS);
                }

            }
            
            $aTranslationDAO->AddTranslation($data->text, "", $data->language, "Empty", 0);
            
            return $aJsonUtils->successFeedback("<>",OPERATION_SUCCESS);
        }
        
        public static function firstWord($translation) {
            $aSearch = $translation;
            $aSearch = str_replace(",", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);

            $aSearch = explode(".", $aSearch);
            return $aSearch[0];
        }
        
        public static function replaceKnown($string, $records) {
            foreach ($records as $key => $value) {
                $record = $value;
                $string = trim($string);
                $item = trim(strtolower($record[item]));
                $replacement = trim(strtolower($record[replacement]));
                
                if (strpos($string, ' '.$item. ' ') !== false) {
                    $string = str_replace($item, $replacement, $string);
                } else if (preg_match('/^'.$item.' /', $string) === 1) {
                    $string = str_replace($item, $replacement, $string);
                }else if (preg_match('/ '.$item.'$/', $string) === 1) {
                    $string = str_replace($item, $replacement, $string);
                }
            }
            
            return str_replace("  ", " ", strtolower(trim($string)));
        }
        
        public static function pushFirstAndLast($string, $hash) {
            foreach ($hash as $key => $value) {
                $record = $value;
                $pattern = strtolower($record[pattern]);
                
                if($record[push_first] == 1 && $record[push_last] == 1) {
                    $patternSplit = explode("-", $pattern);
                    $string = trim(str_replace(trim($pattern), "", $string));
                                       
                    $string = trim($patternSplit[0]). " ".$string." ".$patternSplit[1];
                }
            }
            return str_replace("  ", " ", strtolower(trim($string)));
        }
        
        public static function swapRight($string, $hash) {
            foreach ($hash as $key => $value) {
                $record = $value;
                $pattern = strtolower($record[pattern]);
                
                $index = strpos($string, $pattern);
                if($record[swap_right] == 1) {
                    $cutString = trim(str_replace(trim($pattern), "", $string));
                    $nextSubstring = trim(substr($cutString, $index + 1));
                    $swapSword = explode(" ", $nextSubstring)[0];
                    
                    $string =  str_replace($swapSword, trim(trim($swapSword). " ".trim($pattern)), $cutString);
                }
            }
            return str_replace("  ", " ", strtolower(trim($string)));
        }
        
        public static function exchangeLeftAndRight($string, $hash) {
            foreach ($hash as $key => $value) {
                $record = $value;
                $pattern = strtolower($record[pattern]);
                
                $index = strpos($string, $pattern);
                if($pattern == "<*>") {
                    $cutString = trim(str_replace(trim($pattern), "", $string));
                    $nextSubstring = trim(substr($cutString, $index + 1));
                    $prevSubstring = trim(substr($cutString, 0, $index));

                    $swapPrevs = explode(" ", $prevSubstring);
                    $swapPrev = $swapPrevs[count($swapPrevs) - 1];
                    if($swapPrev ==""){
                        $swapPrev = $swapPrevs;
                    }
                    
                    $swapNexts = explode(" ", $nextSubstring);
                    $swapNext = $swapNexts[0];
                    if($swapNext ==""){
                        $swapNext = $swapNexts;
                    }
                    
                    $fromReplace = $swapPrev. " ".$pattern." ".$swapNext;
                    $toReplace = $swapNext. " ".$swapPrev;
                    
                    $string = str_replace($fromReplace, $toReplace, strtolower(trim($string)));
                }
            }
            return str_replace("  ", " ", strtolower(trim($string)));
        }
        
        public static function swapLeft($string, $hash) {
            foreach ($hash as $key => $value) {
                $record = $value;
                $pattern = strtolower($record[pattern]);
                
                $index = strpos($string, $pattern);
                
                if($record[swap_left] == 1) {
                    $cutString = trim(str_replace(trim($pattern), "", $string));
                    $prevSubstring = trim(substr($cutString, 0, $index));
                    
                    $swapWords = explode(" ", $prevSubstring);
                    
                    if(is_array($swapWords)) {
                        $swapWord = $swapWords[count($swapWords) - 1];
                        if($swapWord ==""){
                            $swapWord = $prevSubstring;
                        }
                    } else {
                        $swapWord = $prevSubstring;
                    }
                    
                    $string =  str_replace($swapWord, $pattern. " ".$swapWord, $cutString);
                   
                }
            }
            
            return str_replace("  ", " ", strtolower(trim($string)));
        }
        
        public static function replaceInquireConjuction($string) {
            $words = explode(" ", $string);
            $nouns = array (
                "sw"=>"swi",
                "vu"=>"byi",
                "ma"=>"ya",
                "mi"=>"yi",
                "mu"=>"wu",
                "xi"=>"xi",
                "s"=>"ra",
                "r"=>"ri",
            );
            
            $index = 0;
            foreach ($words as $key => $value) {
                $word = $value;
                if($word == "{inquire}") {
                    $wordAfterNext = $words[$index + 2];
                    $firstTwoLetters = substr($wordAfterNext, 0, 2);
                    $firstOneLetters = substr($wordAfterNext, 0, 1);
                    
                    if(array_key_exists($firstTwoLetters, $nouns)) {
                        $string = str_replace("{inquire}", $nouns[$firstTwoLetters], $string);
                    } else if(array_key_exists($firstOneLetters, $nouns)) {
                        $string = str_replace("{inquire}", $nouns[$firstOneLetters], $string);
                    } else {
                        $string = str_replace("{inquire}", "yi", $string);
                    }
                }
                $index ++;
            }
            
            return str_replace("  ", " ", $string);
        }
        
        public static function replaceUnusedConfigs($string) {
            $string = str_replace("#", "", $string);
            
            return str_replace("  ", " ", $string);
        }
        
        public static function replacVowelsConfigs($string) {
            $string = str_replace("{vow}", "a", $string);
            
            return str_replace("  ", " ", $string);
        }
        
        public static function replaceBelongingToConjuction($string) {
            $words = explode(" ", $string);
            $nouns = array (
                "sw"=>"swa",
                "vu"=>"bya",
                "ma"=>"ya",
                "mi"=>"ya",
                "mu"=>"wa",
                "ku"=>"wa",
                "nk"=>"wa",
                "wa"=>"wa",
                "ti"=>"ra",
                "xi"=>"xa",
                "vi"=>"ra",
                "va"=>"va",
                "ti"=>"ta",
                "r"=>"ra",
            );
                        
            $index = 0;
            foreach ($words as $key => $value) {
                $word = $value;
                if($word == "{belong}") {
                    $prevWord = $words[$index - 1];
                    $firstTwoLetters = substr($prevWord, 0, 2);
                    $firstOneLetters = substr($prevWord, 0, 1);
                    
                    if(array_key_exists($firstTwoLetters, $nouns)) {
                        $string = str_replace("{belong}", $nouns[$firstTwoLetters], $string);
                    } else if(array_key_exists($firstOneLetters, $nouns)) {
                        $string = str_replace("{belong}", $nouns[$firstOneLetters], $string);
                    } else {
                        $string = str_replace("{belong}", "ya", $string);
                    }
                }
                $index ++;
            }
            
            return str_replace("  ", " ", $string);
        }
        
        public static function commonHash($records) {
            $common = array();
            foreach ($records as $key => $value) {
                $record = $value;
                $item = strtolower($record[replacement]);
                $common[$item] = $record;
            }
            return $common;
        }
        
        public static function patternHash($records) {
            $common = array();
            foreach ($records as $key => $value) {
                $record = $value;
                $item = strtolower($record[replacement]);
                $common[$item] = $record;
            }
            return $common;
        }
        
        /**
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function liveTranslateInternal($passed, $language,$split = "", $retry = 0) {
            $aEntityDAO = new EntityDAO();
            $aItemTypeDAO = new ItemTypeDAO();
			
            $text = $passed;
            if($split != ""){
                $text = $split;
            }
            $fromLanguage = $language;
            $aExclude = FALSE;
            $aResults = $aEntityDAO->findBestEntitySearchByName($text,$fromLanguage,$aExclude);
            if($aResults[status]){
                foreach($aResults['resultsArray'] as $aResult){
                    $aEntityDetails = new EntityDetailsDAO();
            
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                    if($aDetailResults['status']){
                        $array = array();
                        foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                          $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                    
                    $aTemp = $aItemTypeDAO->getItemTypeByID($aResult[item_type]);
                    $type = strtolower($aTemp[resultsArray][2]);
                    $word = $aResult[entity_name];
                    $translation = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                    if(strtolower($fromLanguage) != strtolower($type) && $type != "phrases"){
                        $translation = $word;
                    }
                    
                    if(strtolower($translation) == strtolower($text)) {
                        $translation = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                    }
                    
                    if(strtolower($translation) == strtolower($text)) {
                        $translation = $word;
                    }
                    
                    if(strtolower($fromLanguage) == strtolower($type)){
                        break;
                    }
                }
                
                if($translation != "-" && $translation != "") {
                    return strtolower($translation);
                } 
            }
            if($retry < 5 && $language == "english") {
                if($retry == 0) {
                    $tempText = rtrim($passed,"ing");
                } else if($retry == 1) {
                    $tempText = rtrim($passed,"s");
                } else if($retry == 2) {
                    $tempText = rtrim($passed,"ed");
                } else if($retry == 3) {
                    $tempText = rtrim($passed,"d");
                } else if($retry == 4) {
                    $tempText = rtrim($passed,"al");
                }
                return $this->liveTranslateInternal($passed, $language,$tempText, ++ $retry);
            } else if($retry < 13 && $language == "xitsonga") {
                if($retry == 0) {
                    $tempText = rtrim($passed,"ile");
                    $tempText = $tempText."a";
                } else if($retry == 1) {
                    $tempText = "n".$passed;
                } else if($retry == 2) {
                    $tempText = ltrim($passed,"swi");
                    $tempText = "xi".$tempText;
                } else if($retry == 3) {
                    $tempText = str_replace("nyana", " ", $passed);
                } else if($retry == 4) {
                    $tempText = str_replace("n", "", $passed);
                } else if($retry == 5) {
                    $tempText = rtrim($passed,"ka");
                    $tempText = $tempText."a";
                } else if($retry == 6) {
                    if (preg_match('/^'."mi".' /', $passed) === 1) {
                        $tempText = substr($passed ,(strlen("mi")));
                    } else {
                        $tempText = $passed;
                    }
                } else if($retry == 7) {
                    if (preg_match('/^'."ma".' /', $passed) === 1) {
                        $tempText = substr($passed ,(strlen("ma")));
                    } else {
                        $tempText = $passed;
                    }
                } else if($retry == 8) {
                    if (preg_match('/^'."swi".' /', $passed) === 1) {
                        $tempText = substr($passed ,(strlen("swi")));
                    } else {
                        $tempText = $passed;
                    }
                } else if($retry == 9) {
                    $tempText = rtrim($passed,"iwe");
                    $tempText = $tempText."a";
                }  else if($retry == 10) {
                    $tempText = rtrim($passed,"ela");
                    $tempText = $tempText."a";
                } else if($retry == 11) {
                    if (preg_match('/^'."e".'/', $passed) === 1) {
                        $tempText = substr($passed ,(strlen("e")));
                    } else {
                        $tempText = $passed;
                    }
                } else if($retry == 12) {
                    if (preg_match('/^'."e".'/', $passed) === 1) {
                        $tempText = substr($passed ,(strlen("e")));
                        $tempText = rtrim($tempText,"ni");
                    } else {
                        $tempText = $passed;
                    }
                }
                return $this->liveTranslateInternal($passed, $language,$tempText, ++ $retry);
            }
            
            return "$passed";
        }
    }