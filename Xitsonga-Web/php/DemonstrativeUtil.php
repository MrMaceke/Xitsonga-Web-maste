<?php

class DemonstrativeUtil {

    public static function processEnglish($string) {
        if (strpos($string, "l{dmstv:") !== false) {
            
        } else {
            return $string;
        }

        $default = array(
            "l{dmstv:s:that}" => "sweswo",
            "l{dmstv:s:this}" => "leswi",
            "l{dmstv:p:those}" => "sweswo",
            "l{dmstv:p:these}" => "leswi"
        );

        $englishPronounsFile = file_get_contents("./php/translator_categories/pronouns.txt");
        $englishPronouns = explode("\n", $englishPronounsFile);

        $words = explode(" ", $string);
        $count = 0;
        $returnWords = array();
        foreach ($words as $key => $word) {
            $added = false;
            if (strpos($word, "l{dmstv:p") !== false) {
                $nextWord = $words[$count + 1];

                if (strlen(trim($nextWord)) > 0) {
                    $pluralWord = $nextWord;

                    $suffix = "";
                    for ($letterIndex = 0; $letterIndex < strlen($pluralWord); $letterIndex ++) {
                        $letter = $pluralWord[$letterIndex];
                        if (in_array($letter, TranslatorUtil::$vowel)) {
                            $suffix = $suffix . $letter;

                            break;
                        }
                        $suffix = $suffix . $letter;
                    }

                    if ($letter == "i") {
                        $suffix = "e" . $suffix;
                    } else {
                        $suffix = "a" . $suffix;
                    }

                    array_push($returnWords, $nextWord . " l");
                    $added = true;
                } else {
                    array_push($returnWords, $default[$word]);
                    $added = true;
                }
            } else if (strpos($word, "l{dmstv:s") !== false) {
                $nextWord = $words[$count + 1];
                $afterNextWord = $words[$count + 2];
                if (strlen(trim($nextWord)) > 0) {
                    if ($word == "l{dmstv:s:that}" && TranslatorUtil::isXitsongaPersonalPronoun($nextWord)) {
                        array_push($returnWords, "ku");
                        $added = true;
                    } else if ($word == "l{dmstv:s:that}" && strlen(trim($afterNextWord)) > 0) {
                        array_push($returnWords, "");
                        $added = true;
                    } else if ($word == "l{dmstv:s:this}") {
                        array_push($returnWords, "ku");
                        $added = true;
                    } else {
                        $suffix = "";
                        for ($letterIndex = 0; $letterIndex < strlen($nextWord); $letterIndex ++) {
                            $letter = $nextWord[$letterIndex];
                            if (in_array($letter, TranslatorUtil::$vowel)) {
                                $suffix = $suffix . $letter;

                                break;
                            }
                            $suffix = $suffix . $letter;
                        }

                        $vanhuPronounsFile = file_get_contents("./php/translator_categories/vanhu_pronouns.txt");
                        $vanhuPronouns = explode("\n", $vanhuPronounsFile);

                        $suffix = TranslatorUtil::getInquireBeforeConjuctionSuffix($vanhuPronouns, "$nextWord {inquire:b}");

                        $prefix = "le";
                        if (TranslatorUtil::isInFileArray("vanhu", $nextWord) == true) {
                            $prefix = "lo";
                        }

                        array_push($returnWords, $nextWord . " " . $prefix . $suffix);
                        $added = true;
                    }
                } else {
                    array_push($returnWords, $default[$word]);
                    $added = true;
                    $return = TranslatorUtil::replaceString($word, $default[$word], $string);
                }
            }
            
            if($added == false) {
                array_push($returnWords, $word);
            }
            $count ++;
        }
        $return = "";
        foreach ($returnWords as $key => $value) {
             $return = $return . " " . $value;
        }
       
        return trim(str_replace("  ", " ", $return));
    }

}
