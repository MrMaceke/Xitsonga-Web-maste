<?php

/**
 * Description of SanitizeUtil
 *
 * @author mukondli
 */
class SanitizeUtil {

    public static function removeSpecialCharacters($textString) {
        $text = trim(strtolower(str_replace("_", "", $textString)));
        $text = trim(strtolower(str_replace("?", "", $textString)));
        $text = trim(strtolower(str_replace(".", "", $textString)));
        $text = trim(strtolower(str_replace("’", "'", $textString)));

        return $text;
    }
    
    public static function deleteFile($url) { 
        $path = str_ireplace("https://www.xitsonga.org", "", $url);
        
        unlink(__DIR__."/".$path);
    }

    public static function cleanVisionAIResponse($GoogleAIWords, $aMessage) {
        $aMessage = str_replace("..", ".", $aMessage);
        $aMessage = str_replace("...", ".", $aMessage);

        $aMessageArray = explode(" ", $aMessage);
        $aMessage = "";
        foreach ($aMessageArray as $key => $word) {
            $found = false;
            foreach ($GoogleAIWords as $key => $GoogleAIWord) {
                $GoogleAIWord = trim(strtolower($GoogleAIWord));
                
                $GoogleAIWordsInteral = explode(" ", $GoogleAIWord);
                $xitsongaWord = trim(strtolower($word));
                if(in_array($xitsongaWord, $GoogleAIWordsInteral)) {
                    $found = true;
                    //$aMessage = $aMessage." ...<missing>";
                    break;
                }
            }
            
            if($found == false) {
                $aMessage = $aMessage." ".$word;
            }
   
        }
  
        $aMessage = trim(preg_replace('/\s+/',' ', $aMessage));
        return ucfirst($aMessage);
    }

}
