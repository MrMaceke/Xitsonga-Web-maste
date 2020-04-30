<?php

require_once 'TranslatorUtil.php';
require_once 'TsongaNumbers.php';
require_once 'TsongaTime.php';
require_once 'constants.php';
require_once 'JsonUtils.php';
require_once 'XMLUtils.php';
require_once 'JSONDisplay.php';
require_once 'DictionaryJSONCache.php';
require_once 'SanitizeUtil.php';

/**
 * 
 */
class WhatsAppRequest {

    private static $enum = array(
        -1 => "",
        1 => "t",
        2 => "w",
        3 => "l",
        4 => "a",
        5 => "n",
        6 => "c",
        7 => "h",
        8 => "p"
    );

    public static function toOrdinal($name) {
        return array_search($name, self::$enum);
    }

    public static function toString($ordinal) {
        return self::$enum[$ordinal];
    }

}

/**
 * 
 */
class WhatsAppBotUtil {
    public static $offlineMessage = "I am currently offline. Please try again later.";
    public static $isLive = TRUE;

    public static $aFullMenu = array(
        "/M menu",
        "/T translates phrase",
        "/W translates word",
        "/L gives list",
        //"/A gives audio", 
        "/P saves list as PDF",
        "/N gives number",
        "/C gives time",
        "/H help",
        "Voice note or Picture"
    );
    public static $aFullMenuDescriptions = array(
        "/M menu\nShows menu\n",
        "/T translates phrase\nExample \"Translate my wife\" or \"T my wife\" \n",
        "/W translates word\nExample \"Word love\" or \"W love\" \n",
        "/L gives list\nLists a category of words\nExample \"L months\" or \"List months\"\n",
        "/P saves list as PDF \nExample \"PDF animals\" or \"P animals\"\n",
        //"/A gives audio\nGive audio pronounciation for previous word\nExample A or audio\n", 
        "/N gives number\nExample \"number 15\" or \"N 15 \"\n",
        "/C gives time\nExample C, time or clock\n",
        "I am also able to understand voice notes and pictures."
    );
    public static $aListMenu = array("Names", "Years", "Seasons", "Months", "Days", "Numbers", "Weather", "Colors", "Animals", "Grasshoppers", "Vegetables", "Fruits", "Chiefdom", "Minerals", "Planets", "Family", "Body");
    public static $aSaveListMenu = array("Names", "Weather", "Colors", "Animals", "Grasshoppers", "Vegetables", "Fruits", "Chiefdom", "Minerals", "Planets", "Family", "Body");

    public function processQuery($queryString, $user) {
        if (WhatsAppBotUtil::$isLive == false) {
            return WhatsAppBotUtil::$offlineMessage;
        }
        
        $return = $this->processHelp();

        // Route request
        $request = $this->routeRequest($queryString, $user);
        $body = $this->getRequestBody($queryString, $user);

        switch ($request) {
            case WhatsAppRequest::toOrdinal("t"):
                $return = $this->processTranslatePhrase($body);
                break;
            case WhatsAppRequest::toOrdinal("w"):
                $return = $this->processTranslateWord($body);
                if (trim(strtolower($return)) == TranslatorService::$emptyValuePassed || trim(strtolower($return)) == "") {
                    $return = $this->processTranslatePhrase($body);
                }
                break;
            case WhatsAppRequest::toOrdinal("l"):
                $return = $this->processList($body);
                break;
            case WhatsAppRequest::toOrdinal("p"):
                $return = $this->processSaveList($body);
                break;
            //case WhatsAppRequest::toOrdinal("a"):
            //$return = "Sorry, I currently don't support audio.";
            //break;
            case WhatsAppRequest::toOrdinal("c"):
                $return = $this->processClock($body);
                break;
            case WhatsAppRequest::toOrdinal("n"):
                $return = $this->processNumber($body);
                break;
            case WhatsAppRequest::toOrdinal("h"):
                $return = $this->processHelp();
                break;
            default:
                if (str_word_count($queryString) == 1) {
                    $return = $this->processTranslateWord($queryString);
                } else {
                    $return = $this->processTranslatePhrase($queryString);
                }

                if (trim(strtolower($return)) == TranslatorService::$emptyValuePassed || trim(strtolower($return)) == "") {
                    $return = $this->processTranslatePhrase($queryString);
                }

                if (trim(strtolower($return)) == TranslatorService::$emptyValuePassed) {
                    $body = $queryString;

                    $return = "";
                }

                if (trim($return) != "") {
                    $return = "" . trim($return) . "\n\nType menu to see other features";
                }
                break;
        }

        if (trim(strtolower($return)) == TranslatorService::$emptyValuePassed) {
            $return = "You did not pass a word or phrase for me to translate.";
        }

        if (trim($return) == "") {
            $return = "Sorry, I am struggling to translate.";
        }
        return $return;
    }

    /**
     * 
     * @param type $phrase
     * @return type
     */
    public function processTranslatePhrase($phrase) {
        $aWebBackend = new WebBackend();

        $language = "english";
        $caller = "WhatsApp";

        $aJSON = "\"text\":\"$phrase\"," . "\"langauge\":\"$language\"," . "\"version\":\"$caller\"";
        $aJSON = "{" . $aJSON . "}";

        if (JSONDisplay::IsJson($aJSON) == FALSE) {
            return "Sorry, I am struggling to translate \"$phrase.\"";
        }

        $aJSON = json_decode($aJSON);
        $aReponseJSON = json_decode($aWebBackend->liveTranslate($aJSON));

        if ($aReponseJSON->status == 999) {
            $aMessage = $aReponseJSON->infoMessage;
        } else {
            $aMessage = $aReponseJSON->errorMessage;
        }

        return $aMessage;
    }

    /**
     * 
     * @param type $word
     * @return type
     */
    public function processTranslateWord($word) {
        $language = "english";
        $caller = "WhatsApp";

        $word = str_replace(".", "", $word);
        $aJSON = "\"text\":\"$word\"," . "\"langauge\":\"$language\"," . "\"version\":\"$caller\"";
        $aJSON = "{" . $aJSON . "}";

        if (JSONDisplay::IsJson($aJSON) == FALSE) {
            return "Sorry, I am struggling to translate \"$word.\"";
        }

        $aJSON = json_decode($aJSON);
        $aReponseJSON = json_decode(TranslatorUtil::translateWordEnglishToXitsonga($language, $word, $aJSON));

        if ($aReponseJSON->status == 999) {
            $aMessage = $aReponseJSON->infoMessage;
        } else {
            $aMessage = $aReponseJSON->errorMessage;
        }
        return $aMessage;
    }

    /**
     * 
     * @param type $number
     * @return type
     */
    public function processNumber($number) {
        $aWebBackend = new WebBackend();

        $aJSON = "\"number\":\"$number\"," . "\"langauge\":\"Number\"," . "\"version\": \"WhatsApp\"";
        $aJSON = json_decode("{" . $aJSON . "}");

        $aReponseJSON = json_decode($aWebBackend->askNumberAppNew($aJSON));

        if ($aReponseJSON->status == 999) {
            $aMessage = $aReponseJSON->infoMessage;
        } else {
            $aMessage = $aReponseJSON->errorMessage;
        }
        return $aMessage;
    }

    /**
     * 
     * @param type $time
     * @return type
     */
    public function processClock($time) {
        $aWebBackend = new WebBackend();
        $aJSON = "\"time\":\"$time\"," . "\"langauge\":\"Time\"," . "\"version\": \"WhatsApp\"";

        $aJSON = json_decode("{" . $aJSON . "}");

        $aReponseJSON = json_decode($aWebBackend->askTimeAppNew($aJSON));

        if ($aReponseJSON->status == 999) {
            $aMessage = $aReponseJSON->infoMessage;
        } else {
            $aMessage = $aReponseJSON->errorMessage;
        }
        return $aMessage;
    }

    /**
     * 
     */
    public function processSaveList($listName) {
        $aWebBackend = new WebBackend();

        $listName = ucfirst(strtolower($listName));

        if ($listName == "Jobs") {
            $listName = "job-titles";
        } else if ($listName == "Body") {
            $listName = "body-parts";
        } else if ($listName == "Planets") {
            $listName = "astronomy-planets";
        } else if ($listName == "Family") {
            $listName = "family-relationships";
        }

        if (in_array($listName, WhatsAppBotUtil::$aSaveListMenu) == FALSE) {
            $default = $default . "Bot supports saving following lists:";
            $default = $default . "\n";

            $return = $this->generateGenericMenu($default, WhatsAppBotUtil::$aSaveListMenu);
            $return = $return . "\n\nTry example \"P animals\" or \"PDF animals\"\n";
            return $return;
        }

        $listName = str_replace(".", "", $listName);
        $format = "pdf";

        $aJSON = "\"sub_type\":\"$listName\"," . "\"documentType\":\"$format\"," . "\"version\":\"WhatsApp\"";
        $aJSON = json_decode("{" . $aJSON . "}");

        $aReponseJSON = json_decode($aWebBackend->downloadTypeAsPDF($aJSON));

        if ($aReponseJSON->status == 999) {
            $aMessage = "https://www.xitsonga.org/generated/" . $aReponseJSON->infoMessage;
        } else {
            $aMessage = $aReponseJSON->errorMessage;
        }

        return $aMessage;
    }

    /**
     * 
     */
    public function processList($listName) {
        $aDefaultList = array("Months", "Days", "Seasons", "Numbers", "Years");
        $aWebBackend = new WebBackend();

        $listName = ucfirst(strtolower($listName));
        if (str_word_count($listName) > 1) {
            $nextInList = explode(" ", $listName)[1];
            $listName = explode(" ", $listName)[0];
        }

        if (in_array($listName, WhatsAppBotUtil::$aListMenu) == FALSE) {
            $default = $default . "Bot supports following lists:";
            $default = $default . "\n";

            $return = $this->generateGenericMenu($default, WhatsAppBotUtil::$aListMenu);
            $return = $return . "\n\nTry example \"L months\" or \"list months\"\n";
            return $return;
        }

        $OriginalListName = $listName;
        if ($listName == "Jobs") {
            $listName = "job-titles";
        } else if ($listName == "Body") {
            $listName = "body-parts";
        } else if ($listName == "Planets") {
            $listName = "astronomy-planets";
        } else if ($listName == "Family") {
            $listName = "family-relationships";
        }

        // DAYS
        $daysEn = Array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $daysXi = Array("Musumbhunuko", "Ravumbirhi", "Ravunharhu", "Ravumune", "Ravuntlhanu", "Mugimeto", "Sonto");

        // MONTHS
        $monthsEn = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $monthsXi = Array("Sunguti", "Nyenyenyana", "Nyenyankulu", "Dzivamusoko", "Mudyaxihi", "Khotavuxika", "Mawuwani", "Mhawuri", "Ndzhati", "Nhlangula", "Hukuri", "N'wendzamhala");

        // SEASONS
        $seasonsEn = Array("Summer", "Spring", "Winter", "Autum");
        $seasonsXi = Array("Ximumu", "Ximun'wana", "Xixika", "Xixikana");

        if ($listName == "Numbers") {
            $aNumber = new TsongaNumbers();

            $message = $message . strtoupper($listName);

            for ($aNumberCount = 0; $aNumberCount <= 40; $aNumberCount ++) {
                $aText = $aNumber->getNumberInTsonga($aNumberCount);

                $message = $message . "\n$aNumberCount - " . ucfirst($aText);
            }
        } else if ($listName == "Years") {
            $aNumber = new TsongaNumbers();

            $message = $message . strtoupper($listName);

            $aYears = Array("Lembe - Year", "N'waxemu - Last year", "Nyan'waka - This year", "Haxawa - Next year");
            for ($aNumberCount = 0; $aNumberCount <= count($aYears); $aNumberCount ++) {

                $message = $message . "\n" . ucfirst($aYears[$aNumberCount]);
            }

            for ($aNumberCount = 1990; $aNumberCount <= 2025; $aNumberCount ++) {
                $aText = $aNumber->getNumberInTsonga($aNumberCount);

                $message = $message . "\n$aNumberCount - " . ucfirst($aText);
            }
        } else if (in_array($listName, $aDefaultList)) {
            $index = 0;
            $message = $message . strtoupper($listName);

            foreach (${strtolower($listName) . "Xi"} as $key => $value) {
                $message = $message . "\n$value - " . ${strtolower($listName) . "En"}[$index ++];
            }
        } else {
            $dictionaryCache = file_get_contents(__DIR__ . "/../open/data.json");
            $json = json_decode($dictionaryCache);
            $listItems = array();


            foreach ($json->entities as $key => $value) {
                $word = trim($value->description);
                $translation = $value->translation;
                $type = trim($value->type);
                $subtype = trim($value->subtype);

                if ($type == "xitsonga" && $subtype == strtolower($listName)) {
                    if (strtolower($listName) == "grasshoppers") {
                        array_push($listItems, $word);
                    } else {
                        array_push($listItems, $word . " - " . $translation);
                    }
                }
            }

            $count = 0;
            $message = "";
            $message = $message . strtoupper($OriginalListName);
            foreach ($listItems as $key => $value) {
                if ($nextInList == "") {
                    if ($count < 50) {
                        $message = $message . "\n" . $value;
                    } else {
                        $message = $message . "\n\n" . "Complete list by sending \"list $listName next\"";
                        break;
                    }
                } else {
                    if ($count >= 50) {
                        $message = $message . "\n" . $value;
                    }
                }
                $count ++;
            }
        }

        return $message;
    }

    public function processHelp() {
        $default = $default . "I am Risuna\nA WhatsApp Bot to help you learn Xitsonga.";
        $default = $default . "\n";

        return $this->generateGenericMenu($default, WhatsAppBotUtil::$aFullMenuDescriptions);
    }

    /**
     * 
     * @param type $queryString
     * @param type $user
     * @return type
     */
    public function routeRequest($queryString, $user) {
        $aRequestClassfication = strtolower(explode(" ", trim($queryString))[0]);
        $aRequestMessage = trim(substr(trim($queryString), strlen($aRequestClassfication)));

        if ($aRequestClassfication == "translate") {
            $aRequestClassfication = "t";
        } else if ($aRequestClassfication == "number") {
            $aRequestClassfication = "n";
        } else if ($aRequestClassfication == "list") {
            $aRequestClassfication = "l";
        } else if ($aRequestClassfication == "time" || $aRequestClassfication == "clock") {
            $aRequestClassfication = "c";
        } else if ($aRequestClassfication == "pdf") {
            $aRequestClassfication = "p";
        } else if ($aRequestClassfication == "menu") {
            $aRequestClassfication = "h";
        } else if ($aRequestClassfication == "m") {
            $aRequestClassfication = "h";
        }

        if (strpos($aRequestClassfication, '/') !== false) {
            $aRequestClassfication = str_replace("/", "", $aRequestClassfication);
        }

        // Change translate to word if count in text is one word. 
        if ($aRequestClassfication == "t" && str_word_count($aRequestMessage) == 1) {
            $aRequestClassfication = "w";
        }
        // Change word to translate if count in text is more than one word. 
        else if ($aRequestClassfication == "w" && str_word_count($aRequestMessage) == 1) {
            $aRequestClassfication = "t";
        }


        $request = WhatsAppRequest::toOrdinal($aRequestClassfication);
        return $request;
    }

    public function eligibleImage($queryString, $user) {
        $imageURL = "";
        $dictionaryCache = file_get_contents(__DIR__ . "/../open/data.json");
        $json = json_decode($dictionaryCache);

        $request = $this->routeRequest($queryString, $user);
        if ($request == WhatsAppRequest::toOrdinal("t") || $request == WhatsAppRequest::toOrdinal("w")) {

            $word = $this->getRequestBody($queryString, $user);
            $word = str_replace(".", "", $word);
            $texts = explode(" ", strtolower($word));
            foreach ($json->entities as $key => $value) {
                $word = strtolower(trim($value->description));
                $translation = strtolower(trim($value->translation));
                $type = trim($value->type);
                $image = trim($value->image);

                if ($type == "xitsonga" && $image != "") {
                    if (in_array($word, $texts) || in_array($translation, $texts)) {
                        $imageURL = "https://www.xitsonga.org/assets/images/entity/" . $image;

                        break;
                    }
                }
            }
        }
        return $imageURL;
    }

    /**
     * 
     * @param type $queryString
     * @param type $user
     * @return type
     */
    public function getRequestBody($queryString, $user) {
        $aRequestClassfication = strtolower(explode(" ", trim($queryString))[0]);
        $aRequestMessage = trim(substr(trim($queryString), strlen($aRequestClassfication)));

        return $aRequestMessage;
    }

    /**
     * 
     * @return string
     */
    public function generateFullMenu() {
        $default = $default . "WhatsApp Bot to help you learn Xitsonga.";
        $default = $default . "\n";

        foreach (WhatsAppBotUtil::$aFullMenu as $key => $value) {
            $default = $default . "\n$value";
        }

        return $default;
    }

    /**
     * 
     * @return string
     */
    public function generateGenericMenu($default, $menuList) {
        foreach ($menuList as $key => $value) {
            $default = $default . "\n$value";
        }

        return $default;
    }

}
