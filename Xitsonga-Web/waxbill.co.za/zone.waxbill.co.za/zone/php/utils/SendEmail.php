<?php
    /**
     * Sends emails
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class SendEmail {
        const TEAM_NAME = "Waxbill Team";
        const TEAM_ADDRESS = "184 Protea Estates, Midrand, 1687";
        const WEBSITE_URL = "http://www.waxbill.co.za";
        const DOMAIN_URL = "http://zone.waxbill.co.za";
        const OUR_TEAM_EMAIL_ADDRESS = "info@waxbill.co.za";
        const OUR_TEAM_PHONE_NUMBER = "(+27)710112950";
        
        public function sendUserRegisteringEmail($aData, $aPassword) {
            $aMailBody = $this->generateHTMLMessage($this->generateUserRegisteredMessage($aData,$aPassword));
            
            $aSubject = "Welcome to Waxbill";
            
            return $this->phpMailer($aData["email"],"", $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        
        public function sendUserResetPasswordEmail($aData, $aPassword) {
            $aMailBody = $this->generateHTMLMessage($this->generateUserPasswordResetMessage($aData,$aPassword));
            
            $aSubject = "Password reset";
            
            return $this->phpMailer($aData["email"],"", $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        
        public function sendUpdateContactEmail($firtName, $aUserArray) {
            $aMailBody = $this->generateHTMLMessage($this->generateChangeEmailMessage($firtName,$aUserArray));
            
            $aSubject = "Email address has been updated";
            
            return $this->phpMailer($aUserArray["email"],$firtName, $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        
        public function sendProjectInitiatedEmail($firtName, $aUserArray, $aProjectId, $aProjectName,$aProjectRelease) {
            $aMailBody = $this->generateHTMLMessage($this->generateNewProjectMessage($firtName,$aUserArray,$aProjectId,$aProjectName,$aProjectRelease));
            
            $aSubject = $aProjectId." - New project initiated";
            
            return $this->phpMailer($aUserArray["email"],$firtName, $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        
        public function sendProgressProjectEmail($aFirstName, $aUserArray,$aMessage, $aStageName,$aProjectId, $aProjectName,$aProjectRelease) {
            $aMailBody = $this->generateHTMLMessage($this->generateProgressProjectMessage($aFirstName, $aMessage, $aStageName, $aProjectId, $aProjectName, $aProjectRelease));
            
            $aSubject = $aProjectId." - Progressed to ".strtolower($aStageName)." stage";
            
            return $this->phpMailer($aUserArray["email"],$aFirstName, $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        
        public function sendCompletedProjectEmail($aFirstName, $aUserArray,$aProjectId, $aProjectName,$aProjectRelease,$aProjectStatus) {
            $aMailBody = $this->generateHTMLMessage($this->generateCompletedProjectMessage($aFirstName, $aProjectId, $aProjectName, $aProjectRelease,$aProjectStatus));
            
            $aSubject = $aProjectId." - Project has been completed";
            
            return $this->phpMailer($aUserArray["email"],$aFirstName, $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        
        public function sendAssignedPersonEmail($aFirstName, $aUserArray, $aItemId,$aItemName,$aProjectId, $aProjectName,$aProjectStage) {
            $aMailBody = $this->generateHTMLMessage($this->generateAssignedPersonMessage($aFirstName, $aItemId, $aItemName, $aProjectId, $aProjectName,$aProjectStage));
            
            $aSubject = $aItemId." - a new task has been assigned to you";
            
            return $this->phpMailer($aUserArray["email"],$aFirstName, $aSubject , $aMailBody, "info@waxbill.co.za");
        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateUserPasswordResetMessage($data, $password){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Hi!</h2>
			<p style ="width:100%;margin:2px;font-size:14px">A password reset was initiated on your behalf. Remember to update your password.</p>
                    </div>
                   <div style ="padding:10px;;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:100%;margin:2px;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Account credentials</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Client ID</b></td>
                                    <td>'.$data[user_key].'</td>
                                </tr>
                                <tr>
                                    <td><b>Email Address</b></td>
                                    <td>'.$data[email].'</td>
                                </tr>
                                <tr>
                                    <td><b>Password</b></td>
                                    <td>'.$password.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'" class="link2">Visit ClientZone</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateUserRegisteredMessage($data, $password){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Welcome aboard!</h2>
			<p style ="width:100%;margin:2px;font-size:14px">Thank you for choosing Waxbill. A ClientZone account has been created on your behalf.</p>
                    </div>
                   <div style ="padding:10px;;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:100%;margin:2px;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Account credentials</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Client ID</b></td>
                                    <td>'.$data[user_key].'</td>
                                </tr>
                                <tr>
                                    <td><b>Email Address</b></td>
                                    <td>'.$data[email].'</td>
                                </tr>
                                <tr>
                                    <td><b>Password</b></td>
                                    <td>'.$password.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'" class="link2">Visit ClientZone</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateProgressProjectMessage($aFirstName, $aMessage,$aStageName,$aProjectId, $aProjectName,$aProjectRelease){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Hi '.$aFirstName.'</h2>
			<p style ="width:100%;margin:2px;font-size:14px">Project has been progressed to <i>'.$aStageName.'</i> stage. '.$aMessage.'</p>
                    </div>
                   <div style ="padding:10px;;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:100%;margin:2px auto;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Project details</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Project ID</b></td>
                                    <td>'.$aProjectId.'</td>
                                </tr>
                                <tr>
                                    <td><b>Project Name</b></td>
                                    <td>'.$aProjectName.'</td>
                                </tr>
                                <tr>
                                    <td><b>Project Stage</b></td>
                                    <td>'.$aStageName.'</td>
                                </tr>
                                <tr>
                                    <td><b>Release Forecast</b></td>
                                    <td>'.$aProjectRelease.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'/project/'.$aProjectId.'" class="link2">Track Status</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateCompletedProjectMessage($aFirstName,$aProjectId, $aProjectName,$aProjectRelease,$aProjectStatus){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Hi '.$aFirstName.'</h2>
			<p style ="width:100%;margin:2px;font-size:14px">Your project has been completed. Thank you for your business</p>
                        <p style ="width:100%;margin:2px;font-size:14px">Please do not hestitate contact us if you have any questions.</p>
                    </div>
                   <div style ="padding:10px;;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:100%;margin:2px auto;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Project details</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Project ID</b></td>
                                    <td>'.$aProjectId.'</td>
                                </tr>
                                <tr>
                                    <td><b>Project Name</b></td>
                                    <td>'.$aProjectName.'</td>
                                </tr>
                                <tr>
                                    <td><b>Project Status</b></td>
                                    <td>'.$aProjectStatus.'</td>
                                </tr>
                                <tr>
                                    <td><b>Release Forecast</b></td>
                                    <td>'.$aProjectRelease.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'/project/'.$aProjectId.'" class="link2">Track Status</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateAssignedPersonMessage($aFirstName,$aItemId,$aItemName,$aProjectId, $aProjectName,$aProjectStage){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Hi '.$aFirstName.'</h2>
			<p style ="width:100%;margin:2px;font-size:14px">A new task "'.$aItemId.'" has been assigned to you.</p>
                    </div>
                   <div style ="padding:10px;;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:100%;margin:2px auto;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Item details</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Task ID</b></td>
                                    <td>'.$aItemId.'</td>
                                </tr>
                                <tr>
                                    <td style ="padding-right:20px"><b>Project ID</b></td>
                                    <td><a target="_blank" href="'.self::DOMAIN_URL.'/project/'.$aProjectId.'">'.$aProjectId.'</a></td>
                                </tr>
                                <tr>
                                    <td style ="padding-right:20px"><b>Task Name</b></td>
                                    <td>'.$aItemName.'</td>
                                </tr>
                                <tr>
                                    <td style ="padding-right:20px"><b>Stage Name</b></td>
                                    <td>'.$aProjectStage.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'/project/'.$aProjectId.'" class="link2">Open Project</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateNewProjectMessage($firstName, $aUserArray,$aProjectId, $aProjectName,$aProjectRelease){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Hi '.$firstName.'</h2>
			<p style ="width:100%;margin:2px;font-size:14px">A new project has been inititated under your account '.$aUserArray[user_key].'</p>
                    </div>
                   <div style ="padding:10px;;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:80%;margin:2px;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Project details</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Project ID</b></td>
                                    <td>'.$aProjectId.'</td>
                                </tr>
                                <tr>
                                    <td><b>Project Name</b></td>
                                    <td>'.$aProjectName.'</td>
                                </tr>
                                <tr>
                                    <td><b>Project Stage</b></td>
                                    <td>'."Initiated".'</td>
                                </tr>
                                <tr>
                                    <td><b>Release Forecast</b></td>
                                    <td>'.$aProjectRelease.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'/project/'.$aProjectId.'" class="link2">Track Status</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        /**
         * 
         * @param type aData
         * @return HTML content
         */
        private function generateChangeEmailMessage($firstName, $userArray){
            return '<div style ="padding-top:25px;padding-bottom:25px;width:100%;">
			<h2>Hi '.$firstName.'</h2>
			<p style ="width:100%;margin:2px;font-size:14px">Notification email address has been updated. You will now recieve all communication on this email</p>
                    </div>
                   <div style ="padding:10px;width:100%;background:#F5F5F5;border:1px solid #E5E5E5">
			<div style ="width:100%;font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:150%">
                            <h2 style ="margin-bottom:2px">Updated Account</h2>
                            <table>
                                <tr>
                                    <td style ="padding-right:20px"><b>Client ID</b></td>
                                    <td>'.$userArray[user_key].'</td>
                                </tr>
                                <tr>
                                    <td><b>Email Address</b></td>
                                    <td>'.$userArray[email].'</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50" style =""margin:0 auto>
                            <tr>
                                <td bgcolor="#FF7E0A" align="center" style="border-radius:4px;" width="200" height="50">
                                    <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" >
                                            <a target="_blank" href="'.self::DOMAIN_URL.'" class="link2">Visit ClientZone</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>';

        }
        
        private function phpMailer($aSendTo,$aNames,$aSubject,$aBody, $aFrom){
            $aHeaders  = 'MIME-Version: 1.0' . "\r\n";
            $aHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				
            // Additional headers
            $aHeaders .= "From: Waxbill <$aFrom>". "\r\n";
            $aBool =  mail("$aNames <$aSendTo>", $aSubject  , $aBody, $aHeaders);
            if(!$aBool){
                return array(status=>false);
            }else{
                return array(status=>true);
            }
        }
        /**
         * 
         * @param type aContent
         * @return type
         */
        private function generateHTMLMessage($aContent){
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                            <title>Waxbill Email</title>
                            <style type="text/css">
                                p {
                                    margin:0;
                                    color:#555;
                                    font-family:Helvetica, Arial, sans-serif;
                                    font-size:14px;
                                    line-height:160%;
                                }
                                h2{
                                    color:#181818;
                                    font-family:Helvetica, Arial, sans-serif;
                                    font-size:16px;
                                    font-weight: normal;
                                }
                                
                                h3 {
                                    color:#181818;
                                    font-family:Helvetica, Arial, sans-serif;
                                    font-size:18px;
                                    margin-top:0px;
                                    font-weight: normal;
                                }

                                a.link2{
                                    text-decoration:none;
                                    font-family:Helvetica, Arial, sans-serif;
                                    font-size:16px;
                                    color:#fff;
                                    border-radius:4px;
                                }
                            </style>
                        </head>
                        <body style ="width:100%;margin:0 auto;padding:10px">
                            <div style ="padding-top:25px;padding-bottom:25px;margin:0 auto;text-align:center;width:100%;border-bottom:1px solid #D4D4D4">
                                <img alt = "Waxbill" src ="http://www.waxbill.co.za/assets/images/icons/email_logo.png" style ="margin:0 auto"/>
                            </div>'.
                            $aContent.'
                            <div style ="width:100%;padding-top:25px;margin:0 auto;border-top:1px solid #D4D4D4">
                                <span style="font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">'.self::TEAM_NAME.'</span>
                                <br/>
                                <span style="font-size:14px;color:#555;font-family:Helvetica, Arial, sans-serif;line-height:200%;">'.self::TEAM_ADDRESS.' | '.self::OUR_TEAM_PHONE_NUMBER.'</span>
                                <br/>
                                <span style="font-size:14px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">
                                <a target=:_blank" href="'.self::WEBSITE_URL.'" style="text-decoration:none;color:#555;text-align:center">'.self::WEBSITE_URL.'</a></span>
                            </div>
                        </body>
                    </html>';
        }
    }