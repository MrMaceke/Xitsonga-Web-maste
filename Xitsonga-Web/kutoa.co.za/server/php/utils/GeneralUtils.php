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
        public static function generateOneTimePin()
        {
            $NumberOfAppendValues = 5;
            $minRandomNumber = 0;
            $maxRandomNumber = 99999;

            $stingRandomNumber = strval(mt_rand($minRandomNumber, $maxRandomNumber));
            $stringLength = strlen ($stingRandomNumber);

            $dateTime = strval(date("ymdHis"));
            for ($i = 0; $i < ($NumberOfAppendValues - $stringLength);$i++) {
                $stingRandomNumber .= "0";
            }
            
            return substr($stingRandomNumber . $dateTime, 0, 5);
        }
        /**
         * 
         * @param type $filename_x
         * @param type $filename_y
         * @param type $filename_result
         * @return type
         */
        public static function generateRideImage($driver, $passanger, $location, $date,$filename_x, $filename_y, $filename_result) {
            list($width_x, $height_x) = getimagesize($filename_x);
            list($width_y, $height_y) = getimagesize($filename_y);

            $image = imagecreatetruecolor($width_x + $width_y, $height_x);

            $image_x = imagecreatefromjpeg($filename_x);
            $image_y = imagecreatefromjpeg($filename_y);

            imagecopy($image, $image_x, 0, 0, 0, 0, $width_x, $height_x);
            imagecopy($image, $image_y, $width_x, 0, 0, 0, $width_y, $height_y);
            imagejpeg($image, $filename_result, 100);
            
            $font = "../css/fonts/arial.ttf";
            $font_size = 16;
            $offset_x = 21;
            $offset_y = 21;
            
            list($width, $height) = getimagesize($filename_result);
            
            $image_p = imagecreatetruecolor($width, $height);
            $image = imagecreatefromjpeg($filename_result);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
            
            $bg_color = imagecolorallocate($image, 0, 0, 0);
            $bg_color_2 = imagecolorallocate($image, 0, 0, 0);
            $text_color = imagecolorallocate($image,255,255,255);
            $text_color_2 = imagecolorallocate($image,255,127,0);
            
            /**
             * Driver
             */
            $dims = imagettfbbox($font_size, 0, $font, "D: ".$driver);
            $text_width = $dims[4] - $dims[6] + $offset_x;
            $text_height = $dims[3] - $dims[5] + $offset_y;
            
            imagefilledrectangle($image_p, 0, 0, $text_width, $text_height, $bg_color);
            imagettftext($image_p, $font_size, 0, $offset_x - 10, $offset_y + 5, $text_color, $font, "D: ".$driver);
            
            /**
             * Location
             */
            $dims = imagettfbbox($font_size, 0, $font, $location);
            $text_width = $dims[4] - $dims[6] + $offset_x;
            $text_height = $dims[3] - $dims[5] + $offset_y;
            
            imagefilledrectangle($image_p, $width_x + $text_width - 75, $height_x / 2 - 25, $text_width + 45, $height_x/2 + 10, $bg_color_2);
            imagettftext($image_p, $font_size, 0, ($width_x / 2) + ($text_width/2) - 5, $height_x/2, $text_color_2, $font, $location);
            
            /**
             * Passenger
             */
            
            $offset_x = $width_x;
            $offset_y = $height_x;
            
            $dims = imagettfbbox($font_size, 0, $font, $passanger);
            $text_width = $dims[4] - $dims[6] + $offset_x;
            $text_height = $dims[3] - $dims[5] + $offset_y;
            
            imagefilledrectangle($image_p, $width_x, $height_x - 35, $text_width + 20, $height_x, $bg_color);
            imagettftext($image_p, $font_size, 0, $offset_x + 5, $offset_y - 10, $text_color, $font, $passanger);
            
            /**
             * Save Image
             */

            imagejpeg($image_p, $filename_result,100);
             
            imagedestroy($image);
            imagedestroy($image_x);
            imagedestroy($image_y);
            imagedestroy($image_p); 
            
            return $filename_result;
        }
        
        public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
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
