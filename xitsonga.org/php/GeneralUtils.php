<?php
    /**
     * Genration Utilities for ID generation, formatting, password encryption etc
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class GeneralUtils{

        /**
         * @return string generatedId
         */
        public static function generateId()
        {
            $NumberOfAppendValues = 6;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("ymdHis"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
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
    }
?>
