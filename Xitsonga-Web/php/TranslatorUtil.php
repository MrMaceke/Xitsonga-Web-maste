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
class TranslatorUtil {

    public static $endPattern = "";
    public static $vowel = array("a", "e", "i", "o", "u");

    public static function translateWordEnglishToXitsonga($detectedLanguage, $textString, $data) {
        $aJsonUtils = new JsonUtils();
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
            $aSearch = TranslatorUtil::firstWord($translation);

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

        $format = TranslatorUtil::replaceKnown($format, $configs->configs);
        $common = TranslatorUtil::commonHash($configs->configs);

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

                $currentWord = TranslatorUtil::liveTranslateInternal($dictionaryCache, $wordToReplace, $fromLanguage, "", "", 0);
                $currentWord = TranslatorUtil::complexWordSearchGrammar($currentWord, $wordToReplace, false);
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
        $translation = trim(TranslatorUtil::replaceInquireConjuction($vanhuPronouns, $vanhu, strtolower($translation)));

        /**
         * Replaces {inquire:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceInquireBeforeConjuction($vanhuPronouns, strtolower($translation)));

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
        /*
          $translation = trim(TranslatorUtil::replaceString(" so ", " leswaku ", strtolower($translation)));
          $translation = trim(TranslatorUtil::replaceString("{to:verb}", "ku", strtolower($translation)));
          $translation = trim(TranslatorUtil::replaceString("*ngopfu*", "ngopfu", strtolower($translation)));
          $translation = trim(TranslatorUtil::replaceString("*u*", "u", strtolower($translation)));
          $translation = trim(TranslatorUtil::replaceString("*hi*", "hi", strtolower($translation)));
          $translation = trim(TranslatorUtil::replaceString("*va*", "va", strtolower($translation)));

         */

        /**
         * Logs and returns translation
         */
        if ($translation != "-" && $translation != "") {
            if ($log) {
                $aTranslationDAO->AddTranslation($textString, strtolower($translation), $data->langauge, $build, 3);
            }
            return $aJsonUtils->successFeedback(trim(strtolower($translation)), OPERATION_SUCCESS);
        }

        return $aJsonUtils->successFeedback("", OPERATION_SUCCESS);
    }

    /**
     * 
     * @param type $detectedLanguage
     * @param type $textString
     * @param type $data
     * @param type $log
     * @return type
     */
    public static function translateEnglishToXitsonga($detectedLanguage, $textString, $data, $log) {
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
        $englishPronounsFile = file_get_contents("./php/translator_categories/pronouns.txt");

        $adjectives = explode("\n", $adjectivesFile);
        $verbs = explode("\n", $verbsFile);
        $vanhu = explode("\n", $vanhuFile);
        $vanhuPronouns = explode("\n", $vanhuPronounsFile);
        $englishPronouns = explode("\n", $englishPronounsFile);

        /**
         * Set from and detected language
         */
        $fromLanguage = $detectedLanguage;
        if ($fromLanguage == "") {
            $fromLanguage = $data->langauge;
            $detectedLanguage = $data->langauge;
        }


        $configsFile = file_get_contents("./php/translator_categories/translator_configs_$detectedLanguage.json");
        $configs = json_decode($configsFile);

        $spellingConfigsFile = file_get_contents("./php/translator_categories/translator_configs_$detectedLanguage" . "_spelling.json");
        $spellingConfigs = json_decode($spellingConfigsFile);

        /*
         * Removes unwanted characters from text
         */
        $text = SanitizeUtil::removeSpecialCharacters($textString);

        /**
         * Fix spelling issues
         */
        $text = TranslatorUtil::replaceKnown($text, $spellingConfigs->configs);

        $text = TranslatorUtil::replaceKnownSingleQuotes($text, $configs->configs);

        $text = TranslatorUtil::replaceString("'er", " are", $text);
        $text = TranslatorUtil::replaceString("'s", " <**>", $text);
        $text = TranslatorUtil::replaceString("*quote*", "'s", $text);

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
            $format = TranslatorUtil::swapAdjectiveWithNextWord($format);

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

                $isVerb = TranslatorUtil::isVerb($wordToReplace);
                $currentWord = TranslatorUtil::liveTranslateInternal($dictionaryCache, $wordToReplace, $fromLanguage, "", "", 0);

                $firstWord = $index == 0 ? true : false;
                $lastWord = $index == count($words) - 1 ? true : false;
                $currentWord = TranslatorUtil::complexWordSearchGrammar($currentWord . TranslatorUtil::$endPattern, $wordToReplace, $isVerb, $firstWord, $lastWord);

                TranslatorUtil::$endPattern = "";
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


        $translation = trim(TranslatorUtil::manualyExchangeLeftAndRight(strtolower($translation), "<**>"));

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
        $translation = trim(TranslatorUtil::replaceInquireConjuction($vanhuPronouns, $vanhu, strtolower($translation)));

        /**
         * Replaces {inquire:b} with conjuction based on first two letters or word 
         */
        $translation = trim(TranslatorUtil::replaceInquireBeforeConjuction($vanhuPronouns, strtolower($translation)));

        /**
         * Replaces {vow} with "a"
         */
        $translation = trim(TranslatorUtil::replacVowelsConfigs(strtolower($translation)));

        /**
         * Removes {remove_space}
         */
        $translation = trim(TranslatorUtil::removePatternSpace(strtolower($translation)));


        /**
         * Process demostratives
         */
        $translation = trim(DemonstrativeUtil::processEnglish(strtolower($translation)));

        /**
         * Process pronounces
         */
        $translation = trim(PersonalPronounUtil::processPersonalPronoun(strtolower($translation)));

        /**
         * Process negatives
         */
        $translation = trim(NegativeMeaningUtil::processNegativeMeaning(strtolower($translation)));

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
                $aTranslationDAO->AddTranslation($textString, strtolower($translation), $data->langauge, $build, 3);
            }
            return $aJsonUtils->successFeedback(trim(strtolower($translation)), OPERATION_SUCCESS);
        }

        return $aJsonUtils->successFeedback("", OPERATION_SUCCESS);
    }

    public static function markDouble($param) {
        
    }
    /**
     * 
     * This function swaps an adjective with the next word after it (if next word is noun).
     *  
     * E.G bap person to person {adjectives:b} bad, which translates to munhu {adjectives:b} biha
     */
    public static function swapAdjectiveWithNextWord($text) {
        $words = explode(" ", trim($text));
        $count = 0;
        $ownership = array("my", "our", "its");
        foreach ($words as $key => $value) {
            $word = strtolower(trim($value));
            $exclude = array("happy", "sad");

            if (in_array(strtolower(trim($word)), $exclude) == false && TranslatorUtil::isAdjective($word)) {
                if ($count < count($words)) {
                    $word0 = $words[$count - 1];
                    $word1 = $word;
                    $word2 = $words[$count + 1];

                    if (in_array($word0, $ownership)) {
                        
                        if (TranslatorUtil::isAdjective($word2)) {
                            //continue;
                        }

                        $text = str_replace($word1 . " " . $word2, $word2 . " {adjectives:b:d} " . $word1, $text);
                    } else {
                        if (TranslatorUtil::isAdjective($word2)) {
                            //continue;
                        }
                        $text = str_replace($word1 . " " . $word2, $word2 . " {adjectives:b} " . $word1, $text);
                    }

                    continue;
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
            } else if ($word == "{adjectives:b:d}") {
                $prevWord = TranslatorUtil::removeLastCharacter(trim($words[$index - 2])) . "o";
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

    public static function replaceKnownSingleQuotes($string, $records) {
        $words = explode(" ", $string);
        foreach ($words as $key => $word) {
            if (strpos($word, "'s") !== false) {
                $found = false;
                foreach ($records as $key => $value) {
                    $record = $value;
                    $string = trim($string);
                    $item = trim(strtolower($record->item));
                    $replacement = trim(strtolower($record->replacement));

                    if ($word === $item) {
                        $word = str_replace("'s", "*quote*", $word);

                        $return = $return . " " . $word;
                        $found = true;
                        break;
                    }
                }

                if ($found == false) {
                    $return = $return . " " . $word;
                }
            } else {
                $return = $return . " " . $word;
            }
        }

        return str_replace("  ", " ", strtolower(trim($return)));
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
            "s" => 'ra'
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
        $text = TranslatorUtil::replaceString("'er", " are", $text);
        $text = TranslatorUtil::replaceString("'s", "", $text);

        $XitsongaCommon = array("ndzi", "ni", "mina", "yena", "mi", "ha", "wa");
        $words = explode(" ", $text);
        $rating = 0;

        $english_words_file = file_get_contents("./php/translator_categories/english_words.txt");
        $english_words = explode("\n", $english_words_file);

        foreach ($words as $key => $value) {
            $word = $value;

            foreach ($english_words as $key => $currentWord) {
                if (trim($currentWord) == trim($word)) {
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
            if ($translation == "" || $translation == "-") {
                TranslatorUtil::$endPattern = "";
            }
            return $translation;
        }

        $suffixes = array("ing", "s", "ed", "d", "al", "ier", "est", "er", "th", "es", "ness", "ment", "ful", "less", "ish", "or", "wise", "ward", "ly", "en", "ive", "ic", "est","ics","ully", "ically", "ible", "able", "ity", "sion", "ware");
        if ($retry < count($suffixes) && $language == "english") {
            $value = $suffixes[$retry];
            $tempText = TranslatorUtil::removeLastSubString($passed, strtolower($value));

            TranslatorUtil::$endPattern = "|" . strtoupper($value);

            return TranslatorUtil::liveTranslateInternal($dictionaryCache, $passed, $language, $endPattern, $tempText, $retry + 1);
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
            return TranslatorUtil::liveTranslateInternal($dictionaryCache, $passed, $language, $endPattern, $tempText, ++$retry);
        }

        TranslatorUtil::$endPattern = "";
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
            $originalWord = TranslatorUtil::removeLastSubString($originalWord, strtolower($slitter));
        }

        /**
         * Word is in past tense
         */
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "a|ed", "ile");
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "o|ed", "ile");

        /**
         * Word is in present tense
         */
        if (strpos($word, 'a|ing') !== false && false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "a|ing", "eni");
            if ($firstWord) {
                $word = "$word";
            } else {
                $word = "le ku $word";
            }
        } else if (strpos($word, '|ing') !== false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ing", "");
        }

        /**
         * Word is plural
         */
        if (strpos($word, '|s') !== false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|s", "");
            if ($isVerb) {
                $word = ($word);
            } else {
                $word = $word; 
                //TranslatorUtil::changeWordToPlural($word);
            }
        }

        /**
         * Person from verb
         */
        if (strpos($word, 'la|er') !== false) {
            if (TranslatorUtil::isAdjective($originalWord)) {
                $word = $word . " swinene";
            } else {
                $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "la|er", "ri");

                $word = "mu" . $word;
            }
        } else if (strpos($word, 'a|er') !== false) {
            if (TranslatorUtil::isAdjective($originalWord)) {
                $word = $word . " swinene";
            } else {
                $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "a|er", "i");

                $word = "mu" . $word;
            }
        }
        /**
         * Extreme case
         */
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|est", " ngopfu");
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ier", " hi ku tlurisa");


        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ment", "");
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ful", "");
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ness", "");
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ish", " nyana");


        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "a|able", "eka");
        $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "a|ible", "eka");
        if (strpos($word, '|less') !== false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|less", "");

            $word = "pfumala ku " . $word;
        }

        if (strpos($word, '|ly') !== false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ly", "");

            $word = "Hi ku " . $word;
        }

        if (strpos($word, '|ically') !== false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ically", "");

            $word = "Hi " . $word;
        }

        if (strpos($word, '|ware') !== false) {
            $word = TranslatorUtil::replaceHardCodedPatternForWord(strtolower($word), "|ware", "");

            $word = "Swo " . $word . " hi swona";
        }

        if (strpos($word, '|') !== false) {
            $word = explode("|", $word)[0];
        }

        return $word;
    }

    public static function isPersonalPronoun($word) {
        $englishPronounsFile = file_get_contents("./php/translator_categories/pronouns.txt");
        $englishPronouns = explode("\n", $englishPronounsFile);
        foreach ($englishPronouns as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));

            if ($value == $word) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function isAdjective($word) {
        $adjectivesFile = file_get_contents("./php/translator_categories/adjectives.txt");
        $adjectives = explode("\n", $adjectivesFile);
        foreach ($adjectives as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));

            if ($value == $word) {
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

            if ($value == $word) {
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

            if ($value == $word) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public static function isVerb($word) {
        $verbsFile = file_get_contents("./php/translator_categories/verbs.txt");
        $verbs = explode("\n", $verbsFile);

        $suffixes = array("s", "es");

        foreach ($verbs as $key => $value) {
            $value = trim(strtolower($value));
            $word = trim(strtolower($word));

            if ($value == $word) {
                return TRUE;
            }

            foreach ($suffixes as $key => $suffix) {
                $tempWord = TranslatorUtil::removeLastSubString($word, strtolower($suffix));
                if ($value == $tempWord) {
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

        if (TranslatorUtil::has_prefix($word, $string)) {
            $temp = substr($word, strlen($string), strlen($word));

            return $temp;
        } else {
            return $word;
        }
    }

    /**
     * 
     * @param type $fileName
     * @param type $param
     * @param type $prefix
     * @return type
     */
    public static function applyPredefinedPluralPrefix($fileName, $param, $prefix) {
        $aFile = file_get_contents("./php/translator_categories/$fileName.txt");
        $aList = explode("\n", $aFile);

        foreach ($aList as $key => $item) {
            $paramTemp = $param;
            if (str_word_count($param) > 1) {
                $paramTemp = explode(".", trim($param))[0];
            }

            $item = trim(strtolower($item));
            if ($prefix === "FILE") {
                $plural = explode("-", $item)[1];
                $item = explode("-", $item)[0];
            }

            if ($item == trim(strtolower($paramTemp))) {
                if ($prefix === "FILE") {
                    return strtolower(trim($plural));
                }
                return $prefix . $paramTemp;
            }
        }
        return NULL;
    }

    public static function changeWordToPlural($param) {
        // REPLACES PREFIX FOR PREDEFINED TYPES
        $predefined = TranslatorUtil::applyPredefinedPluralPrefix("vanhu", $param, "va");
        if ($predefined != NULL) {
            return $predefined;
        }

        $predefined = TranslatorUtil::applyPredefinedPluralPrefix("no_plural", $param, "");
        if ($predefined != NULL) {
            return $predefined;
        }

        $predefined = TranslatorUtil::applyPredefinedPluralPrefix("different_rule_plural", $param, "FILE");
        if ($predefined != NULL) {
            return $predefined;
        }

        $vowels = array();

        $vowels['a'] = 'ma';
        $vowels['e'] = 'mi';
        $vowels['i'] = 'mi';
        $vowels['o'] = 'ti';
        $vowels['u'] = 'ma';

        $consonants_file = file_get_contents("./php/translator_categories/consonants_plural.txt");
        $consonants = explode("\n", $consonants_file);

        foreach ($consonants as $key => $consonant) {
            $plural = explode("-", trim(strtolower($consonant)))[1];
            $consonant = explode("-", trim(strtolower($consonant)))[0];

            foreach (TranslatorUtil::$vowel as $key1 => $vowel) {
                $prefix = $consonant . $vowel;
                $new_prefix = $vowels[$vowel];

                if ($plural != "") {
                    $new_prefix = $plural;
                }

                if (TranslatorUtil::has_prefix($param, $prefix)) {
                    $string = TranslatorUtil::removeFirstSubString($param, $prefix);

                    // REPLACES PREFIX
                    if ($prefix === "xi") {
                        return $new_prefix . $string;
                    }

                    // PREPANDS NEW PREFIX
                    return $new_prefix . $prefix . $string;
                }
            }
        }


        return $param;
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
