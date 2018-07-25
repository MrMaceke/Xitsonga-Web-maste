<?php

    /**
     * InputValidator
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
    */
    class InputValidator {
        public static $MIN_ENTITY_LENGTH = 1;
        public static $MIN_NAME_LENGTH = 3;
        public static $MIN_PASSWORD_LENGTH = 3;
        
        
        public function inputValidationAddType($data) {
            $aError = FALSE;
            $aMessage = "Description is a compulsory field";
            
            if(!$this->isEmpty($data->description)){
                $aError = TRUE;
                $aMessage = "Validation success";
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
        public function inputValidationEditType($data) {
            $aError = FALSE;
            $aMessage = "Description is a compulsory field";
            
            if(!$this->isEmpty($data->name)){
                $aError = TRUE;
                $aMessage = "Validation success";
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
        public function inputValidationAddExercise($data) {
            $aError = FALSE;
            $aMessage = "Title and description are compulsory fields";
            
            if(!$this->isEmpty($data->text) && !$this->isEmpty($data->title)){
                $aError = TRUE;
                $aMessage = "Validation success";
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
        public function inputSubmitExercise($data) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if($this->isEmpty($data->exerciseID)){
                $aError = FALSE;
                $aMessage = "Data is not set up properly";
            }else{
                foreach ($data->answers as $key => $value) {
                    if($this->isEmpty($value->answerId)){
                        $aError = FALSE;
                        $aMessage = "All questions must be answered";
                        
                         return array(status=> $aError, message=>$aMessage);
                    }
                }
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
        public function inputValidationAddAnswers($data) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if($this->isEmpty($data->questionID)){
                $aError = FALSE;
                $aMessage = "Data is not set up properly";
            }else{
                $aOne = FALSE;
                $aCorrect = FALSE;
                foreach ($data->answers as $key => $value) {
                    $aOne = TRUE;
                    if($this->isEmpty($value->answerText)){
                        $aError = FALSE;
                        $aMessage = "Text is a compulsory field for all answers";
                        
                         return array(status=> $aError, message=>$aMessage);
                    }
                    
                    if($value->correct == 1){
                        $aCorrect = TRUE;
                    }
                }
                
                if(!$aCorrect){
                    $aError = FALSE;
                    $aMessage = "At least one answer must be correct";
                    
                     return array(status=> $aError, message=>$aMessage);
                }
                
                if(!$aOne){
                    $aError = FALSE;
                    $aMessage = "At least one answer is required";
                    
                    return array(status=> $aError, message=>$aMessage);
                }
                
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
        public function inputValidationTranslationAPI($data) {
            $aError = TRUE;
            $aMessage = "";
            
            if($this->isEmpty($data[format])){
                $aError = FALSE;
                $aMessage = "No format specified";
            }elseif($this->isEmpty($data[word])){
                $aError = FALSE;
                $aMessage = "No word specified";
            }elseif($this->isEmpty($data[language])){
                $aError = FALSE;
                $aMessage = "No language specified";
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
         public function inputValidationAddEntity($data){
            if($this->isEmpty($data->name)){
              return array(status=> false, message=>"Description is a compulsory field"); 
            }
            
            $aError = FALSE;
            $aMessage = "Translation is a compulsory field";
            
            foreach ($data->details as $key => $value) {
                $aDescription = trim($value->content);
                if(($value->itemType == "english_translation" or strtolower($value->itemType) == "english translation") 
                        && !$this->isEmpty($aDescription)){
                    $aError = TRUE;
                }
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        
        
        public function inputValidationAddQuestion($data) {
            $aError = FALSE;
            $aMessage = "Title is a compulsory field";
            
            if(!$this->isEmpty($data->questionText)){
                $aError = TRUE;
                $aMessage = "Validation success";
            }
            return array(status=> $aError, message=>$aMessage);
        }
        
        public function inputValidationAddTranslationConfig($data){
            if($this->isEmpty($data->item)){
              return array(status=> false, message=>"Item is a compulsory field"); 
            } else if($this->isEmpty($data->replacement)){
              return array(status=> false, message=>"Replacement is a compulsory field"); 
            } else if($this->isEmpty($data->language)){
              return array(status=> false, message=>"Language is a compulsory field"); 
            } else if($this->isEmpty($data->pattern)){
              return array(status=> false, message=>"Pattern is a compulsory field"); 
            }
            
            return array(status=> true); 
        }
        
        
        public function inputValidationSystemEmail($data){
            if(!$this->minLengthRequirement($data->subject,InputValidator::$MIN_NAME_LENGTH)
              || !$this->minLengthRequirement($data->content,InputValidator::$MIN_NAME_LENGTH)){ 
              return array(status=> false, message=>"Subject or content email too short"); 
            }
            return array(status=> true); 
        }
        
        public function inputValidationSuggestionEmail($data){
            if(!$this->minLengthRequirement($data->content,InputValidator::$MIN_NAME_LENGTH)){ 
              return array(status=> false, message=>"Sugesstion is too short"); 
            }
            return array(status=> true); 
        }
        
        public function inputValidationUpdateUser($data){
            if(!$this->minLengthRequirement($data->firstName,InputValidator::$MIN_NAME_LENGTH)
              || !$this->minLengthRequirement($data->lastName,InputValidator::$MIN_NAME_LENGTH)){ 
              return array(status=> false, message=>"First name or last name too short"); 
            }
            return array(status=> true); 
        }
        
        public function inputValidationChangePassword($data){
            if(!$this->minLengthRequirement($data->currentPassword,InputValidator::$MIN_PASSWORD_LENGTH)
              || !$this->minLengthRequirement($data->password,InputValidator::$MIN_PASSWORD_LENGTH)){ 
              return array(status=> false, message=>"Password is too short"); 
            }elseif($data->password != $data->cpassword ){
              return array(status=> false, message=>"Passwords doesn't match"); 
            }
            return array(status=> true); 
        }
        
        public function inputValidationLogin($data){
            if($this->isEmpty($data->email) || $this->isEmpty($data->password) 
                || !$this->isValidEmailFormat($data->email) || !$this->minLengthRequirement($data->password,InputValidator::$MIN_PASSWORD_LENGTH)){
                
              return array(status=> false, message=>"Incorrect Credentials"); 
            }
            return array(status=> true); 
        }
        
        public function inputValidationRegister($data){
            if($this->isEmpty($data->firstName) || $this->isEmpty($data->lastName)
              || $this->isEmpty($data->email) || $this->isEmpty($data->password)
              || $this->isEmpty($data->cemail)){
                
              return array(status=> false, message=>"All fields are mandatory"); 
            }elseif(!$this->minLengthRequirement($data->firstName,InputValidator::$MIN_NAME_LENGTH)
              || !$this->minLengthRequirement($data->lastName,InputValidator::$MIN_NAME_LENGTH)){
                
              return array(status=> false, message=>"First name or last name too short"); 
            }elseif(!$this->minLengthRequirement($data->password,InputValidator::$MIN_PASSWORD_LENGTH)){
                
              return array(status=> false, message=>"Password is too short"); 
            }elseif($data->email != $data->cemail ){
                
              return array(status=> false, message=>"Emails don't match"); 
            }elseif($data->password != $data->cpassword ){
                
              return array(status=> false, message=>"Passwords don't match"); 
            }elseif(!$this->isValidEmailFormat($data->email)){

              return array(status=> false, message=>"Email format not valid"); 
            }
            return array(status=> true); 
        }
        
        private function isValidEmailFormat($aEmail) {
            if(filter_var($aEmail, FILTER_VALIDATE_EMAIL)) {
                return TRUE;
            }
            return FALSE;
        }
        /**
         * 
         * @param String $aString
         * @param String $aLength
         * @return Boolean
         */
        private function minLengthRequirement($aString,$aLength){
            $aString = trim($aString);
            if(strlen($aString)>= $aLength){
                return TRUE;
            }
            return FALSE;
        }
        /**
         * 
         * @param String $aString
         * @return Boolean
         */
        private function isEmpty($aString) {
            $aString = trim($aString);
            if(strlen($aString) == 0){
                return TRUE;
            }
            return FALSE;
        }
    }