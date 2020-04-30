<?php

class NegativeMeaningUtil {

    public static function processNegativeMeaning($string) {
        if (strpos($string, "{ngtv}") === false) {
            return $string;
        }

        $return = "";
        $words = explode(" ", trim($string));
        $count = 0;
        for($count = 0; $count < count($words); $count ++) {
            $word = trim(strtolower($words[$count]));
            $nextWord = trim(strtolower($words[$count + 1]));
            if (strpos($word, "{ngtv") !== false && strlen(trim($nextWord)) > 0) {
                $return = $return . "" . TranslatorUtil::removeLastCharacter($nextWord) . "i" . " ";
                $count = $count + 1;
            } else {
                $return = $return . "" . $word . " ";
            }
        }

        return trim($return);
    }

}
