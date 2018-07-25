<?php
    /**
     * Validate user access and business rules for all operation
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
    */
    class BusinessDataValidator {
        public static $aSuperUser = array("esambo393@gmail.com","amukelani.chauke553@gmail.com","sneidon@yahoo.com","collins_baloyi@yahoo.com");
        public static $aSupportsTags = array("xitsonga","phrases","proverbs","idioms","system-messages","video");
        public static $aSupportsOnlyWordOne = array("xitsonga","english","surnames");
        public static $aSupportedAPIFormats = array("json","xml");
        public static $aSupportedAPILanguages = array("xitsonga","english");
        public static $aSupportsRichContent = array("jokes","poems","song-lyrics","traditional-food");
        /**
         * Checks user access and business rules for adding entity
         * 
         * @param JSON aData
         * @param {DTOUser} aUser
         */
        public function businessDataValidationAddEntity($aData,$aUser) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if(!$aUser->isAdmin()){
                $aError = FALSE;
                $aMessage = "You don't have permission for this operation";
            }else if(strtolower($aData->typeValue) == "--default--"){
                $aError = FALSE;
                $aMessage = "Please specify type for entity";
            }else{
                if(!in_array(strtolower($aData->typeValue), BusinessDataValidator::$aSupportsTags)){
                    foreach ($aData->details as $key => $value) {
                        $aDescription = trim($value->content);
                        
                        if($value->itemType == "english_translation"){
                           if(strip_tags($aDescription) != $aDescription && !in_array(strtolower($aData->typeValue), BusinessDataValidator::$aSupportsRichContent)){
                                $aError = FALSE;
                                $aMessage = ucfirst($aData->typeValue)." doesn't support rich text";
                                break;
                            } 
                        } 
                        
                        if(($value->itemType == "dictionary_type" OR strtolower($value->itemType) == "dictionary type") 
                                && (is_numeric($aDescription) && ($aDescription != 18 AND $aDescription != 17 AND $aDescription != 19))){
                            $aError = FALSE;
                            $aMessage = ucfirst($aData->typeValue)." - dictionary type must be default";
                            break;
                        }
                        
                        if(($value->itemType == "website_link" OR strtolower($value->itemType) == "website link") 
                                && (is_numeric($aDescription) && ($aDescription != 18 AND $aDescription != 17 AND $aDescription != 19))){
                            $aError = FALSE;
                            $aMessage = ucfirst($aData->typeValue)." - tag must be default";
                            break;
                        }
                    }
                }
                
                if($aError){
                    if(str_word_count($aData->name) > 1 && in_array(strtolower($aData->typeValue), BusinessDataValidator::$aSupportsOnlyWordOne)){
                        $aError = FALSE;
                        $aMessage = "Entity name for ".$aData->typeValue." type must be one word";
                    }
                }
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        /**
         * Checks user access and business rules for edit entity
         * 
         * @param JSON aData
         * @param {DTOUser} aUser
         */
        public function businessDataValidationEditEntity($aData,$aUser) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if(!$aUser->isAdmin()){
                $aError = FALSE;
                $aMessage = "You don't have permission for this operation";
            }else if(strtolower($aData->typeValue) == "--default--"){
                $aError = FALSE;
                $aMessage = "Please specify type for entity";
            }else{
               if(!in_array(strtolower($aData->typeValue), BusinessDataValidator::$aSupportsTags)){
                    foreach ($aData->details as $key => $value) {
                        $aDescription = trim($value->content);
                        if(($value->itemType == "dictionary_type" OR strtolower($value->itemType) == "dictionary type") 
                                && (is_numeric($aDescription) && ($aDescription != 18 AND $aDescription != 17 AND $aDescription != 19))){
                            $aError = FALSE;
                            $aMessage = ucfirst($aData->typeValue)." - dictionary type must be default";
                            break;
                        }
                        
                        if(($value->itemType == "website_link" OR strtolower($value->itemType) == "website link") 
                                && (is_numeric($aDescription) && ($aDescription != 18 AND $aDescription != 17 AND $aDescription != 19))){
                            $aError = FALSE;
                            $aMessage = ucfirst($aData->typeValue)." - tag must be default";
                            break;
                        }
                    }
                }
                
                if($aError){
                    if(str_word_count($aData->name) > 1 && in_array(strtolower($aData->typeValue), BusinessDataValidator::$aSupportsOnlyWordOne)){
                        $aError = FALSE;
                        $aMessage = "Entity name for ".$aData->typeValue." type must be one word";
                    }
                }
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        
        public function businessDataValidationTranslationAPI($data) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if(!in_array(strtolower($data[format]), BusinessDataValidator::$aSupportedAPIFormats)){
                $aError = FALSE;
                $aMessage = "Format specified is not supported. We only support JSON and XML.";
            }else if(!in_array(strtolower($data[language]), BusinessDataValidator::$aSupportedAPILanguages)){
                $aError = FALSE;
                $aMessage = "Language specified is not supported. We only support Xitsonga and English.";
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        /**
         * Checks user access for super users
         * 
         * @param {DTOUser} aUser
         */
        public function businessDataValidationSuperAdminAccess($aUser) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if(!$aUser->isAdmin() OR !in_array($aUser->getEmail(), BusinessDataValidator::$aSuperUser)){
                $aError = FALSE;
                $aMessage = "You don't have permission for this operation";
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        
        /**
         * Checks user access for  users
         * 
         * @param {DTOUser} aUser
         */
        public function businessDataValidationAdminAccess($aUser) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if(!$aUser->isAdmin()){
                $aError = FALSE;
                $aMessage = "You don't have permission for this operation";
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        /**
         * Checks user access for  users
         * 
         * @param {DTOUser} aUser
         */
        public function businessDataValidationUserAccess($aUser) {
            $aError = TRUE;
            $aMessage = "Validation success";
            if(!$aUser->isSignedIn()){
                $aError = FALSE;
                $aMessage = "You don't have permission for this operation";
            }
            
            return array(status=> $aError, message=>$aMessage); 
        }
        /**
         * Checks session access for  users
         * 
         * @param {DTOUser} aUser
         */
        public function businessDataValidationSessionAccess() {
            $aError = TRUE;
            $aMessage = "Validation success";
            
            return array(status=> $aError, message=>$aMessage); 
        }
    }
    
?>