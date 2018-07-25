<?php
    require_once 'AudioConstructsDAO.php';
    require_once 'JsonUtils.php';
    require_once 'JSONDisplay.php';
    require_once 'constants.php';
    /**
    * 
    * @Author Sneidon Dumela <sneidon@yahoo.com>
    * @version 1.0
    */
    class AudioReader {
        private $vowel = array("a","e","i","o","u");
        /**
         * Loads all avaialable audio URLS from the database
         * 
         * @return PHPArray
         */
        public function audioReaderSetUp() {
            $aAudioConstructDao = new AudioConstructsDAO();
            
            $aResults = $aAudioConstructDao->getAllAudioConstructs();
            
            return $aResults;
        }
        /**
         * 
         * @param String aWord
         * @param PHPArray aResults
         * @return JSON
         */
        public function completeAudioConstructURLs($aWord, $aResults) {
            $aReturn = array();
            $aAvaialbleAudio =  array();
            foreach ($aResults as $key => $value) {
                $aAvaialbleAudio[$key] = $value[construct];
            }
            
            $aCountIndex = 0;
            for($aCount = 0; $aCount < strlen($aWord); $aCount ++){
                $aTemp = $aWord[$aCount];
                
                if(!in_array($aTemp,$this->vowel)){
                    
                    for($aIndex = $aCount + 1; $aIndex < strlen($aWord); $aIndex ++){
                        $aTemp2 = $aWord[$aIndex];
                        
                        if(in_array($aTemp2,$this->vowel)){
                            $aTemp = $aTemp.$aTemp2;
                            $aCount = $aIndex;
                            break;
                        }
                        $aTemp = $aTemp.$aTemp2;    
                    }
                }
                $aReturn[$aCountIndex ++] = $aTemp;               
            }
            
            for($aCount = 0; $aCount < count($aReturn); $aCount ++){
                $aTemp = !in_array($aReturn[$aCount],$this->vowel)?substr_replace($aReturn[$aCount],"",-1):$aReturn[$aCount];
                
                if(!in_array($aTemp,$aAvaialbleAudio) AND !in_array($aTemp,$this->vowel)){
                    $aJSON = new JSONUtils();
                    
                    return $aJSON->errorFeedback("Word or phrase audio not avaialble", OPERATION_FAILED);
                }
            }  
            return JSONDisplay::GetAidioURLsJSON($aReturn,"Success", OPERATION_SUCCESS);
        }
        /**
         * Returns array with urls for audio for the specified word
         * 
         * @param String aWord
         * @param PHPArray aResults
         * @return PHPArray
         */
        public function completeAudioConstructURLsArray($aWord, $aResults) {
            $aReturn = array();
            $aAvaialbleAudio =  array();
            foreach ($aResults as $key => $value) {
                $aAvaialbleAudio[$key] = $value[construct];
            }
            
            $aCountIndex = 0;
            for($aCount = 0; $aCount < strlen($aWord); $aCount ++){
                $aTemp = $aWord[$aCount];
                
                if(!in_array($aTemp,$this->vowel)){
                    
                    for($aIndex = $aCount + 1; $aIndex < strlen($aWord); $aIndex ++){
                        $aTemp2 = $aWord[$aIndex];
                        
                        if(in_array($aTemp2,$this->vowel)){
                            $aTemp = $aTemp.$aTemp2;
                            $aCount = $aIndex;
                            break;
                        }
                        $aTemp = $aTemp.$aTemp2;    
                    }
                }
                $aReturn[$aCountIndex ++] = $aTemp;               
            }
            
            for($aCount = 0; $aCount < count($aReturn); $aCount ++){
                $aTemp = !in_array($aReturn[$aCount],$this->vowel)?substr_replace($aReturn[$aCount],"",-1):$aReturn[$aCount];
                if(!in_array($aTemp,$this->vowel)){
                    if(!in_array($aTemp,$aAvaialbleAudio)){
                        return NULL;
                    }
                }
            }  
            return $aReturn;
        }
    }
?>
