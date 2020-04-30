<?php

/**
 * Genration Utilities for ID generation, formatting, password encryption etc
 * 
 * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
 * @version 1.0
 */
class GeneralUtils {

    /**
     * @return string generatedId
     */
    public static function generateId() {
        $NumberOfAppendValues = 6;
        $minRandomNumber = 0;
        $maxRandomNumber = 99999;

        $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
        $stringLength = strlen($stingRandomNumber);

        $dateTime = strval(date("ymdHis"));
        for ($i = 0; $i < ($NumberOfAppendValues - $stringLength); $i++) {
            $stingRandomNumber .= "0";
        }
        return $dateTime . $stingRandomNumber; //12 + 6 characters
    }

    /**
     * Returns an ecypted password
     * 
     * @param string password
     * @param string encryptionKey
     * @return string encryptedString
     */
    public static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function getBytes($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function multiExplode($delimiters, $string) {
        $ary = explode($delimiters[0], $string);
        array_shift($delimiters);
        if ($delimiters != NULL) {
            foreach ($ary as $key => $val) {
                $ary[$key] = GeneralUtils::multiExplode($delimiters, $val);
            }
        }
        return $ary;  
    }
}

?>
