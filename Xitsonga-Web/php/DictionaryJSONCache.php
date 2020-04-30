<?php

/**
 * Description of newPHPClass
 *
 * @author mukondli
 */
class DictionaryJSONCache {

    //put your code here

    public static function wordFullDefination($searchWord) {
        $english_words_file = file_get_contents("./php/sources/oxford-english-dictionary.txt");
        $english_words = explode("\n", $english_words_file);

        foreach ($english_words as $key => $value) {
            $word = explode(" ", trim($value))[0];

            if (trim(strtolower($searchWord)) == strtolower($word)) {
                for ($index = 1; $index < 100; $index ++) {
                    $delimiter = $delimiter . ". $index";
                    if ($index < 99) {
                        $delimiter = $delimiter . "|";
                    }
                }
                $sentences = preg_split('/(' . $delimiter . ')/', $value, -1, PREG_SPLIT_NO_EMPTY);
                return $sentences;
            }
        }
        return array();
    }

    public static function cacheTranslateInternalWithoutLanguage($file, $full, $text) {
        $json = json_decode($file);
        $text = str_replace(".", " ", trim($text));
        $full = trim($full);

        $translation = $text;

        $found = false;
        foreach ($json->entities as $key => $value) {
            $word = trim($value->description);
            $type = trim($value->type);

            if (strtolower($word) == strtolower($text) && trim($value->translation) != '-') {
                $translation = $value->translation;

                $found = true;
                break;
            }
        }

        if ($found == false) {
            foreach ($json->entities as $key => $value) {
                $word = trim($value->description);
                $type = trim($value->type);
                $splitByDot = explode(".", $value->translation)[0];
                $splitByComma = explode(",", $value->translation)[0];
                if (strtolower($text) == strtolower($value->translation)
                        OR strtolower($text) == strtolower($splitByDot)
                        OR strtolower($text) == strtolower($splitByComma)) {
                    $translation = $word;

                    $found = true;
                    break;
                }
            }
        }

        if ($found == false && strlen($text) > 3) {
            foreach ($json->entities as $key => $value) {
                $word = trim($value->description);
                $type = trim($value->type);

                if (strlen($word) > strlen($text)) {
                    continue;
                }

                if (DictionaryJSONCache::hasPrefix(strtolower($word), strtolower($text))) {
                    $translation = $value->translation;

                    $found = true;
                    break;
                }
            }
        }

        if ($translation == "-" && $found) {
            $translation = str_replace("-", "", $translation);
            return trim(strtolower($translation));
        }

        if ($found) {
            $translation = str_replace("-", "", $translation);
            return $translation;
        }

        return $full;
    }

    public static function cacheTranslateInternal($file, $full, $text, $language) {
        $json = json_decode($file);
        $text = trim($text);
        $full = trim($full);

        $translation = $text;

        $found = false;
        foreach ($json->entities as $key => $value) {
            $word = trim($value->description);
            $type = trim($value->type);

            if (strtolower($word) == strtolower($text) && (strtolower($type) == strtolower($language) || strtolower($type) == strtolower("groups")) && trim($value->translation) != '-') {
                $translation = $value->translation;

                $found = true;
                break;
            }
        }
        if ($found == false) {
            foreach ($json->entities as $key => $value) {
                $word = trim($value->description);
                $type = trim($value->type);
                $splitByDot = explode(".", $value->translation)[0];
                $splitByComma = explode(",", $value->translation)[0];
                if (strtolower($text) == strtolower($value->translation)
                        OR strtolower($text) == strtolower($splitByDot)
                        OR strtolower($text) == strtolower($splitByComma)) {
                    $translation = $word;

                    $found = true;
                    break;
                }
            }
        }

        if ($found == false && strlen($text) > 2) {
            foreach ($json->entities as $key => $value) {
                $word = trim($value->description);
                $type = trim($value->type);

                if (strlen($word) > strlen($text) + 2) {
                    continue;
                }

                if (DictionaryJSONCache::hasPrefix(strtolower($word), strtolower($text)) && strtolower($type) == strtolower($language)) {
                    $translation = $value->translation;

                    $found = true;
                    break;
                }
            }
        }

        if ($translation == "-" && $found) {
            $translation = str_replace("-", "", $translation);
            return trim(strtolower($translation));
        }

        if ($found) {
            $translation = str_replace("-", "", $translation);
            return $translation;
        }

        return $full;
    }

    public static function hasPrefix($string, $prefix) {
        return substr($string, 0, strlen($prefix)) == $prefix;
    }

}
