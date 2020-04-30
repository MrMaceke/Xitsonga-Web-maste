<?php

require_once 'EntityDAO.php';
require_once 'EntityDetailsDAO.php';
require_once 'ItemTypeDAO.php';
require_once 'ExerciseDAO.php';

/**
 * Specifies page access per user
 * 
 * @Author Sneidon Dumela <me@sneidon.com>
 * @version 1.0
 */
class PageAccessController {

    /**
     * Defines Access Level
     */
    private $UNLOGGED_ACCESS = -1;
    private $BASIC_ACCESS = 0;
    private $USER_ACCESS = 1;
    private $ADMIN_ACCESS = 2;
    private $pages;
    private $meta;

    public function PageAccessController($aUser) {

        $this->pages = array(
            "index" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga &mdash; a free dictionary with thousands of words"),
            "translate" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Translate &mdash; a free xitsonga to english dictionary"),
            "vision" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Vision &mdash; a free xitsonga to english dictionary"),
            "status" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "System status &mdash; Xitsonga.org"),
            "chatbot" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Rivoningo, the ChatBot &mdash; a live Xitsonga ChatBox based on Eliza"),
            "api" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Dictionary API &mdash; a simple REST dictionary API"),
            "team" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Meet the team &mdash; Xitsonga.org"),
            "references" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Sources &mdash; Xitsonga.org"),
            "press" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Press &mdash; Xitsonga.org"),
            "android" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Product Offering &mdash; Xitsonga.org"),
            "services" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Services &mdash; Xitsonga.org"),
            "blackberry" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Blackberry &mdash; Download a free Xitsonga dictionary App"),
            "learn" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Learn Xitsonga"),
            "coronavirus" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Learn about Coronavirus &mdash; Xitsonga.org"),
            "videos" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Tutorials &mdash; Xitsonga.org"),  
            "contact" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Contact &mdash; Xitsonga.org"),
            "about" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "About &mdash; Xitsonga.org"), 
            "terms" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Legal &mdash; Xitsonga.org"),
            "disclaimer" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Disclaimer &mdash; Xitsonga.org"),
            "privacy" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Privacy &mdash; Xitsonga.org"),
            "login" => $this->returnArrayForPage($this->UNLOGGED_ACCESS, $aUser, "Login into &mdash; Xitsonga.org"),
            "accounts" => $this->returnArrayForPage($this->UNLOGGED_ACCESS, $aUser, "Accounts &mdash; Xitsonga.org"),
            "activate" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Activate account"),
            "encrypt" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Reset password"),
            "search" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Search Xitsonga Dictionary"),
            "scholar" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Dictionary"),
            "sayings" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Idioms & Proverbs"),
            "entertainment" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Writing - Xitsonga.org"),
            "literature" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga literature"),
            "grammar" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Language & Grammar"),
            "people" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Names & Surnames "),
            "exercises" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Online Exercises"),
            "register" => $this->returnArrayForPage($this->UNLOGGED_ACCESS, $aUser, "Register with &mdash; Xitsonga.org"),
            "access" => $this->returnArrayForPage($this->UNLOGGED_ACCESS, $aUser, "Oops &mdash; access denied"),
            "manage" => $this->returnArrayForPage($this->ADMIN_ACCESS, $aUser, "Administrator console &mdash; Xitsonga.org"),
            "myProfile" => $this->returnArrayForPage($this->USER_ACCESS, $aUser, "MyProfile"),
            "membership" => $this->returnArrayForPage($this->USER_ACCESS, $aUser, "Membership &mdash; Xitsonga.org"),
            "poems" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Poems - Swiphatu &mdash; Xitsonga.org"),
            "prayer" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "The Lord's prayer in Xitsonga"),
            "overloaded" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "System overloaded"),
            "profile" => $this->returnArrayForPage($this->BASIC_ACCESS, $aUser, "Xitsonga Contributor")
        );

        $this->meta = array(
            "index" => $this->returnArrayForMeta("A free dictionary with thousands of words translated from Xitsonga to English and English to Xitsonga.", "Xitsonga,xitsonga dictionary, xitsonga to english dictionary, english to xitsonga dictionary"),
            "learn" => $this->returnArrayForMeta("Free xitsonga online exercises", "xitsonga exercises,xitsonga online tests"),
            "contact" => $this->returnArrayForMeta("Share your thoughts with us.", "Xitsonga contact,Sneidon Dumela"),
            "about" => $this->returnArrayForMeta("Sharing information to help people learn Xitsonga.", "Xitsonga dictionary founding,sneidon dumela"),
            "login" => $this->returnArrayForMeta("Sign in with Xitsonga.org.", "Xitsonga"),
            "register" => $this->returnArrayForMeta("Sign up with Xitsonga.org..", "Xitsonga"),
            "scholar" => $this->returnArrayForMeta("Thousands of words translated from Xitsonga to English and English to Xitsonga.", "Xitsonga dictionary,Animals in Xitsonga,fruits in Xitsonga,xitsonga to english,english to xitsonga,time in xitsonga,numbers in Xitsonga"),
            "grammar" => $this->returnArrayForMeta("Hundreds of Xitsonga phrases translated to English, and items to help you construct sentences in Xitsonga.", "Xitsonga common phrases,xitsonga phrase to english,xitsonga phrases"),
            "people" => $this->returnArrayForMeta("Xitsonga names & surnames", ""),
            "entertainment" => $this->returnArrayForMeta("Xitsonga poems & short stories", ""),
            "sayings" => $this->returnArrayForMeta("Xitsonga proverbs with english translations.", "Xitsonga proverbs,swivuriso")
        );
    }

    /**

     */
    private function returnArrayForPage($aAccess, $aUser, $aTitle) {
        return array("access" => $this->determinePageAccessForUser($aAccess, $aUser), "title" => $aTitle);
    }

    private function returnArrayForMeta($aTitle, $keywords) {
        return array("keywords" => $keywords, "desc" => $aTitle);
    }

    /**
     * Uses User session to determine individual page access
     * 
     * @param Integer - aLevel, specifies required access level for page
     * @param DTOUser - Current user session
     * 
     * @return Boolean TRUE for granting access and FALSE for denying access
     */
    private function determinePageAccessForUser($aLevel, $aUser) {
        $aUserDTO = $aUser;

        $aReturn = false;

        if ($aLevel == $this->UNLOGGED_ACCESS) {
            $aReturn = $aUserDTO->isSignedIn() ? FALSE : TRUE;
        } else if ($aLevel == $this->BASIC_ACCESS) {
            $aReturn = true;
        } else if ($aLevel == $this->USER_ACCESS) {
            $aReturn = $aUserDTO->isSignedIn() ? TRUE : FALSE;
        } else if ($aLevel == $this->ADMIN_ACCESS) {
            $aReturn = $aUserDTO->isAdmin() ? TRUE : FALSE;
        }
        return $aReturn;
    }

    /**
     * Returns page title for HTML display
     * 
     * @param String - Page name
     * @return String - HTML page title
     */
    public function getPageTitle($aPageName, $data = NULL) {

        $title = $this->pages[$aPageName]["title"];
        if ($data == NULL or $aPageName == "manage" or $aPageName == "profile" or $aPageName == "membership") {
            return $this->pages[$aPageName]["title"];
        } else if ($data['sk'] == "tenses") {
            return "Verbs " . $data['name'] . " tense &mdash; " . $this->pages[$aPageName]["title"];
        } else if ($aPageName == "learn" AND $data['name'] != NULL) {
            $aExerciseDAO = new ExerciseDAO();
            $aURL = strtolower(str_replace("_", " ", $data['name']));
            $aResult = $aExerciseDAO->getExerciseByURL($aURL);
            if ($aResult[status]) {
                $title = ucfirst($aResult['resultsArray']['exercise_title']) . " &mdash; " . $this->pages[$aPageName]["title"];
            } else {
                return "Exercise not found";
            }
        } elseif ($data['name'] != NULL) {
            $aURL = strtolower(str_replace("_", " ", $data[name]));

            $data[name] = strtolower(str_replace("*", "'", $aURL));

            $aEntityDAO = new EntityDAO();
            $aResult = $aEntityDAO->getEntityByName($data[name]);
            if ($aResult['status']) {
                $aEntityDetailsDAO = new EntityDetailsDAO();
                $type = ItemTypeDAO::$ENGLISH_TRANS;
                $aResultTemp = $aEntityDetailsDAO->getEntityDetailsByEntityIdAndType($aResult['resultsArray'][0]['entity_id'], $type);
                if ($aResultTemp[status]) {
                    $translation = ucfirst($aResultTemp[resultsArray][content]);

                    $translation = (explode(".", $translation));

                    $subtile = substr($translation[0], 0, 100);

                    $aItemTypeDAO = new ItemTypeDAO();
                    $aTemp = $aItemTypeDAO->getItemTypeByID($aResult['resultsArray'][0][item_type]);
                    $aType = strtolower($aTemp[resultsArray][2]);

                    if ($subtile == "-") {
                        $title = ucfirst($aResult['resultsArray'][0]['entity_name']) . " &mdash; " . $this->pages[$aPageName]["title"];
                    } else if (strip_tags($aResultTemp[resultsArray][content]) != $aResultTemp[resultsArray][content]) {
                        $title = ucfirst($aResult['resultsArray'][0]['entity_name']) . " &mdash; " . ucfirst($aType);
                    } else {
                        $title = ucfirst($aResult['resultsArray'][0]['entity_name']) . " - " . $subtile . " &mdash; " . $this->pages[$aPageName]["title"];
                    }
                } else {
                    $title = ucfirst($aResult['resultsArray'][0]['entity_name']) . " &mdash; " . $this->pages[$aPageName]["title"];
                }
            } else {
                $title = "404 - Requested item was not found";
            }
        } elseif ($data['sk'] != NULL) {
            $aTitle = ucwords(str_replace("_", " ", $data['sk']));
            $title = $aTitle . " &mdash; " . $this->pages[$aPageName]["title"];
        }
        return $title;
    }

    public function getPageSecureFacebookImageURL($aPageName, $data = NULL) {
        $title = "https://www.xitsonga.org/assets/images/Artwork.png";
        if (isset($data['name'])) {
            $data[name] = strtolower(str_replace("_", " ", $data[name]));
            $aTitle = ucwords(str_replace("_", " ", $data['sk']));
            $aEntityDAO = new EntityDAO();
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aResult = $aEntityDAO->getEntityByName($data[name]);

            if ($aResult['status']) {
                $which = $_REQUEST[which];
                if ($which == "") {
                    $which = 0;
                }
                $aResultItem = $aResult['resultsArray'][$which];

                $aDetailResults = $aEntityDetails->getEntityDetailsByEntityId($aResultItem[entity_id]);
                if ($aDetailResults['status']) {
                    $array = array();
                    foreach ($aDetailResults['resultsArray'] as $aDetailResult) {
                        $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                $aImage = ($array[ItemTypeDAO::$IMAGE][content]);
                if ($aImage != "") {
                    $title = "https://www.xitsonga.org/assets/images/entity/$aImage";
                }
            }
        }
        return $title;
    }

    public function getPageUnsecureFacebookImageURL($aPageName, $data = NULL) {
        $title = "http://www.xitsonga.org/assets/images/Artwork.png";
        if (isset($data['name'])) {
            $data[name] = strtolower(str_replace("_", " ", $data[name]));
            $aTitle = ucwords(str_replace("_", " ", $data['sk']));
            $aEntityDAO = new EntityDAO();
            $aEntityDetails = new EntityDetailsDAO();
            $aItemTypeDAO = new ItemTypeDAO();
            $aResult = $aEntityDAO->getEntityByName($data[name]);

            if ($aResult['status']) {
                $which = $_REQUEST[which];
                if ($which == "") {
                    $which = 0;
                }
                $aResultItem = $aResult['resultsArray'][$which];

                $aDetailResults = $aEntityDetails->getEntityDetailsByEntityId($aResultItem[entity_id]);
                if ($aDetailResults['status']) {
                    $array = array();
                    foreach ($aDetailResults['resultsArray'] as $aDetailResult) {
                        $array[$aDetailResult[description]] = $aDetailResult;
                    }
                }
                $aImage = ($array[ItemTypeDAO::$IMAGE][content]);
                if ($aImage != "") {
                    $title = "http://www.xitsonga.org/assets/images/entity/$aImage";
                }
            }
        }
        return $title;
    }

    /**
     * Returns page title for HTML display
     * 
     * @param String - Page name
     * @return String - HTML page title
     */
    public function getPageMetadata($aPageName, $meta, $data = NULL) {
        $title = $this->meta[$aPageName][$meta];

        if ($data == NULL OR $meta == "desc") {
            $types = $data['sk'];
            if (isset($data['name'])) {
                $which = $_REQUEST[which];
                if ($which == "") {
                    $which = 0;
                }
                $data[name] = strtolower(str_replace("_", " ", $data[name]));
                $aTitle = ucwords(str_replace("_", " ", $data['sk']));
                $aEntityDAO = new EntityDAO();
                $aEntityDetails = new EntityDetailsDAO();
                $aItemTypeDAO = new ItemTypeDAO();
                $aResult = $aEntityDAO->getEntityByName($data[name]);
                $aTemp = $aItemTypeDAO->getItemTypeByID($aResult['resultsArray'][$which][item_type]);
                $aType = strtolower($aTemp[resultsArray][2]);

                if ($aResult['status']) {
                    $aResultItem = $aResult['resultsArray'][$which];
                    $aDetailResults = $aEntityDetails->getEntityDetailsByEntityId($aResultItem[entity_id]);
                    if ($aDetailResults['status']) {
                        $array = array();
                        foreach ($aDetailResults['resultsArray'] as $aDetailResult) {
                            $array[$aDetailResult[description]] = $aDetailResult;
                        }
                    }
                    $means = $array[ItemTypeDAO::$EXPLAINATION][content];
                    if ($means == "" OR $means == "-") {
                        $means = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                    }
                    if (strtolower($aType) == strtolower("english")) {
                        $title = "The English word &quot;" . ucfirst($aResult['resultsArray'][$which]['entity_name']) . "&quot; translates to &quot;" . ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) . "&quot; in Xitsonga - Xitsonga Dictionary";
                    } else if (strtolower($aType) == strtolower("xitsonga")) {
                        $title = "The Xitsonga word &quot;" . ucfirst($aResult['resultsArray'][$which]['entity_name']) . "&quot; translates to &quot;" . ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) . "&quot; in English - Xitsonga Dictionary";
                    } else if (strtolower($aType) == strtolower("proverbs")) {
                        $title = "The Xitsonga proverb &quot;" . ucfirst($aResult['resultsArray'][$which]['entity_name']) . "&quot; means &quot;" . ucfirst($means) . "&quot;- Xitsonga Dictionary";
                    } else if (strtolower($aType) == strtolower("idioms")) {
                        $title = "The Xitsonga idiom &quot;" . ucfirst($aResult['resultsArray'][$which]['entity_name']) . "&quot; means &quot;" . ucfirst($means) . "&quot;- Xitsonga Dictionary";
                    } else if (strtolower($aType) == strtolower("phrases")) {
                        $title = "The Xitsonga phrase &quot;" . ucfirst($aResult['resultsArray'][$which]['entity_name']) . "&quot; translates to &quot;" . ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]) . "&quot; in English - Xitsonga Dictionary";
                    }
                } else {
                    $title = "A dictionary with thousands of words translated from Xitsonga to English.";
                }
            } else if ($types == "verbs") {
                $title = "This is a list of verbs translated from Xitsonga to English.";
            } elseif ($types == "nouns") {
                $title = "This is a list of nouns translated from Xitsonga to English.";
            } elseif ($types == "colors") {
                $title = "This is a list of colors translated from Xitsonga to English.";
            } elseif ($types == "animals") {
                $title = "This is a list of birds, wild and domestic animals names translated from Xitsonga to English.";
            } elseif ($types == "fruits") {
                $title = "This is a list of fruits translated from Xitsonga to English.";
            } elseif ($types == "vegetables") {
                $title = "This is a list of vegetables translated from Xitsonga to English.";
            } elseif ($types == "money") {
                $title = "Money matters translated from Xitsonga to English.";
            } elseif ($types == "numbers") {
                $title = "Numbers in Xitsonga.";
            } elseif ($types == "time") {
                $title = "Time in Xitsonga.";
            } elseif ($types == "xitsonga") {
                $title = "A dictionary with thousands of words translated from Xitsonga to English.";
            } elseif ($types == "english") {
                $title = "A dictionary with thousands of words translated from English to Xitsonga.";
            } elseif ($types == "proverbs") {
                $title = "A list of  Xitsonga proverbs translated to English.";
            } elseif ($types == "sayings") {
                $title = "A list of  Xitsonga sayings translated to English.";
            } elseif ($types == "Measurements") {
                $title = "Units of measure translated from Xitsonga to English.";
            } elseif ($types == "terminology") {
                $title = "This is a list of terminology used in different industries translated from Xitsonga to English.";
            } elseif ($types == "adjectives") {
                $title = "This is a list of adjectives translated from Xitsonga to English";
            } elseif ($types == "greeting") {
                $title = "This is a list of ways of greeting people in Xitsonga.";
            } else if ($types == "surnames") {
                $title = "This is a list of Xitsonga surnames.";
            } else if ($types == "jokes") {
                $title = "This is a list of Xitsonga jokes.";
            } elseif ($types == "names") {
                $title = "This is a list of Xitsonga names translated from Xitsonga to English.";
            } elseif ($types == "cities") {
                $title = "This is a list of cities names translated from Xitsonga to English.";
            } elseif ($types == "countries") {
                $title = "This is a list of countries names translated from Xitsonga to English.";
            } else {
                $title = $this->meta[$aPageName][$meta];
            }
            return $title;
        } elseif (isset($data['name'])) {
            $which = $_REQUEST[which];
            if ($which == "") {
                $which = 0;
            }
            $data[name] = strtolower(str_replace("_", " ", $data[name]));
            $aTitle = ucwords(str_replace("_", " ", $data['sk']));
            $aEntityDAO = new EntityDAO();
            $aResult = $aEntityDAO->getEntityByName($data[name]);
            if ($aResult['status']) {
                if (strtolower($data[sk]) == strtolower("english")) {
                    $title = ucfirst($aResult['resultsArray'][$which]['entity_name']) . " in Xitsonga, translate " . ucfirst($aResult['resultsArray'][$which]['entity_name']) . " to xitsonga";
                } else {
                    $title = ucfirst($aResult['resultsArray'][$which]['entity_name']) . " in English, translate " . ucfirst($aResult['resultsArray'][$which]['entity_name']) . " to English" . ", meaning of " . ucfirst($aResult['resultsArray'][0]['entity_name']) . "," . ucfirst($aResult['resultsArray'][0]['entity_name']) . " meaning";
                }
            } else {
                $title = "Xitsonga to English, Xitsonga dictionary, Xitsonga translated to english";
            }
        } elseif ($data['sk'] != NULL) {
            $aTitle = ucwords(str_replace("_", " ", $data['sk']));
            $title = $aTitle . " in Xitsonga";
            if ($aTitle == "Xitsonga") {
                $title = "Xitsonga to English, Xitsonga dictionary, Xitsonga translated to english";
            } elseif ($aTitle == "English") {
                $title = "English to Xitsonga, Xitsonga dictionary, english translated to xitsonga.";
            }
        }
        return $title;
    }

    /**
     * 
     * @param String - Page name
     * @return type
     */
    public function hasAccess($aPageName) {
        return $this->pages[$aPageName]["access"];
    }

}

?>
