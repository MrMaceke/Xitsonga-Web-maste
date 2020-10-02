<?php

/**
 * 
 */
class TranslatorService {

    public static $wordsOverallLimit = 100;
    public static $wordsLimitPerSentence = 20;
    
    public static $wordOverallLimitMessage = "I am sorry but I cannot handle more than 100 words at once.";
    public static $wordsLimitPerSentenceMessage = "I am sorry but I cannot handle more than a sentence of more than 20 words.";
    public static $emptyValuePassed = "Type a word or phrase.";
    public static $offlineMessage = "Translator is currently offline for maintenance. Please try again later.";
    
    public static $isLive = TRUE;
    private static $JsonUtil;

    public static function translateText($data, $adminUser) {
        TranslatorService::$JsonUtil = new JSONUtils();

        /**
         * Returns default message when feature is offline.
         */
        if ($adminUser == false && TranslatorService::$isLive == false) {
            $language = TranslatorService::getLanguage($data);

            TranslatorService::auditTranslation($adminUser, $data->text, $language, $data, TranslatorService::$offlineMessage);

            return TranslatorService::$JsonUtil->successFeedback(TranslatorService::$offlineMessage, OPERATION_SUCCESS);
        }

        $dataText = $data->text;
        $value = trim($dataText);

        /**
         * Returns default message when black string is passed.
         */
        if ($value == "") {
            $language = TranslatorService::getLanguage($data);
 
            TranslatorService::auditTranslation($adminUser, $dataText, $language, $data, TranslatorService::$emptyValuePassed);

            return TranslatorService::$JsonUtil->successFeedback(TranslatorService::$emptyValuePassed, OPERATION_SUCCESS);
        }

        /**
         * Replace sentence dividers with full stop
         */
        $text = TranslatorService::replaceSentenceDividerWithFullStop($dataText);

        /**
         * Clean out recurring special characters
         */
        $text = TranslatorService::cleanRecurringSpecialCharacter($text, ".");
        $text = TranslatorService::cleanRecurringSpecialCharacter($text, "?");
        $text = TranslatorService::cleanRecurringSpecialCharacter($text, "-");
        
        /**
         * Count number of words to check whether they exceed limit.
         */
        $wordLimitMessage = TranslatorService::wordOverallLimit($text);
        if ($wordLimitMessage != NULL) {
            $language = TranslatorService::getLanguage($data);

            TranslatorService::auditTranslation($adminUser, $dataText, $language, $data, $wordLimitMessage);

            return TranslatorService::$JsonUtil->successFeedback($wordLimitMessage, OPERATION_SUCCESS);
        }

        /**
         * Translate single word and skip if no word found
         */
        $cleanStringWithoutFullstop = str_replace(".", "", $text);
        if (str_word_count($cleanStringWithoutFullstop) == 1) {
            $aResponse = TranslatorService::translateOneWord($cleanStringWithoutFullstop, $data);
            
            if (strtolower($aResponse) != strtolower($cleanStringWithoutFullstop) && $aResponse != "") {
                TranslatorService::auditTranslation($adminUser, $dataText, "english", $data, $aResponse);

                return TranslatorService::$JsonUtil->successFeedback($aResponse, OPERATION_SUCCESS);
            }
        } 

        /**
         * Check if word per sentence is not exceeded.
         */
        if (TranslatorService::wordLimitPerSentence($text) == false) {
            $language = TranslatorService::getLanguage($data);
            
            TranslatorService::auditTranslation($adminUser, $dataText, $language, $data, TranslatorService::$wordsLimitPerSentenceMessage);

            return TranslatorService::$JsonUtil->successFeedback($wordLimitMessage, OPERATION_SUCCESS);
        }

        /**
         * Translate sentences and stores in array
         */
        $detectedLanguage = TranslatorService::detectLanguage($text);
        $translatesSentences = array();

        $sentences = explode(".", $text);
        if (is_array($sentences)) {
            foreach ($sentences as $key => $sentence) {
                $aResponse = TranslatorService::translateSentence($detectedLanguage, $sentence, $data);
                array_push($translatesSentences, $aResponse);
            }
        } else {
            $aResponse = TranslatorService::translateSentence($detectedLanguage, $sentence, $data);
            array_push($translatesSentences, $aResponse);
        }

        /**
         * Add sentence translations
         */
        $index = 0;
        $upperCaseNext = FALSE;
        $translation = "";
        $ending = TranslatorService::sentenceEndingArray($dataText);
        foreach ($translatesSentences as $key => $sentence) {
            if ($upperCaseNext) {
                $sentence = ucfirst(strtolower($sentence));
                $upperCaseNext = FALSE;
            }

            if ($ending[$index] != "," && $ending[$index] != ";") {
                $upperCaseNext = TRUE;
            }
            $translation = $translation . $sentence . $ending[$index] . " ";
            $index ++;
        }

        $translation = TranslatorService::cleanRecurringSpecialCharacter($translation, "?");
        $translation = TranslatorService::cleanRecurringSpecialCharacter($translation, ".");
        $translation = trim(preg_replace('/\s+/', ' ', $translation));

        TranslatorService::auditTranslation($adminUser, $dataText, $detectedLanguage, $data, $translation);

        return TranslatorService::$JsonUtil->successFeedback(ucfirst($translation), OPERATION_SUCCESS);
    }

    public static function translateSentence($detectedLanguage, $sentence, $data) {
        $aTranslatorUtil = new TranslatorUtil();
        $aTranslatorXitsongaUtil = new TranslatoXitsongaUtil();
        
        if ($detectedLanguage == "english") {
            $aResponse = json_decode($aTranslatorUtil->translateEnglishToXitsonga($detectedLanguage, trim($sentence), $data, true));
        } else if ($detectedLanguage == "xitsonga") {
            
            $aResponse = json_decode($aTranslatorXitsongaUtil->translateXitsongaToEnglish($detectedLanguage, trim($sentence), $data, true));
        } else {
            return $sentence;
        }

        if ($aResponse->status == 999) {
            return $aResponse->infoMessage;
        } else {
            return $aResponse->errorMessage;
        }
    }

    /**
     * Determines whether text is English
     * 
     */
    public static function detectLanguage($text) {
        $text = TranslatorUtil::replaceString("'er", " are", $text);
        $text = TranslatorUtil::replaceString("'s", " is", $text);
        $text = TranslatorUtil::replaceString(".", "", $text);
        $text = trim($text);

        $words = explode(" ", $text);
        $rating = 0;
        
        $english_words_file = file_get_contents("./php/translator_categories/english_top_10000_common_words.txt");
        $english_words = explode("\n", $english_words_file);
        
        foreach ($words as $key => $value) {
            $word = $value;

            foreach ($english_words as $key => $currentWord) {
                if (trim(strtolower($currentWord)) == trim(strtolower($word)) && strlen(trim($word)) > 1) {
                    $rating ++;
                    continue;
                }
            }
        }

        if ($rating > 1) {
            return "english";
        }
        return "xitsonga";
    }

    public static function translateOneWord($text, $data) {
        $aResponse = TranslatorUtil::translateWordEnglishToXitsonga("english", $text, $data);
        $aReponseJSON = json_decode($aResponse);

        if ($aReponseJSON->status == 999) {
            $return = $aReponseJSON->infoMessage;
        } else {
            $return = $aReponseJSON->errorMessage;
        }

        return ucfirst($return);
    }

    public static function wordLimitPerSentence($text) {
        $sentences = explode(".", $text);
        if (is_array($sentences)) {
            foreach ($sentences as $key => $value) {
                $value = trim($value);
                if (str_word_count($value) > TranslatorService::$wordsLimitPerSentence) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public static function wordOverallLimit($text) {
        if (str_word_count($text) > TranslatorService::$wordsOverallLimit) {
            $return = TranslatorService::$wordOverallLimitMessage;

            return $return;
        }
        return NULL;
    }

    public static function getLanguage($data) {
        $language = $data->language;
        if ($language == "") {
            $language = "default";
        }
        return $language;
    }

    public static function auditTranslation($adminUser, $text, $language, $data, $return) {
        $aAuditsAPICallsDAO = new AuditsAPICallsDAO();

        $aData[item] = $text . " (" . $language . ")";
        $aData[translation] = trim(strtolower($return) . " " . $data->url . " " . $data->firstTime);
        $aData[type] = "Translate";
        $aData[caller] = $data->version == "" ? "web" : $data->version;

        if ($adminUser == false) {
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
        }
    }

    public static function sentenceEndingArray($text) {
        $text = TranslatorService::cleanRecurringSpecialCharacter($text, ".");
        $text = TranslatorService::cleanRecurringSpecialCharacter($text, "?");
        
        $endingCharacter = array(".", "?", ",", ";", "!");
        $ending = array();
        for ($index = 0; $index < strlen($text); $index ++) {
            $letter = $text[$index];
            if (in_array($letter, $endingCharacter)) {
                array_push($ending, $letter);
            }
        }
        return $ending;
    }

    public static function cleanRecurringSpecialCharacter($text, $character) {
        for ($count = 10; $count > 1; $count --) {
            $characters = "";
            for ($row = 0; $row < $count; $row ++) {
                $characters = $characters . $character;
            }

            $text = trim(strtolower(str_replace("$characters", $character, $text)));
        }

        return $text;
    }

    public static function replaceSentenceDividerWithFullStop($text) {
        $text = trim(strtolower(str_replace("  ", " ", $text)));
        $text = trim(strtolower(str_replace(",", ".", $text)));
        $text = trim(strtolower(str_replace("?", ".", $text)));
        $text = trim(strtolower(str_replace(";", ".", $text)));
        $text = trim(strtolower(str_replace("!", ".", $text)));
        $text = trim(strtolower(str_replace("-", " ", $text)));

        return $text;
    }

}
