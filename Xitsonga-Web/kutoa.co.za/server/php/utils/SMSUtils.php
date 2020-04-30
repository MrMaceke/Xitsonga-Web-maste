<?php
    function include_all_php($folder){
        foreach (glob("{$folder}/*.php") as $filename)
        {
            include $filename;
        }
    }
    
    include_all_php(__DIR__.'/../Twilio/');
    
     /**
     * SMS utilities
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SMSUtils {
        public static $ALLOW_NUMBERS = array("0710112950","0826974300");
        /**
         * 
         * @param type $phoneNumber
         * @return type
         */
        public static function formatSAPhoneNumber($phoneNumber) {
             return "+27".substr($phoneNumber, 1);   
        }
        /**
         * 
         * @param type $phoneNumber
         * @param type $message
         * @return type
         */
        public static function sendSMS($phoneNumber, $message) {
            $account_sid = 'AC826f290fb2828d5c20efbc8338b6c01d';
            $auth_token = '0561c0d20b5e4a1b1ab7233784b1ebcf';
            
            try {
                $client = new Twilio\Rest\Client($account_sid, $auth_token);

                $aReturn =  $client->account->messages->create(
                    SMSUtils::formatSAPhoneNumber($phoneNumber),
                    array(
                        'From' => '+14106953454',
                        'Body' => $message,
                    )
                );
            } catch (RestException $e){
                $aReturn = $e->getMessage();
            }
            
            return $aReturn;
        }
    }
