<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PersonalPronounUtil {

    public static $pronouns = array(
        "{ppns:you}" => array("u", "wena"),
        "{ppns:i}" => array("ndzi", "mina"),
        "{ppns:he}" => array("u", "yena"),
        "{ppns:she}" => array("u", "yena"),
        "{ppns:it}" => array("xi", "xona"),
        "{ppns:we}" => array("hi", "hina"),
        "{ppns:they}" => array("va", "vona")
    );
    public static $exluded = array("xana","xana?");

    public static function processPersonalPronoun($string) {
        if (strpos($string, "{ppns:") !== false) {
            // Skip
        } else {
            return $string;
        }

        $return = "";
        $words = explode(" ", trim($string));
        $count = 0;
        foreach ($words as $key => $word) {
            $word = trim(strtolower($word));
            $nextWord = trim(strtolower($words[$count + 1]));
            if (strpos($word, "{ppns:") !== false) {
                
                if (strlen(trim($nextWord)) > 0 && in_array(trim($nextWord), PersonalPronounUtil::$exluded) == false) {
                   
                    $return = $return . "" . PersonalPronounUtil::$pronouns[$word][0] . " ";
                } else {
                    $return = $return . "" . PersonalPronounUtil::$pronouns[$word][1] . " ";
                }
            } else {
                $return = $return . "" . $word . " ";
            }
            $count ++;
        }

        return trim($return);
    }

}
