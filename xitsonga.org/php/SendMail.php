<?php
   /**
   * SendMail functionality
   * 
   * @Author Sneidon Dumela <me@sneidon.com>
   * @version 1.0
   */
    class SendMail {
        private static $TEAM_NAME = "Xitsonga.org";
        private static $WEBSITE_URL = "<a href ='https://www.xitsonga.org'>https://www.xitsonga.org</a>";
        private static $OUR_TEAM_EMAIL_ADDRESS = "<a href='mailto:info@xitsonga.org'>sneidon@yahoo.com</a>";
        
         /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendSytemMail($aUser,$aSubject, $aContent) {
            $aMailBody = $this->generateHTMLMessage($this->generateSystemMessage($aUser,$aContent));
            
            $aSubject = $aSubject;
            
            return $this->phpMailer($aUser[email], ucfirst(strtolower($aUser[firstname]))." ".ucfirst(strtolower($aUser[lastname])), $aSubject , $aMailBody, "info@xitsonga.org");
        }
        /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendSuggestionEmail($aData) {
            $aMailBody = $this->generateHTMLMessage($this->generateSuggestionMessage($aData));
            
            $aSubject = "Xitsonga dictionary content suggestion";
            
            return $this->phpMailer("info@xitsonga.org","info@xitsonga.org", $aSubject , $aMailBody, "info@xitsonga.org");
        }
        /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendActivateEmail($aData,$url) {
            $aMailBody = $this->generateHTMLMessage($this->generateConfirmationMessage($aData,$url));
            
            $aSubject = "Activate your Xitsonga.org account";
            
            return $this->phpMailer($aData->email, ucfirst(strtolower($aData->firstName))." ".ucfirst(strtolower($aData->lastName)), $aSubject , $aMailBody, "info@xitsonga.org");
        }
        
        /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendEncryptPasswordEmail($aData,$url) {
            $aMailBody = $this->generateHTMLMessage($this->generateEncryptPasswordMessage($aData,$url));
            
            $aSubject = "Reset your Xitsonga.org password";
            
            return $this->phpMailer($aData->email, ucfirst(strtolower($aData->firstName))." ".ucfirst(strtolower($aData->lastName)), $aSubject , $aMailBody, "info@xitsonga.org");
        }
        
         /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendResetPasswordEmail($aData,$pass) {
            $aMailBody = $this->generateHTMLMessage($this->generateResetPasswordMessage($aData,$pass));
            
            $aSubject = "Password reset for Xitsonga.org";
            
            return $this->phpMailer($aData[email],ucfirst(strtolower($aData[firstname]))." ".ucfirst(strtolower($aData[lastname])), $aSubject , $aMailBody, "info@xitsonga.org");
        }
         /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendServerMigrationEmail($aData,$url,$pass) {
            $aMailBody = $this->generateHTMLMessage($this->generateServerMigrationMessage($aData,$url,$pass));
            
            $aSubject = "Tsonga Online - xitsonga.org server migration";
            
            return $this->phpMailer($aData[email],$aData[firstname]." ".$aData[lastname], $aSubject , $aMailBody, "info@xitsonga.org");
        }
        /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendEmail($aData) {
            $aMailBody = $this->generateHTMLMessage($this->generateMessage($aData));
            
            $aSubject = "Tsonga Online Admin";
            
            return $this->phpMailer($aData, $aSubject, $aMailBody, "info@xitsonga.org");
        }
        
        private function phpMailer($send_to,$names,$subject,$body, $from){
            
            $aHeaders  = 'MIME-Version: 1.0' . "\r\n";
            $aHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				
            // Additional headers
            $aHeaders .= "From: <$from>". "\r\n";
            $bool =  mail("$names <$send_to>", $subject  , $body, $aHeaders);
            if(!$bool){
                return array(status=>false,"An unknown error occured");
            }else{
                return array(status=>true);
            }
        }
        /**
         * 
         * @param type $aData
         * @return Boolean
         */
        public function sendContactMail($aData) {
            $aMailBody = $this->generateHTMLMessage($this->generateAdminMessage($aData));
            
            $aSubject = "Xitsonga.org - Feedback";
                        
            return $this->phpMailer("sneidon@yahoo.com","Team", $aSubject, $aMailBody, "info@xitsonga.org");
        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateMessage($data){
            return "<div>Dear".$data->names."/div><br/>
                    <div>".$data->message."</div><br/>";

        }
        
         /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateConfirmationMessage($data,$url){
            return "<div>Dear ".$data->firstName." ".$data->lastName."</div><br/>
                    <div>Your account is nearly ready, all we need you to do now is confirm that you want us to complete your account set up</div><br/>
                    <div>Please click to <a href ='$url'>activate your account</a></div><br/>
                    <div>If the link above doesn't work, you can copy and paste this link into your web browser:</div><br/>$url<br/><br/>";

        }
        
         /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateEncryptPasswordMessage($data,$url){
            return "<div>Dear ".$data->firstName." ".$data->lastName."</div><br/>
                    <div>We require that you update your password to ensure that your information is protected.</div><br/>
                    <div>Please click to <a href ='$url'>reset your account password</a></div><br/>
                    <div>If the link above doesn't work, you can copy and paste this link into your web browser:</div><br/>$url<br/><br/>";

        }
        
        private function generateSuggestionMessage($data){
            return "<div>Dear Sneidon</div><br/>
                    <div>Someone left the following suggestion:</div><br/>
                     <div> <b>URL</b>: <a href='".$data->url."'>".$data->url."</a></div><br/>
                    <div>".$data->suggestion."</div><br/>
                    <div>Reply to: ".$data->email."<br/><br/>";

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateSystemMessage($data,$content){
            return "<div>Dear ".$data[firstname]." ".$data[lastname]."</div><br/>
                    <div>$content</div><br/>";

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateResetPasswordMessage($data,$pass){
            return "<div>Dear ".ucfirst(strtolower($data[firstname]))." ".ucfirst(strtolower($data[lastname]))."</div><br/>
                    <div>You have requested a password reset:</div>
                    <div>
                        <ul>
                            <li>Your password is: <b>$pass</b></li>
                            <li>It is advisable that you change it soon as possible.</li>
                        <ul>
                    </div>
                    <div>If the password above doesn't work, please contact us on the details below.</div><br/>";

        }
        
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateServerMigrationMessage($data,$url,$pass){
            return "<div>Dear ".$data[firstname]." ".$data[lastname]."</div><br/>
                    <div>We recently migrated our domain from <a href ='http://www.tsongaonline.co.za'>www.tsongaonline.co.za</a> to <a href ='http://www.xitsonga.org'>www.xitsonga.org</a>.</div><br/>
                    <div>The following changes apply to your account:</div>
                    <div>
                        <ul>
                            <li>Your account has been migrated to <a href ='http://www.xitsonga.org'>www.xitsonga.org</a></li>
                            <li>You can use your email <b>$data[email]</b> to login to <a href ='http://www.xitsonga.org'>www.xitsonga.org</a></li>
                            <li>Your system generated password is: <b>$pass</b></li>
                            <li>Your account will only be activate after activation.</li>
                        <ul>
                    </div>
                    <div>Thanks for understanding. Your account is nearly ready, all we need you to do now is confirm that you want us to complete your account set up.</div><br/>
                    <div>Please click to <a href ='$url'>activate your account</a></div><br/>
                    <div>If the link above doesn't work, you can copy and paste this link into your web browser:</div><br/>$url<br/><br/>";

        }
        
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateAdminMessage($data){
            return "<div>Dear Team,</div><br/>
                    <div><b>".$data->names."</b> left the following message:</div><br/>
                    <div><i>".$data->message."</i></div><br/>
                    <div>Reply to: ".$data->email.", ".$data->phone." <br/><br/>";

        }
        /**
         * 
         * @param type aContent
         * @return type
         */
        private function generateHTMLMessage($aContent){
            return "<html>
                        <head>
                                <title>Xisonga.org</title>
                        </head>
                        <body>
                                $aContent
                                <div>
                                        Warm Regards,<br/><strong>" .
                                        SendMail::$TEAM_NAME . "</strong>
                                        <br/>
                                        W: ".SendMail::$WEBSITE_URL."<br/>
                                        E: ".SendMail::$OUR_TEAM_EMAIL_ADDRESS."<br/>
                                </div>					
                        </body>
                    </html>";
        }
    }   
