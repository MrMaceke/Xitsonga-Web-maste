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
require_once 'TsongaNumbers.php';
require_once 'TsongaTime.php';
require_once 'constants.php';
require_once 'JsonUtils.php';
require_once 'DictionaryJSONCache.php';
require_once 'SanitizeUtil.php';
require_once 'DemonstrativeUtil.php';
require_once 'PersonalPronounUtil.php';
require_once 'NegativeMeaningUtil.php';
/**
 * 
 */
class TranslatoXitsongaUtil {

    public static $endPattern = "";
    public static $vowel = array("a", "e", "i", "o", "u");

    public static function translateWordEnglishToXitsonga($detectedLanguage, $textString, $data) {
        $aJsonUtils = new JsonUtils();
        $aEntityDAO = new EntityDAO();
        $aItemTypeDAO = new ItemTypeDAO();
        $aTranslationDAO = new TranslationDAO();

        /**
         * Load all caches from files
         */
        $dictionaryCache = file_get_contents(__DIR__ . "/../open/data.json");

        /**
         * Set from and detected language
         */
        $fromLanguage = $detectedLanguage;
        if ($fromLanguage == "") {
            $fromLanguage = $data->langauge;
            $detectedLanguage = $data->langauge;
        }

        /*
         * Removes unwanted characters from text
         */
        $text = SanitizeUtil::removeSpecialCharacters($textString);

        /**
         * 3. Look for direct translation
         * 
         * This code looks for a direct translations and defaults to initial phrase when direct translation not found.
         */
        $translation = DictionaryJSONCache::cacheTranslateInternalWithoutLanguage($dictionaryCache, $text, $text, $detectedLanguage);

        /**
         * Return direct translation if found
         */
        if ($translation != "-" && $translation != "" && $translation != $text) {
            $aSearch = TranslatoXitsongaUtil::firstWord($translation);

            //if ($log) {
                $aTranslationDAO->AddTranslation($textString, ucfirst(strtolower($aSearch)), $data->langauge, "Direct Translation", 5);
            //}

            return $aJsonUtils->successFeedback(ucfirst(strtolower($aSearch)), OPERATION_SUCCESS);
        } else {
            /**
             * Reset translation variable
             */
            $translation = "";
        }

        return $aJsonUtils->successFeedback(ucfirst(strtolower($aSearch)), OPERATION_SUCCESS);
    }

    /**
     * 
     * @param type $detectedLanguage
     * @param type $textString
     * @param type $data
     * @param type $log
     * @return type
     */
    public static function translateXitsongaToEnglish($detectedLanguage, $textString, $data, $log) {
        $aJsonUtils = new JsonUtils();
        $aTranslationDAO = new TranslationDAO();

        /**
         * Load all caches from files
         */
        $dictionaryCache = file_get_contents(__DIR__ . "/../open/data.json");
        $adjectivesFile = file_get_contents("./php/translator_categories/adjectives.txt");
        $verbsFile = file_get_contents("./php/translator_categories/verbs.txt");
        $vanhuFile = file_get_contents("./php/translator_categories/vanhu.txt");
        $vanhuPronounsFile = file_get_contents("./php/translator_categories/vanhu_pronouns.txt");

        $adjectives = explode("\n", $adjectivesFile);
        $verbs = explode("\n", $verbsFile);
        $vanhu = explode("\n", $vanhuFile);
        $vanhuPronouns = explode("\n", $vanhuPronounsFile);

        /**
         * Set from and detected language
         */
        $fromLanguage = $detectedLanguage;

        /*
         * Removes unwanted characters from text
         */
        $text = SanitizeUtil::removeSpecialCharacters($textString);
        
        $spellingConfigsFile = file_get_contents("./php/translator_categories/translator_configs_$detectedLanguage" . "_spelling.json");
        $spellingConfigs = json_decode($spellingConfigsFile);
        
        /**
         * Fix spelling issues
         */
        $text = TranslatorUtil::replaceKnown($text, $spellingConfigs->configs);
        /**
         * 3. Look for direct translation
         * 
         * This code looks for a direct translations and defaults to initial phrase when direct translation not found.
         */
        $translation = DictionaryJSONCache::cacheTranslateInternal($dictionaryCache, $text, $text, $detectedLanguage);
        
        /**
         * Return direct translation if found
         */
        if ($translation != "-" && $translation != "" && $translation != $text) {
            $aSearch = TranslatoXitsongaUtil::firstWord($translation);

            if ($log) {
                $aTranslationDAO->AddTranslation($textString, strtolower($aSearch), $data->langauge, "Direct Translation", 5);
            }

            return $aJsonUtils->successFeedback(strtolower($aSearch), OPERATION_SUCCESS);
        } else {
            /**
             * Reset translation variable
             */
            $translation = "";
        }

        /**
         * 4. Replace known text with configs
         */
        $configsFile = file_get_contents("./php/translator_categories/translator_configs_$detectedLanguage.json");
        $configs = json_decode($configsFile);
        $format = $text;
        
        $format = TranslatoXitsongaUtil::replaceKnown($format, $configs->configs);
        $common = TranslatoXitsongaUtil::commonHash($configs->configs);

        $singular = array(
            "my" => "mine",
            "your" => "yours",
            "their" => "theirs",
            "our" => "ours"
        );

        $known = array();
        $words = explode(" ", $format);
        for ($wordIndex = 0; $wordIndex < count($words); $wordIndex ++) {
            $tempWord = $words[$wordIndex];
            if (array_key_exists($tempWord, $common)) {
                $known[$tempWord] = $common[$tempWord];
            }
        }


        /**
         * 7. Get translations for single words except for items in configs or common list
         */
        $build = "";

        for ($index = 0; $index < count($words); $index ++) {
            $config = $known[$words[$index]];
            /**
             * If current word is in configs items 
             */
            if (array_key_exists($words[$index], $common)) {
                if ($wordToReplace != $words[$index]) {
                    $currentWord = $wordToReplace;
                } else {
                    $currentWord = $config->pattern;
                }
            } else {
                /**
                 * If word is not in config, search dictionary cache for word translation
                 */
                $wordToReplace = $words[$index];

                $currentWord = TranslatoXitsongaUtil::liveTranslateInternal($dictionaryCache, $wordToReplace, $fromLanguage, "", "", 0);
                $currentWord = TranslatoXitsongaUtil::complexWordSearchGrammar($currentWord, $wordToReplace, false);
            }

            if ($currentWord == "-" || $currentWord == "") {
                $translation = $translation . " " . $words[$index] . "";
            } else {
                $build = $build . " * " . $words[$index];

                $aSearch = TranslatoXitsongaUtil::firstWord($currentWord);

                $translation = $translation . " " . $aSearch;
            }
        }

        /**
         * 8. Run all config actions
         */
        /*
         * Replace # with space
         */
        $translation = trim(TranslatoXitsongaUtil::replaceUnusedConfigs("#", strtolower($translation)));

        /**
         * Actions push first and last congig
         */
        $translation = trim(TranslatoXitsongaUtil::pushFirstAndLast(strtolower($translation), $known));

        /**
         * Actions swap right config
         */
        $translation = trim(TranslatoXitsongaUtil::swapRight(strtolower($translation), $known));

        /**
         * Actions swap left config
         */
        $translation = trim(TranslatoXitsongaUtil::swapLeft(strtolower($translation), $known));

        /**
         * Actions left and right exchanges configs
         */
        $translation = trim(TranslatoXitsongaUtil::exchangeLeftAndRight(strtolower($translation), $known));

        /**
         * Replaces {belong} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatoXitsongaUtil::replaceBelongingToConjuction($vanhu, strtolower($translation)));

        /**
         * Replaces {adjectives:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatoXitsongaUtil::replaceAdjectiveConjuction(strtolower($translation)));

        /**
         * Replaces {belong:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatoXitsongaUtil::replaceBelongingToBeforeConjuction(strtolower($translation)));

        /**
         * Replaces {inquire} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatoXitsongaUtil::replaceInquireConjuction($vanhuPronouns, $vanhu, strtolower($translation)));

        /**
         * Replaces {inquire:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatoXitsongaUtil::replaceInquireBeforeConjuction($vanhuPronouns, strtolower($translation)));

        /**
         * Replaces {vow} with "a"
         */
        $translation = trim(TranslatoXitsongaUtil::replacVowelsConfigs(strtolower($translation)));

        /**
         * Removes {remove_space}
         */
        $translation = trim(TranslatoXitsongaUtil::removePatternSpace(strtolower($translation)));

        /**
         * 9. Replace all marked words
         */
        /*
          $translation = trim(TranslatoXitsongaUtil::replaceString(" so ", " leswaku ", strtolower($translation)));
          $translation = trim(TranslatoXitsongaUtil::replaceString("{to:verb}", "ku", strtolower($translation)));
          $translation = trim(TranslatoXitsongaUtil::replaceString("*ngopfu*", "ngopfu", strtolower($translation)));
          $translation = trim(TranslatoXitsongaUtil::replaceString("*u*", "u", strtolower($translation)));
          $translation = trim(TranslatoXitsongaUtil::replaceString("*hi*", "hi", strtolower($translation)));
          $translation = trim(TranslatoXitsongaUtil::replaceString("*va*", "va", strtolower($translation)));

         */

        /**
         * Logs and returns translation
         */
        if ($translation != "-" && $translation != "") {
            if ($log) {
                $aTranslationDAO->AddTranslation($textString, strtolower($translation), $detectedLanguage, $build, 3);
            }
            return $aJsonUtils->successFeedback(trim(strtolower($translation)), OPERATION_SUCCESS);
        }

        return $aJsonUtils->successFeedback("", OPERATION_SUCCESS);
    }

    /**
     * 
     * This function swaps an adjective with the next word after it (if next word is noun).
     *  
     * E.G bap person to person {adjectives:b} bad, which translates to munhu {adjectives:b} biha
     */
    public static function swapAdjectiveWithNextWord($text, $adjectives) {
        $words = explode(" ", trim($text));
        $count = 0;
        $ownership = array("my","our","its");
        foreach ($words as $key => $value) {
            $word = strtolower(trim($value));

            foreach ($adjectives as $key1 => $value1) {
                $exclude = array("happy", "sad");
                
                if (in_array(strtolower(trim($word)), $exclude) == false) {
                    $value1 =  strtolower(trim($value1));
                  
                    if (trim($word) == trim($value1)) {
                        if ($count < count($words)) {    
                            $word0 = $words[$count - 1];
                            $word1 = $word;
                            $word2 = $words[$count + 1];
                            
                            if(in_array($word0, $ownership)) {
                                $text = str_replace($word1 . " " . $word2, $word2. " {adjectives:b:d} " . $word1, $text);
                            } else {
                                $text = str_replace($word1 . " " . $word2, $word2 . " {adjectives:b} " . $word1, $text);
                            }

                            break;
                        }
                    } 
                }
            }
            $count = $count + 1;
        }

        return $text;
    }

    /**
     * 
     * Marks the conjunction if it preceeded by a word class e.i verbs, adjectives
     *  
     * E.G bap person to person {adjectives:b} bad, which translates to munhu {adjectives:b} biha
     */
    public static function markConjuctionPreceededByAWordClass($conjunction, $marker, $text, $verbs) {
        $words = explode(" ", trim($text));
        $count = 0;

        foreach ($words as $key => $value) {
            $word = $value;

            if ($word == $conjunction && strlen($words[$count + 1]) > 1) {
                $nextWord = $words[$count + 1];
                foreach ($verbs as $key1 => $value1) {
                    if (strtolower(trim($nextWord)) == strtolower(trim($value1))) {
                        $text = str_replace($conjunction . " " . $nextWord, $marker . " " . $nextWord, $text);

                        break;
                    }
                }
            }
            $count = $count + 1;
        }

        return $text;
    }

    /**
     * 
     * Marks the conjunction if it preceeded by a verb
     *  
     * E.G bap person to person {adjectives:b} bad, which translates to munhu {adjectives:b} biha
     */
    public static function replaceWordPreceededByAVerb($item, $replacement, $words, $verbs) {
        $count = 0;

        foreach ($words as $key => $value) {
            $word = $value;

            if (trim(strtolower($word)) == strtolower(trim($item)) && strlen($words[$count + 1]) > 1) {
                $nextWord = $words[$count + 1];

                foreach ($verbs as $key1 => $value1) {
                    if (strtolower(trim($nextWord)) == strtolower(trim($value1))) {

                        return $replacement;
                    }
                }
            }
            $count = $count + 1;
        }

        return $item;
    }

    public static function replaceAdjectiveConjuction($string) {
        $words = explode(" ", $string);
        $nouns = array(
            "sw" => "swo",
            "vu" => "byo",
            "ma" => "yo",
            "mi" => "yo",
            "mu" => "wo",
            "ku" => "wo",
            "nk" => "wo",
            "wa" => "wo",
            "ti" => "ro",
            "xi" => "xo",
            "vi" => "ro",
            "va" => "vo",
            "ti" => "to",
            "r" => "ro",
            "g" => "ra",
        );

        $index = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            if ($word == "{adjectives:b}") {
                $prevWord = trim($words[$index - 1]);
                $firstTwoLetters = substr($prevWord, 0, 2);
                $firstOneLetters = substr($prevWord, 0, 1);
                
                if (array_key_exists($firstTwoLetters, $nouns)) {
                    $string = str_replace("{adjectives:b}", $nouns[$firstTwoLetters], $string);
                } else if (array_key_exists($firstOneLetters, $nouns)) {
                    $string = str_replace("{adjectives:b}", $nouns[$firstOneLetters], $string);
                } else {
                    $string = str_replace("{adjectives:b}", "yo", $string);
                }
            } else if($word == "{adjectives:b:d}") {
                $prevWord = TranslatoXitsongaUtil::removeLastCharacter(trim($words[$index - 2]))."o";
                $string = str_replace("{adjectives:b:d}", "$prevWord", $string);
            }
            $index ++;
        }

        return str_replace("  ", " ", $string);
    }

    public static function has_prefix($string, $prefix) {
        return substr($string, 0, strlen($prefix)) == $prefix;
    }

    public static function firstWord($translation) {
        $aSearch = $translation;
        $aSearch = str_replace(",", ".", $aSearch);
        $aSearch = str_replace("‚", ".", $aSearch);
        $aSearch = str_replace("‚", ".", $aSearch);

        $aSearch = explode(".", $aSearch);
        return $aSearch[0];
    }

    public static function removePatternSpace($translation) {
        $translation = str_replace(" {remove_space}", "", $translation);

        return $translation;
    }

    public static function replaceKnown($string, $records) {
        foreach ($records as $key => $value) {
            $record = $value;
            $string = trim($string);
            $item = trim(strtolower($record->item));
            $replacement = trim(strtolower($record->replacement));

            if (strpos($string, ' ' . $item . ' ') !== false) {
                $string = str_replace($item, $replacement, $string);
            } else if (trim($string) == $item) {
                $string = str_replace($item, $replacement, $string);
            } else if (preg_match('/^' . $item . ' /', $string) === 1) {
                $string = str_replace($item, $replacement, $string);
            } else if (preg_match('/ ' . $item . '$/', $string) === 1) {
                $string = str_replace($item, $replacement, $string);
            }
        }

        return str_replace("  ", " ", strtolower(trim($string)));
    }

    public static function pushFirstAndLast($string, $hash) {
        foreach ($hash as $key => $value) {
            $record = $value;
            $pattern = strtolower($record->pattern);

            if ($record->push_first == 1 && $record->push_last == 1) {
                $patternSplit = explode("-", $pattern);

                $tempString = substr($string, strpos($string, $pattern), strlen($string));

                $tempString = trim(str_replace(trim($pattern), "", $tempString));

                $lastArray = explode(" ", $patternSplit[1]);
                $middleArray = explode(" ", $tempString);
                $middleString = "";
                $lastString = "";

                if (count($middleArray) > 1 && false) {
                    $indexCount = 0;
                    foreach ($middleArray as $key => $middleWord) {
                        if ($indexCount == count($middleArray) - 1) {
                            $middleString = $middleString . " " . $lastArray[0];
                        }
                        $middleString = $middleString . " " . $middleWord;
                        $indexCount ++;
                    }
                    $lastString = $lastArray[1];
                } else {
                    $middleString = $tempString;
                    $lastString = $patternSplit[1];
                }

                $tempString = trim($patternSplit[0]) . " " . $middleString . " " . $lastString;

                $string = substr($string, 0, strpos($string, $pattern)) . $tempString;
                break;
            }
        }
        return str_replace("  ", " ", strtolower(trim($string)));
    }

    public static function swapRight($string, $hash) {
        foreach ($hash as $key => $value) {
            $record = $value;
            $pattern = strtolower($record->pattern);

            $index = strpos($string, $pattern);
            if ($record->swap_right == 1) {
                $cutString = trim(str_replace(trim($pattern), "", $string));
                $nextSubstring = trim(substr($cutString, $index + 1));
                $swapSword = explode(" ", $nextSubstring)[0];

                $string = str_replace($swapSword, trim(trim($swapSword) . " " . trim($pattern)), $cutString);
            }
        }
        return str_replace("  ", " ", strtolower(trim($string)));
    }

    public static function exchangeLeftAndRight($string, $hash) {
        foreach ($hash as $key => $value) {
            $record = $value;
            $pattern = strtolower($record->pattern);

            $index = strpos($string, $pattern);
            if ($pattern == "<*>") {
                $cutString = trim(str_replace(trim($pattern), "", $string));
                $nextSubstring = trim(substr($cutString, $index + 1));
                $prevSubstring = trim(substr($cutString, 0, $index));

                $swapPrevs = explode(" ", $prevSubstring);
                $swapPrev = $swapPrevs[count($swapPrevs) - 1];
                if ($swapPrev == "") {
                    $swapPrev = $swapPrevs;
                }

                $swapNexts = explode(" ", $nextSubstring);
                $swapNext = $swapNexts[0];
                if ($swapNext == "") {
                    $swapNext = $swapNexts;
                }

                $fromReplace = $swapPrev . " " . $pattern . " " . $swapNext;
                $toReplace = $swapNext . " " . $swapPrev;

                $string = str_replace($fromReplace, $toReplace, strtolower(trim($string)));
            }
        }
        return str_replace("  ", " ", strtolower(trim($string)));
    }

    public static function manualyExchangeLeftAndRight($string, $pattern) {
        $index = strpos($string, $pattern);
        if ($pattern == "<**>") {
            $cutString = trim(str_replace(trim($pattern), "", $string));
            $nextSubstring = trim(substr($cutString, $index + 1));
            $prevSubstring = trim(substr($cutString, 0, $index));

            $swapPrevs = explode(" ", $prevSubstring);
            $swapPrev = $swapPrevs[count($swapPrevs) - 1];
            if ($swapPrev == "") {
                $swapPrev = $swapPrevs;
            }

            $swapNexts = explode(" ", $nextSubstring);
            $swapNext = $swapNexts[0];
            if ($swapNext == "") {
                $swapNext = $swapNexts;
            }

            $fromReplace = $swapPrev . " " . $pattern . " " . $swapNext;
            $toReplace = $swapNext . " {belong} " . $swapPrev;

            $string = str_replace($fromReplace, $toReplace, strtolower(trim($string)));
        }

        return str_replace("  ", " ", strtolower(trim($string)));
    }

    public static function swapLeft($string, $hash) {
        foreach ($hash as $key => $value) {
            $record = $value;
            $pattern = strtolower($record->pattern);

            $index = strpos($string, $pattern);

            if ($record->swap_left == 1) {
                $cutString = trim(str_replace(trim($pattern), "", $string));
                $prevSubstring = trim(substr($cutString, 0, $index));

                $swapWords = explode(" ", $prevSubstring);

                if (is_array($swapWords)) {
                    $swapWord = $swapWords[count($swapWords) - 1];
                    if ($swapWord == "") {
                        $swapWord = $prevSubstring;
                    }
                } else {
                    $swapWord = $prevSubstring;
                }

                $string = str_replace($swapWord, $pattern . " " . $swapWord, $cutString);
            }
        }

        return str_replace("  ", " ", strtolower(trim($string)));
    }

    public static function replaceInquireConjuction($peoplePronouns, $people, $string) {
        $words = explode(" ", $string);
        $nouns = array(
            "sw" => "swi",
            "vu" => "byi",
            "ma" => "ya",
            "mi" => "yi",
            "mu" => "wu",
            "va" => "va",
            "xi" => "xi",
            "s" => "ra",
            "g" => "ri",
            "r" => "ri",
        );

        $index = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            if ($word == "{inquire}") {
                $wordAfterNext = $words[$index + 2];
                $firstTwoLetters = substr($wordAfterNext, 0, 2);
                $firstOneLetters = substr($wordAfterNext, 0, 1);

                $isPeople = FALSE;
                foreach ($people as $key1 => $value1) {
                    $temp = strtolower($value1);
                    if (trim($temp) == strtolower(trim($wordAfterNext))) {
                        $isPeople = true;
                    }
                }

                $isPeoplePronouns = FALSE;
                foreach ($peoplePronouns as $key1 => $value1) {
                    $temp = strtolower($value1);
                    if (trim($temp) == strtolower(trim($wordAfterNext))) {
                        $isPeoplePronouns = true;
                    }
                }

                if ($isPeople) {
                    $string = str_replace("{inquire}", "u", $string);
                } else if ($isPeoplePronouns) {
                    $string = str_replace("{inquire}", "i", $string);
                } elseif (array_key_exists($firstTwoLetters, $nouns)) {
                    $string = str_replace("{inquire}", $nouns[$firstTwoLetters], $string);
                } else if (array_key_exists($firstOneLetters, $nouns)) {
                    $string = str_replace("{inquire}", $nouns[$firstOneLetters], $string);
                } else {
                    $string = str_replace("{inquire}", "yi", $string);
                }
            }
            $index ++;
        }

        return str_replace("  ", " ", $string);
    }

    public static function replaceInquireBeforeConjuction($peoplePronouns, $string) {
        $words = explode(" ", $string);
        $nouns = array(
            "sw" => "swi",
            "vu" => "byi",
            "ma" => "ya",
            "mi" => "yi",
            "mu" => "wu",
            "xi" => "xi",
            "s" => "ra",
            "g" => "ri",
            "r" => "ri",
        );

        $index = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            if ($word == "{inquire:b}") {
                $wordAfterNext = $words[$index - 1];
                $firstTwoLetters = substr($wordAfterNext, 0, 2);
                $firstOneLetters = substr($wordAfterNext, 0, 1);

                $isPeoplePronouns = FALSE;
                foreach ($peoplePronouns as $key1 => $value1) {
                    $temp = strtolower($value1);
                    if (trim($temp) == strtolower(trim($wordAfterNext))) {
                        $isPeoplePronouns = true;
                    }
                }

                if ($peoplePronouns) {
                    $string = str_replace("{inquire:b}", "i", $string);
                } else if (array_key_exists($firstTwoLetters, $nouns)) {
                    $string = str_replace("{inquire:b}", $nouns[$firstTwoLetters], $string);
                } else if (array_key_exists($firstOneLetters, $nouns)) {
                    $string = str_replace("{inquire:b}", $nouns[$firstOneLetters], $string);
                } else {
                    $string = str_replace("{inquire:b}", "i", $string);
                }
            }
            $index ++;
        }

        return str_replace("  ", " ", $string);
    }
    
    public static function getInquireBeforeConjuctionSuffix($peoplePronouns, $string) {
        $words = explode(" ", $string);
        $nouns = array(
            "sw" => "swi",
            "vu" => "byi",
            "ma" => "ya",
            "mi" => "yi",
            "mu" => "wu",
            "xi" => "xi",
            "s" => "ra",
            "g" => "ri",
            "r" => "ri",
        );
        
        $index = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            if ($word == "{inquire:b}") {
                $wordAfterNext = $words[$index - 1];
                $firstTwoLetters = substr($wordAfterNext, 0, 2);
                $firstOneLetters = substr($wordAfterNext, 0, 1);

                $isPeoplePronouns = FALSE;
                foreach ($peoplePronouns as $key1 => $value1) {
                    $temp = strtolower($value1);
                    if (trim($temp) == strtolower(trim($wordAfterNext))) {
                        $isPeoplePronouns = true;
                    }
                }

                if ($peoplePronouns) {
                    return "yi";
                } else if (array_key_exists($firstTwoLetters, $nouns)) {
                    return $nouns[$firstTwoLetters];
                } else if (array_key_exists($firstOneLetters, $nouns)) {
                    return $nouns[$firstOneLetters];
                } else {
                    return "yi";
                }
            }
            $index ++;
        }

        return "yi";
    }

    public static function replaceUnusedConfigs($neddle, $string) {
        $string = str_replace($neddle, "", $string);

        return str_replace("  ", " ", $string);
    }

    public static function replacVowelsConfigs($string) {
        $string = str_replace("{vow}", "a", $string);

        return str_replace("  ", " ", $string);
    }

    public static function replaceString($pattern, $value, $string) {

        return str_replace($pattern, $value, $string);
    }

    public static function replaceBelongingToConjuction($people, $string) {
        $words = explode(" ", $string);
        $nouns = array(
            "sw" => "swa",
            "vu" => "bya",
            "ma" => "ya",
            "mi" => "ya",
            "mu" => "wa",
            "ku" => "wa",
            "nk" => "wa",
            "wa" => "wa",
            "ti" => "ra",
            "xi" => "xa",
            "vi" => "ra",
            "va" => "va",
            "ti" => "ta",
            "r" => "ra",
            "g" => "ra",
        );

        $index = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            if ($word == "{belong}") {
                $prevWord = $words[$index - 1];
                $firstTwoLetters = substr($prevWord, 0, 2);
                $firstOneLetters = substr($prevWord, 0, 1);

                $isPeople = FALSE;
                foreach ($people as $key1 => $value1) {
                    $temp = strtolower($value1);

                    if (trim($temp) == strtolower(trim($prevWord))) {
                        $isPeople = true;
                    }
                }

                if ($isPeople) {
                    $string = str_replace("{belong}", "wa", $string);
                } elseif (array_key_exists($firstTwoLetters, $nouns)) {
                    $string = str_replace("{belong}", $nouns[$firstTwoLetters], $string);
                } else if (array_key_exists($firstOneLetters, $nouns)) {
                    $string = str_replace("{belong}", $nouns[$firstOneLetters], $string);
                } else {
                    $string = str_replace("{belong}", "ya", $string);
                }
            }
            $index ++;
        }

        return str_replace("  ", " ", $string);
    }

    public static function replaceBelongingToBeforeConjuction($string) {
        $words = explode(" ", $string);
        $nouns = array(
            "sw" => "swa",
            "vu" => "bya",
            "ma" => "ya",
            "mi" => "ya",
            "mu" => "wa",
            "ku" => "wa",
            "nk" => "wa",
            "wa" => "wa",
            "ti" => "ra",
            "xi" => "xa",
            "vi" => "ra",
            "va" => "va",
            "ti" => "ta",
            "r" => "ra",
            "g" => "ra",
        );

        $index = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            if ($word == "{belong:b}") {
                $prevWord = $words[$index - 1];
                $firstTwoLetters = substr($prevWord, 0, 2);
                $firstOneLetters = substr($prevWord, 0, 1);

                if (array_key_exists($firstTwoLetters, $nouns)) {
                    $string = str_replace("{belong:b}", $nouns[$firstTwoLetters], $string);
                } else if (array_key_exists($firstOneLetters, $nouns)) {
                    $string = str_replace("{belong:b}", $nouns[$firstOneLetters], $string);
                } else {
                    $string = str_replace("{belong:b}", "ya", $string);
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
            $item = strtolower($record->replacement);
            $common[$item] = $record;
        }
        return $common;
    }

    public static function patternHash($records) {
        $common = array();
        foreach ($records as $key => $value) {
            $record = $value;
            $item = strtolower($record->replacement);
            $common[$item] = $record;
        }
        return $common;
    }

    /**
     * Determines whether text is English
     * 
     */
    public static function detectLanguage($text) {
        $text = TranslatoXitsongaUtil::replaceString("'er", " are", $text);
        $text = TranslatoXitsongaUtil::replaceString("'s", "", $text);

        $words = explode(" ", $text);
        $rating = 0;

        $english_words_file = file_get_contents("./php/translator_categories/english_words.txt");
        $english_words = explode("\n", $english_words_file);

        foreach ($words as $key => $value) {
            $word = $value;
            //$currentWord = TranslatoXitsongaUtil::searchWordByLanguage($word, "english");
            foreach ($english_words as $key => $currentWord) {
                if (trim($currentWord) != trim($word)) {
                    $rating ++;
                    continue;
                }
            }
        }

        if ($rating > 1) {
            return TRUE;
        }
        return FALSE;
    }

    public static function searchWordByLanguage($text, $language) {
        $aEntityDAO = new EntityDAO();
        $aItemTypeDAO = new ItemTypeDAO();

        $fromLanguage = $language;
        $aExclude = FALSE;
        $aResults = $aEntityDAO->findBestEntitySearchByName($text, $fromLanguage, $aExclude);
        if ($aResults[status]) {
            foreach ($aResults['resultsArray'] as $aResult) {
                $aEntityDetails = new EntityDetailsDAO();

                $aDetailResults = $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                if ($aDetailResults['status']) {
                    $array = array();
                    foreach ($aDetailResults['resultsArray'] as $aDetailResult) {
                        $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }

                $aTemp = $aItemTypeDAO->getItemTypeByID($aResult[item_type]);
                $type = strtolower($aTemp[resultsArray][2]);
                $word = $aResult[entity_name];
                $translation = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                if (strtolower(trim($type)) == strtolower(trim($language))) {
                    return $translation;
                }
            }
        }
        return $text;
    }

    /**
     * 
     * @param PHPArray data
     * @return JSON
     */
    public static function liveTranslateInternal($dictionaryCache, $passed, $language, $endPattern, $split, $retry) {
        $aEntityDAO = new EntityDAO();
        $aItemTypeDAO = new ItemTypeDAO();

        $text = trim($passed);
        if ($split != "") {
            $text = $split;
        }

        $translation = DictionaryJSONCache::cacheTranslateInternal($dictionaryCache, $passed, $text, $language);

        if ($translation != $passed) {
            if ($translation == "" || $translation == "-") {
                TranslatoXitsongaUtil::$endPattern = "";
            }
            return $translation;
        }

        $suffixes = array("o","a","nyana","ile","rile");
        if ($retry < count($suffixes) && $language == "xitsonga") {
            $value = $suffixes[$retry];
            $tempText = TranslatoXitsongaUtil::removeLastSubString($passed, strtolower($value));

            TranslatoXitsongaUtil::$endPattern = "|" . strtoupper($value);

            return TranslatoXitsongaUtil::liveTranslateInternal($dictionaryCache, $passed, $language, $endPattern, $tempText, $retry + 1);
        }

        TranslatoXitsongaUtil::$endPattern = "";
        return "$passed";
    }

    public static function removeLastSubString($word, $string) {
        $word = trim($word);
        $string = trim($string);

        if (substr($word, strlen($word) - strlen($string), strlen($word)) == $string) {
            $temp = substr($word, 0, strlen($word) - strlen($string));

            return $temp;
        } else {
            return $word;
        }
    }

    public static function complexWordSearchGrammar($word, $originalWord, $isVerb, $firstWord = false, $lastWord = false) {
        if (strpos($word, '|') !== false) {
            $slitter = explode("|", $word)[1];
            $originalWord = TranslatoXitsongaUtil::removeLastSubString($originalWord, strtolower($slitter));
        }

        if (strpos($word, '|') !== false) {
            $word = explode("|", $word)[0];
        }

        return $word;
    }
    
    public static function isPersonalPronoun($word) {
        $englishPronounsFile = file_get_contents("./php/translator_categories/xitsonga_pronouns.txt");
        $englishPronouns = explode("\n", $englishPronounsFile);
         foreach ($englishPronouns as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));
            
            if($value == $word) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
     public static function isAdjective($word) {
        $adjectivesFile = file_get_contents("./php/translator_categories/xitsonga_adjectives.txt");
        $adjectives = explode("\n", $adjectivesFile);
         foreach ($adjectives as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));
            
            if($value == $word) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    
    public static function isXitsongaPersonalPronoun($word) {
        $englishPronounsFile = file_get_contents("./php/translator_categories/vanhu_pronouns.txt");
        $englishPronouns = explode("\n", $englishPronounsFile);
         foreach ($englishPronouns as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));
            
            if($value == $word) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    public static function isInFileArray($file, $word) {
       
        $englishPronounsFile = file_get_contents("./php/translator_categories/$file.txt");
        $englishPronouns = explode("\n", $englishPronounsFile);
         foreach ($englishPronouns as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));
            
            if($value == $word) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    public static function isVerb($word) {
        $verbsFile = file_get_contents("./php/translator_categories/tsonga_verbs.txt");
        $verbs = explode("\n", $verbsFile);

        $suffixes = array("s", "es");

        foreach ($verbs as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));
            
            if($value == $word) {
                return TRUE;
            }
            
            foreach ($suffixes as $key => $suffix) {
                $tempWord = TranslatoXitsongaUtil::removeLastSubString($word, strtolower($suffix));
                if($value == $tempWord) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public static function replaceHardCodedPatternForWord($word, $pattern, $replacement) {
        return str_replace($pattern, $replacement, $word);
    }

    public static function removeFirstSubString($word, $string) {
        $word = trim($word);
        $string = trim($string);

        if (TranslatoXitsongaUtil::has_prefix($word, $string)) {
            $temp = substr($word, strlen($string), strlen($word));

            return $temp;
        } else {
            return $word;
        }
    }

    public static function removeLastCharacter($string) {
        return mb_substr($string, 0, -1);
    }
   
    public static function sortByLength($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return (strlen($a) > strlen($b) ? -1 : 1);
    }
}
