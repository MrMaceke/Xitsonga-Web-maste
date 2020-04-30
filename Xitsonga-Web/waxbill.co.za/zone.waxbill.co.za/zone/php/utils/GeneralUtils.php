<?php
    /**
     * Genration Utilities for ID generation, formatting, password encryption etc
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class GeneralUtils{
        const DEFAULT_MCRYPT_IV_SIZE = 24;

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
         * Generates a unique project ID e.g W1234567
         * 
         * @return String client id
         */
        public static function generateClientID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("Hi"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("W".$dateTime . $stingRandomNumber, 0, 8); //12 + 6 characters
        }
        /**
         * Generates a unique deployment ID e.g D1234567
         * 
         * @return String client id
         */
        public static function generateDeploymentID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("Hi"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("D".$dateTime . $stingRandomNumber, 0, 8); //12 + 6 characters
        }
        /**
         * Generates a unique quote ID e.g 1234567
         * 
         * @return String client id
         */
        public static function generateQuoteID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("Hi"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("".$dateTime . $stingRandomNumber, 0, 8); //12 + 6 characters
        }
        /**
         * Generates a unique project ID e.g P12345
         * 
         * @return String project id
         */
        public static function generateProjectID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("s"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("P".$dateTime . $stingRandomNumber, 0, 6); //12 + 6 characters
        }
        /**
         * Generates a unique project ID e.g I12345
         * 
         * @return String project id
         */
        public static function generateItemID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("s"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("I".$dateTime . $stingRandomNumber, 0, 6); //12 + 6 characters
        }
        /**
         * Generates a unique project ID e.g I12345
         * 
         * @return String project id
         */
        public static function generateTicketID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("s"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("T".$dateTime . $stingRandomNumber, 0, 6); //12 + 6 characters
        }
        /**
         * Generates a unique payment ID e.g INV12345
         * 
         * @return String project id
         */
        public static function generatePaymentID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("s"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("INV".$dateTime . $stingRandomNumber, 0, 8); //12 + 6 characters
        }
        /**
         * Generates a unique payment ID e.g DA12345
         * 
         * @return String project id
         */
        public static function generateDealID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("s"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("DA".$dateTime . $stingRandomNumber, 0, 8); //12 + 6 characters
        }
        /**
         * Generates a unique project stage ID e.g S12345
         * 
         * @return String project id
         */
        public static function generateProjectStageID()
        {
            $NumberOfAppendValues = 2;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("s"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr("S".$dateTime . $stingRandomNumber, 0, 6); //12 + 6 characters
        }
        /**
         * Returns an ecypted password
         * 
         * @param string password
         * @param string encryptionKey
         * @return string encryptedString
         */
        public static function encryptPassword($password, $encryptionKey) {
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $encryptedString =  base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $encryptionKey, $password, MCRYPT_MODE_ECB, $iv));
            
            return trim($encryptedString);
        }
        /**
         * Returns a decrypted password
         * 
         * @param string password
         * @param string encryptionKey
         * @return string encryptedString
         */
        public static function decryptPassword($encryptedString, $encryptionKey) {
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $decryptedString =  mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $encryptionKey, base64_decode($encryptedString), MCRYPT_MODE_ECB, $iv);
            
            return trim($decryptedString);
        }
        /**
         * Genereates a password
         * 
         * @param Integer $length
         * @return String password
         */
        public static function generateSystemPassword($length = 8) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $count = mb_strlen($chars);

            for ($i = 0, $result = ''; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $result .= mb_substr($chars, $index, 1);
            }

            return $result;
        }
    }
