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

/**
 * 
 */
class TranslatorUtil {

    public function translateEnglishToXitsonga($detectedLanguage, $textString, $data, $log) {
        $aJsonUtils = new JsonUtils();
        $aEntityDAO = new EntityDAO();
        $aItemTypeDAO = new ItemTypeDAO();
        $aTranslationDAO = new TranslationDAO();

        /**
         * Load all caches from files
         */
        $dictionaryCache = file_get_contents(__DIR__ . "/../open/data.json");
        $adjectivesFile = file_get_contents("./php/translator_categories/adjectives.txt");
        $verbsFile = file_get_contents("./php/translator_categories/verbs.txt");
        $vanhuFile = file_get_contents("./php/translator_categories/vanhu.txt");
        
        $adjectives = explode("\n", $adjectivesFile);
        $verbs = explode("\n", $verbsFile);
        $vanhu = explode("\n", $vanhuFile);

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
        $translation = DictionaryJSONCache::cacheTranslateInternal($dictionaryCache, $text, $text, $detectedLanguage);

        /**
         * Return direct translation if found
         */
        if ($translation != "-" && $translation != "" && $translation != $text) {
            $aSearch = TranslatorUtil::firstWord($translation);

            if ($log) {
                $aTranslationDAO->AddTranslation($data->text, strtolower($aSearch), $data->langauge, "Direct Translation", 5);
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
        if ($detectedLanguage == "english") {
            /*
             * Replace known text with configs and common hashes
             */
            $format = TranslatorUtil::replaceKnown($format, $configs->configs);
            $common = TranslatorUtil::commonHash($configs->configs);
            
            /**
             * 5. Swap Adjectives with next word. i.e "bad person" to "person {adjectives:b} bad"
             */
            $format = TranslatorUtil::swapAdjectiveWithNextWord($format, $adjectives);

            /**
             * 6. Mark conjuction if next word is verb i.e "to love" to "{to:verb} love"
             */
            $format = TranslatorUtil::markConjuctionPreceededByAWordClass("to", "{to:verb}", $format, $verbs);
            
            /**
             * Replace "so" with "ngopfu" if preceeded by adjective
             */
            $format = TranslatorUtil::markConjuctionPreceededByAWordClass("so", "*ngopfu* <*>", $format, $adjectives);

            /**
             * Split sentence into single words
             */
            $known = array();
            $words = explode(" ", $format);
            for ($wordIndex = 0; $wordIndex < count($words); $wordIndex ++) {
                $tempWord = $words[$wordIndex];
                if (array_key_exists($tempWord, $common)) {
                    $known[$tempWord] = $common[$tempWord];
                }
            }
        } else if ($detectedLanguage == "xitsonga") {
            $format = TranslatorUtil::replaceKnown($format, $configs->configs);
            $common = TranslatorUtil::commonHash($configs->configs);

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
                /**
                 * 7. 1 Replace "you, we, they" if preceeded by verb
                 */
                $wordToReplace = $words[$index];
                if ($wordToReplace == "you") {
                    $wordToReplace = TranslatorUtil::replaceWordPreceededByAVerb($wordToReplace, "*u*", $words, $verbs);
                } elseif ($wordToReplace == "we") {
                    $wordToReplace = TranslatorUtil::replaceWordPreceededByAVerb($wordToReplace, "*hi*", $words, $verbs);
                } elseif ($wordToReplace == "they") {
                    $wordToReplace = TranslatorUtil::replaceWordPreceededByAVerb($wordToReplace, "*va*", $words, $verbs);
                }

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

                $currentWord = $this->liveTranslateInternal($dictionaryCache, $wordToReplace, $fromLanguage);
            }

            if ($currentWord == "-" || $currentWord == "") {
                $translation = $translation . " " . $words[$index] . "";
            } else {
                $build = $build . " * " . $words[$index];

                $aSearch = TranslatorUtil::firstWord($currentWord);

                $translation = $translation . " " . $aSearch;
            }
        }

        /**
         * 8. Run all config actions
         */
        /*
         * Replace # with space
         */
        $translation = trim(TranslatorUtil::replaceUnusedConfigs("#", strtolower($translation)));

        /**
         * Actions push first and last congig
         */
        $translation = trim(TranslatorUtil::pushFirstAndLast(strtolower($translation), $known));

        /**
         * Actions swap right config
         */
        $translation = trim(TranslatorUtil::swapRight(strtolower($translation), $known));

        /**
         * Actions swap left config
         */
        $translation = trim(TranslatorUtil::swapLeft(strtolower($translation), $known));

        /**
         * Actions left and right exchanges configs
         */
        $translation = trim(TranslatorUtil::exchangeLeftAndRight(strtolower($translation), $known));

        /**
         * Replaces {belong} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceBelongingToConjuction($vanhu, strtolower($translation)));

        /**
         * Replaces {adjectives:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceAdjectiveConjuction(strtolower($translation)));

        /**
         * Replaces {belong:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceBelongingToBeforeConjuction(strtolower($translation)));

        /**
         * Replaces {inquire} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceInquireConjuction($vanhu, strtolower($translation)));
        
         /**
         * Replaces {inquire:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceInquireBeforeConjuction(strtolower($translation)));
        
        /**
         * Replaces {vow} with "a"
         */
        $translation = trim(TranslatorUtil::replacVowelsConfigs(strtolower($translation)));
        
        /**
         * Removes {remove_space}
         */
        $translation = trim(TranslatorUtil::removePatternSpace(strtolower($translation)));

        /**
         * 9. Replace all marked words
         */
        $translation = trim(TranslatorUtil::replaceString(" so ", " leswaku ", strtolower($translation)));
        $translation = trim(TranslatorUtil::replaceString("{to:verb}", "ku", strtolower($translation)));
        $translation = trim(TranslatorUtil::replaceString("*ngopfu*", "ngopfu", strtolower($translation)));
        $translation = trim(TranslatorUtil::replaceString("*u*", "u", strtolower($translation)));
        $translation = trim(TranslatorUtil::replaceString("*hi*", "hi", strtolower($translation)));
        $translation = trim(TranslatorUtil::replaceString("*va*", "va", strtolower($translation)));

        /**
         * Logs and returns translation
         */
        if ($translation != "-" && $translation != "") {
            if ($log) {
                $aTranslationDAO->AddTranslation($data->text, strtolower($translation), $data->langauge, $build, 3);
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

        foreach ($words as $key => $value) {
            $word = $value;

            foreach ($adjectives as $key1 => $value1) {
                $exclude = array("happy", "sad");

                if (in_array(strtolower(trim($word)), $exclude) == false) {
                    if (trim($word) == trim($value1)) {
                        if ($count < count($words)) {
                            $word1 = $word;
                            $word2 = $words[$count + 1];

                            $text = str_replace($word1 . " " . $word2, $word2 . " {adjectives:b} " . $word1, $text);

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
            }else if (preg_match('/^' . $item . ' /', $string) === 1) {
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
                
                $tempString = trim($patternSplit[0]) . " " . $tempString . " " . $patternSplit[1];
                
                $string = substr($string,0, strpos($string, $pattern)).$tempString;
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

    public static function replaceInquireConjuction($people, $string) {
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
                    if(trim($temp) == strtolower(trim($wordAfterNext))) {
                        $isPeople = true;
                    }
                }
                
                if ($isPeople) {
                    $string = str_replace("{inquire}", "u", $string);
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

    public static function replaceInquireBeforeConjuction($string) {
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

                if (array_key_exists($firstTwoLetters, $nouns)) {
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
                    
                    if(trim($temp) == strtolower(trim($prevWord))) {
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
    public function detectLanguage($text) {
        $words = explode(" ", $text);
        $rating = 0;
        foreach ($words as $key => $value) {
            $word = $value;
            $currentWord = $this->searchWordByLanguage($word, "english");

            if (trim($currentWord) != trim($word)) {
                $rating ++;
            }
        }

        if ($rating > 1) {
            return TRUE;
        }
        return FALSE;
    }

    public function searchWordByLanguage($text, $language) {
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
    public function liveTranslateInternal($dictionaryCache, $passed, $language, $split = "", $retry = 0) {
        $aEntityDAO = new EntityDAO();
        $aItemTypeDAO = new ItemTypeDAO();

        $text = trim($passed);
        if ($split != "") {
            $text = $split;
        }

        if (is_numeric($passed) && $language == "english") {
            $aNumber = new TsongaNumbers();
            return $aNumber->getNumberInTsonga($passed);
        }

        $aDate = date('y-m-d');
        $aDate = $aDate . " " . $passed;

        $aTsongaTimer = new TsongaTime();
        if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $passed) && $language == "english") {
            return ucfirst($aTsongaTimer->getTime($aTsongaTimer->returnRealTime($aDate)));
        }

        $translation = DictionaryJSONCache::cacheTranslateInternal($dictionaryCache, $passed, $text, $language);
        if ($translation != $passed) {

            return $translation;
        }

        if ($retry < 6 && $language == "english") {
            if ($retry == 0) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "ing");
            } else if ($retry == 1) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "s");
            } else if ($retry == 2) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "ed");
            } else if ($retry == 3) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "d");
            } else if ($retry == 4) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "al");
            } else if ($retry == 5) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "er");
            }
            return $this->liveTranslateInternal($dictionaryCache, $passed, $language, $tempText, ++$retry);
        } else if ($retry < 13 && $language == "xitsonga") {
            if ($retry == 0) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "ile");
                $tempText = $tempText . "a";
            } else if ($retry == 1) {
                $tempText = "n" . $passed;
            } else if ($retry == 2) {
                $tempText = TranslatorUtil::removeFirstSubString($passed, "swi");
                $tempText = "xi" . $tempText;
            } else if ($retry == 3) {
                $tempText = str_replace("nyana", " ", $passed);
            } else if ($retry == 4) {
                $tempText = str_replace("n", "", $passed);
            } else if ($retry == 5) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "eka");
                $tempText = $tempText . "a";
            } else if ($retry == 6) {
                if (preg_match('/^' . "mi" . ' /', $passed) === 1) {
                    $tempText = substr($passed, (strlen("mi")));
                } else {
                    $tempText = $passed;
                }
            } else if ($retry == 7) {
                if (preg_match('/^' . "ma" . ' /', $passed) === 1) {
                    $tempText = substr($passed, (strlen("ma")));
                } else {
                    $tempText = $passed;
                }
            } else if ($retry == 8) {
                if (preg_match('/^' . "swi" . ' /', $passed) === 1) {
                    $tempText = substr($passed, (strlen("swi")));
                } else {
                    $tempText = $passed;
                }
            } else if ($retry == 9) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "iwe");
                $tempText = $tempText . "a";
            } else if ($retry == 10) {
                $tempText = TranslatorUtil::removeLastSubString($passed, "ela");
                $tempText = $tempText . "a";
            } else if ($retry == 11) {
                if (preg_match('/^' . "e" . '/', $passed) === 1) {
                    $tempText = substr($passed, (strlen("e")));
                } else {
                    $tempText = $passed;
                }
            } else if ($retry == 12) {
                if (preg_match('/^' . "e" . '/', $passed) === 1) {
                    $tempText = substr($passed, (strlen("e")));
                    $tempText = TranslatorUtil::removeLastSubString($tempText, "ni");
                } else {
                    $tempText = $passed;
                }
            }
            return $this->liveTranslateInternal($dictionaryCache, $passed, $language, $tempText, ++$retry);
        }

        return "$passed";
    }

    public function removeLastSubString($word, $string) {
        $word = trim($word);
        $string = trim($string);

        if (substr($word, strlen($word) - strlen($string), strlen($word)) == $string) {
            $temp = substr($word, 0, strlen($word) - strlen($string));

            return $temp;
        } else {
            return $word;
        }
    }

    public function removeFirstSubString($word, $string) {
        $word = trim($word);
        $string = trim($string);

        if (substr($word, 0, strlen($word) - strlen($string) - 1) == $string) {
            $temp = substr($word, strlen($word) - strlen($string) - 1, strlen($word));

            return $temp;
        } else {
            return $word;
        }
    }

}
