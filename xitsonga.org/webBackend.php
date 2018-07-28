<?php
    set_time_limit(720);
    date_default_timezone_set('Africa/Johannesburg');

    register_shutdown_function('shutdown');
    
    function shutdown() {
        $isError = false;
        $error = error_get_last();
        
        if ($error != NULL){
            switch($error['type']){
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    $isError = true;
                    break;
            }
        }
        if ($isError){
            echo $error[message];
        }
    }
    
    require_once './constants.php';
    require_once './dto/DTOUser.php';
    require_once './php/JsonUtils.php';
    require_once './php/XMLUtils.php';
    require_once './php/JSONDisplay.php';
    require_once './php/UseDAO.php';
    require_once './php/ItemTypeDAO.php';
    require_once './php/EntityDAO.php';
    require_once './php/ExerciseDAO.php';
    require_once './php/QuestionDAO.php';
    require_once './php/AnswersDAO.php';
    require_once './php/ActivationDAO.php';
    require_once './php/EntityDetailsDAO.php';
    require_once './php/AuditsAPICallsDAO.php';
    require_once './php/TranslationConfigDAO.php';
    require_once './php/DefinationCacheDAO.php';
    require_once './php/TranslationDAO.php';
    require_once './php/SendMail.php';
    require_once './php/PageAccessController.php';
    require_once './php/HTMLDisplay.php';
    require_once './php/TsongaNumbers.php';
    require_once './php/TsongaTime.php';
    require_once './php/InputValidator.php';
    require_once './php/BusinessDataValidator.php';
    require_once './php/fpdf.php';
    require_once "./php/ElizaBot.php";
    require_once "./php/TranslatorUtil.php";
        
    /* create backend instance for ajax calls*/
    if(isset($_REQUEST['system']) && ($_REQUEST['system'] == "android" || $_REQUEST['system'] == "iOS")){
        $aWebBackend = new WebBackend();

    	$data = trim(file_get_contents('php://input'));

  	if(isset($_REQUEST['inggput'])){
  		echo $aWebBackend->formatErrorFeebackToJSON("Your app is outdated. Please update from App store.");
  		return;
  	}
     	if(isset($_REQUEST['input']) && $_REQUEST['input'] == "array"){
        	$array = json_decode($data,true);
        	echo $aWebBackend->dynamicFunction($_REQUEST['type'],$array,"array");
     	}elseif(isset($_REQUEST['input']) && $_REQUEST['input'] == "string"){
	        $array = json_decode($data,true);
        	echo $aWebBackend->dynamicFunction($_REQUEST['type'],$array,"array");
     	}else{
        	echo $aWebBackend->dynamicFunction($_REQUEST['type'],$data);
     	}

    }
    else if($_REQUEST['data'] != null){
        $aWebBackend = new WebBackend();
        if($aWebBackend->startPHPSession()){
            echo $aWebBackend->dynamicFunction($_REQUEST['type'],$_REQUEST['data']);
        }else{
            echo $aWebBackend->formatErrorFeebackToJSON(BACKEND_PHP_ERROR_SESSION);
        }
    }else if($_REQUEST['method'] != null){
        $aMethod = $_REQUEST['method'];
        
        $aWebBackend = new WebBackend();

        if($aMethod == "translate") {
            $data[format] = strtolower($_REQUEST['format']);
            $data[word] = strtolower($_REQUEST['word']);
            $data[language] = strtolower($_REQUEST['language']);
        
            echo $aWebBackend->translation($data);
        }else if($aMethod == "generateDictionaryJSON") {
           
            echo $aWebBackend->generateDictionaryJSON();
        }else if($aMethod == "getTranslationForWordNew") {
           
            echo $aWebBackend->getTranslationForWordNew();
        }else{
            if(strtolower($_REQUEST['format']) == 'json'){
                echo $aWebBackend->formatErrorFeebackToJSON("Unsupported method"); 
            }else if(strtolower($_REQUEST['format']) == 'xml'){
                echo $aWebBackend->formatErrorFeebackToXML("Unsupported method"); 
            }else{
                echo $aWebBackend->formatErrorFeebackToJSON("Unsupported method");
            }
        }
    }
    /**
     * This is the backend for all components. 
     * 
     *-This backend is designed for xitsonga.org and cannot be reused for other systems.<br/>
     *-It is directly linked to the system's functions and database.
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @copyright (c) 2015, Sneidon Dumela
     * @version 1.0
     */
    class WebBackend{
        public static $USERS_MANAGE = 1;
        public static $USER_EMAIL = 2;
          
        private $aPageController;
        private $JsonUtil;
        private $XMLUtil;
        
        /**
         * Constructor - creates @link PageAccessController session
         */
        public function WebBackend() {
            $this->startPHPSession();
            $this->JsonUtil = new JSONUtils();
            $this->XMLUtil = new XMLUtils();
            $this->aPageController = new PageAccessController($this->getCurrentUser()) ;
        }
        /**
         * Dynamically calls specified function
         * 
         * @param String functionName
         * @param JSON|String data
         * @param String type
         * 
         * @return JSON|HTML content
         */
        public function dynamicFunction($functionName, $data = null,$input = "JSON") {
            if($data == null){
                $this->$functionName();
            }else{
                if($input == "JSON"){
                    $data = json_decode($data);
                }
                return $this->$functionName($data);
            }
        }
        /**
         * Starts PHP session
         * 
         *-If the session is already running, nothing is done.<br/>
         *-If session is not running, it starts a session. 
         * 
         * @see {php_session_start()}
         * @return boolean
         */
        public function startPHPSession() {
            if (!isset($_SESSION['running'])){
                session_start();
                $_SESSION['running'] = true;
            }
            return true;
        }
        /**
         * 
         * @param type $data
         */
        public function translation($data) {
            $aFormat = $data[format];
            
            $aInputValidation = new InputValidator();

            $aData[item] = ucfirst($data[word]);
            $aData[translation] = $aFormat;
            $aData[caller] = "Web";
            $aData[type] = "API";
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
            
            $aValidationResult = $aInputValidation->inputValidationTranslationAPI($data);
            if(!$aValidationResult[status]){                
                if($aFormat == "xml"){
                    return $this->formatErrorFeebackToXML($aValidationResult[message]);
                }
                return $this->formatErrorFeebackToJSON($aValidationResult[message]);
            }
            
            $aBusinessDataValdiator = new BusinessDataValidator();
            
            $aValidationResult = $aBusinessDataValdiator->businessDataValidationTranslationAPI($data);
            
            if(!$aValidationResult[status]){
                if($aFormat == "xml"){
                    return $this->formatErrorFeebackToXML($aValidationResult[message]);
                }
                return $this->formatErrorFeebackToJSON($aValidationResult[message]);
            }
            $aEntityDAO = new EntityDAO();
            
            $aResults = $aEntityDAO->getEntityByName($data[word]);
            if($aResults['status']){
                $aFound = FALSE;
                foreach ($aResults[resultsArray] as $key => $value) {
                   
                    if(strtolower($value[description]) == strtolower($data[language])){
                        $aFound = TRUE;
                        break;
                        
                    }
                }
                if($aFound){
                  if($aFormat == "xml"){
                     return JSONDisplay::GetEntitiesAPIXML($aResults[resultsArray],$data[language], OPERATION_SUCCESS, "Request successful");   
                  }
                  return JSONDisplay::GetEntitiesAPIJSON($aResults[resultsArray],$data[language], OPERATION_SUCCESS, "Request successful");  
                }
                
                if($aFormat == "xml"){
                    return $this->formatErrorFeebackToXML("No $data[language] word matching your criteria found on system.");
                }
                return $this->formatErrorFeebackToJSON("No $data[language] word matching your criteria found on system.");
            }else{
                if($aFormat == "xml"){
                    return $this->formatErrorFeebackToXML($aResults[message]);
                }
                return $this->formatErrorFeebackToJSON($aResults[message]);
            }
            
        }
        /**
         * 
         * @param type $param
         * @return type
         */
        public function downloadTypeAsPDF($param) {
            $aBusinessValidator = new BusinessDataValidator();
            
            $aValidation = $aBusinessValidator->businessDataValidationSessionAccess();
             if($this->getCurrentUser()->getEmail() != "sneidon@yahoo.com"){
               // return $this->formatErrorFeebackToJSON("We are adding new features to function. Please try again later.");
             }
            if($aValidation[status]){
                if($this->getCurrentUser()->isSignedIn()) {
                    if($param->sub_type == "proverbs" or strpos($param->sub_type,'proverbs') !== false){
                        $aFileName = "Free_Account"."_".ucfirst(GeneralUtils::generateId())."_"."swivuriso";
                    } else {
                        $aFileName = ucfirst(strtolower($this->getCurrentUser()->getFirstName()))."_".ucfirst(strtolower($this->getCurrentUser()->getLastName()))."_".$param->sub_type;
                    }
                } else {
                    if($param->sub_type == "proverbs" or strpos($param->sub_type,'proverbs') !== false){
                        $aFileName = "Free_Account"."_".ucfirst(GeneralUtils::generateId())."_"."swivuriso";
                    } else {
                        $aFileName = "Free_Account"."_".ucfirst(GeneralUtils::generateId())."_".$param->sub_type;
                    }
                }
                
                $array = array("xitsonga","english","proverbs","idioms","riddles","surnames","phrases");
                
                $aEntityDAO = new EntityDAO();
                $aEntityDetails = new EntityDetailsDAO();
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Helvetica');
                $pdf->AliasNbPages();
                 
                if(!in_array(strtolower($param->sub_type), $array)) {
                    $data["entity_sub_type"] = $param->sub_type;
                    $data["start"] = 0;
                    $data["end"] = 200;
                    
                    if($param->sub_type == "homonyms"  or $param->sub_type == "antonyms" or $param->sub_type == "synonyms") {
                        $aResults = $aEntityDAO->listEntityContainingSubType($data);
                    } else {
                        $aResults = $aEntityDAO->listEntityBySubType($data);
                    }
                    $title = "List of ".$param->sub_type." translated from Xitsonga to English";
                }else{
                    $data["entity_type"] = $param->sub_type;
                    $data["start"] = 0;
                    $data["end"] = 300;
                    
                    $aResults = $aEntityDAO->listEntityByType($data);
                    
                    if($param->sub_type == "xitsonga" or $param->sub_type =="english") {
                        $title = "List of 300 ".$param->sub_type." words translated from Xitsonga to English";
                    } else {
                        $title = "List of ".$param->sub_type." translated from Xitsonga to English";
                    }
                }
                
                if($aResults[status]){
                    
                    if($param->documentType =="text"){
                        $aContents = "Xitsonga.org\n\n$title\n\n";
                        $aCount = 1;
                        foreach ($aResults[resultsArray] as $key => $value) {

                            $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($value[entity_id]);
                            if($aDetailResults['status']){
                                $array = array();
                                foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                                  $array[$aDetailResult[description]] = $aDetailResult;
                                }
                            }
                            $aTranslation = ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]); 
                            
                            if($param->sub_type == "homonyms"  or $param->sub_type == "antonyms") {
                                $aContents = $aContents."$aCount. ".ucfirst($value[entity_name])." - ".$aTranslation."\n";
                                $aContents = $aContents." - ".ucfirst($array[ucfirst($param->sub_type)][content]);
                            } else {
                                $aContents = $aContents."$aCount. ".ucfirst($value[entity_name])."\n - ".$aTranslation;
                                //$aContents = $aContents.ucfirst($value[entity_name]);
                            }
                            
                            $aContents = $aContents."\n";
                            $aCount ++;
                        }
                        $aContents = $aContents."\n\nGenerated from https://www.xitsonga.org";
                        
                        $file = "generated/$aFileName.txt";
                        
                        $f = fopen("$file",'w');
                        if ($f !== false) {
                            ftruncate($f, 0);
                            fclose($f);
                        }
                        
                        file_put_contents($file, $aContents, FILE_APPEND | LOCK_EX);
                        
                        return $this->formatSuccessFeebackToJSON($aFileName.".txt");
                    } else {
                        $aCount = 1;
                        $pdf->SetFont('Helvetica',"",8);
                         $pdf->Ln(-5);
                        if($this->getCurrentUser()->isSignedIn()) {
                            $pdf->Cell(0,10,"To: ".$this->getCurrentUser()->getFirstName()." ".$this->getCurrentUser()->getLastName()." (".$this->getCurrentUser()->getEmail().")",0,1,"R");
                        } else {
                            $pdf->Cell(0,10,"To: "."Guest user",0,1,"R");
                        }
                       

                        $supportsImages = array("animals","fruits","trees","vegetables","colors","countries","cities","weather","minerals","astronomy-planets");
                        $long = false;
                      
			if($param->sub_type == "animals") {
			        $title = "A list of birds, wild and domestic animals names translated from Xitsonga to English";
			        $title = $title."\n - A bird is xinyenyana in Xitsonga.";
			        $title = $title."\n - A domestic animal is xiharhi xa le kaya in Xitsonga.";
			        $title = $title."\n - A wild animal is xiharhi xa le nhoveni in Xitsonga.";
			        $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "trees") {
			        $title = "A list tree names translated from Xitsonga to English";
			        $title = $title."\n - A tree is nsinya in Xitsonga.";
			        $title = $title."\n - The parts of the data was sourced from FanathePurp.";
			        $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "fruits") {
			        $title = "A list fruits names translated from Xitsonga to English";
			        $title = $title."\n - Fruits translates to mihandzu in Xitsonga.";
			        $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "vegetables") {
			        $title = "A list vegetables names translated from Xitsonga to English";
			        $title = $title."\n - Vegetables translates to matsavu in Xitsonga.";
			        $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			}  else if($param->sub_type == "job-titles") {
			        $title = "A list of job titles translated from Xitsonga to English";
			        $title = $title."\n - A job is ntirho in Xitsonga.";
			        $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			}  else if($param->sub_type == "family-relationships") {
			        $title = "A list of words used to descibe family relationships from Xitsonga to English";
			        $title = $title."\n - A family is ndyangu in Xitsonga.";
			        $title = $title."\n - A relationship is vuxaka in Xitsonga..";
			        $title = $title."\n - A relative is xaka in Xitsonga.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "direction") {
			        $title = "Directions translated from Xitsonga to English";
			        $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "astronomy-planets") {
			        $title = "Planet names and astronomy terms translated from Xitsonga to English";
			        $title = $title."\n - A planet is nyeleti in Xitsonga. Nyeleti also means means star.";
			        $title = $title."\n - A relationship is vuxaka in Xitsonga.";
			        $title = $title."\n - Astronomy is ntivo tinyeleti in Xitsonga which means knowledge of the stars.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "colors") {
			        $title = "A list of colors translated from Xitsonga to English";
			        $title = $title."\n - A colors is muhlovo in Xitsonga.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			}  else if($param->sub_type == "weather") {
			        $title = "A list of seasons and weather condiction translated from Xitsonga to English";
			        $title = $title."\n - Weather is maxelo in Xitsonga.";
			         $title = $title."\n - A season is nguva in Xitsonga.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "chiefdom") {
			        $title = "Words which describes chiefdom hierarchies translated from Xitsonga to English";
			        $title = $title."\n - Chiefdom is vuhosi in Xitsonga.";
			         $title = $title."\n - Parts of the data was sourced from http://www.vivmag.co.za";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "adverbs") {
			        $title = "A list of adverb translated from Xitsonga to English";
			        $title = $title."\n - A adverb is riengeteri in Xitsonga.";
			         $title = $title."\n - A adverb is a word or phrase that modifies or qualifies an adjective, verb, or other adverb or a word group, expressing a relation of place, time, circumstance, manner, cause, degree, etc. (e.g., gently, quite, then, there )";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "verbs") {
			        $title = "A list of verbs translated from Xitsonga to English";
			        $title = $title."\n - A verb is riendli in Xitsonga.";
			         $title = $title."\n - A verb is a word used to describe an action, state, or occurrence, and forming the main part of the predicate of a sentence, such as hear, become, happen.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "nouns") {
			        $title = "A list of nouns translated from Xitsonga to English";
			        $title = $title."\n - A noun is riviti in Xitsonga.";
			         $title = $title."\n - A noun is a part of speech that denotes a person, animal, place, thing, or idea.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			}  else if($param->sub_type == "pronouns") {
			        $title = "A list of pronouns translated from Xitsonga to English";
			        $title = $title."\n - A pronoun is risivi in Xitsonga.";
			         $title = $title."\n - A pronoun is a word that can function by itself as a noun phrase and that refers either to the participants in the discourse (e.g., I, you ) or to someone or something mentioned elsewhere in the discourse (e.g., she, it, this ).";
			         $title = $title."\n - Parts of the data was sourced from http://madyondza.blogspot.co.za";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "adjectives") {
			        $title = "A list of adjectives translated from Xitsonga to English";
			        $title = $title."\n - A adjective is rihlawuri in Xitsonga.";
			         $title = $title."\n - A adjective are words that describe or modify other words. They can identify or quantify another person or thing in the sentence.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "conjunctions") {
			        $title = "A list of conjunctions translated from Xitsonga to English";
			        $title = $title."\n - A conjunction is rihlanganisi in Xitsonga.";
			         $title = $title."\n - A conjunction is part of speech that is used to connect words, phrases, clauses, or sentences. Conjunctions are considered to be invariable grammar particle, and they may or may not stand between items they conjoin.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "homonyms") {
			        $title = "A list of homonyms translated from Xitsonga to English";
			        $title = $title."\n - A homonyms are mafana peletwana in Xitsonga.";
			         $title = $title."\n - A homonyms are words each of two or more words having the same spelling but different meanings and origins (e.g., pole and pole).";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "synonyms") {
			        $title = "A list of synonyms translated from Xitsonga to English";
			        $title = $title."\n - Synonyms are vamavizweni in Xitsonga.";
			         $title = $title."\n - A synonym a word having the same or nearly the same meaning as another in the language, as happy, joyful, elated.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "antonyms") {
			        $title = "A list of antonyms translated from Xitsonga to English";
			        $title = $title."\n - A antonyms are marito fularha in Xitsonga";
			         $title = $title."\n - An antonym is a word opposite in meaning to another (e.g., bad and good ).";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "greeting") {
			        $title = "A list of ways of greeting people in Xitsonga.";
			        $title = $title."\n - A phrase is xivulwana in Xitsonga";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			} else if($param->sub_type == "emotions") {
			        $title = "A list of phrases about Emotions.";
			        $title = $title."\n - A phrase is xivulwana in Xitsonga";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			} else if($param->sub_type == "how-to-ask") {
			        $title = "A list of way to ask for things";
			        $title = $title."\n - A phrase is xivulwana in Xitsonga";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			}  else if($param->sub_type == "proverbs") {
			        $title = "A list of Xitsonga proverbs translated to English.";
			        $title = $title."\n - A proverb is xivuriso in Xitsonga.";
			         $title = $title."\n - A proverb is a short pithy saying in general use, stating a general truth or piece of advice.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			}  else if($param->sub_type == "idioms") {
			        $title = "A list of Xitsonga idioms translated to English.";
			        $title = $title."\n - An idiom is xivulavulelo in Xitsonga.";
			         $title = $title."\n -An idiom is a group of words established by usage as having a meaning not deducible from those of the individual words (e.g., rain cats and dogs, see the light).";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			}  else if($param->sub_type == "riddles") {
			        $title = "A list of Xitsonga riddles translated to English.";
			        $title = $title."\n - A riddles is tshayito in Xitsonga.";
			         $title = $title."\n - An riddle is a question or statement intentionally phrased so as to require ingenuity in ascertaining its answer or meaning, typically presented as a game.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			}  else if($param->sub_type == "proverbs-about-life") {
			        $title = "A list of Xitsonga proverbs about life translated to English.";
			        $title = $title."\n - A proverb is xivuriso in Xitsonga.";
			         $title = $title."\n - A proverb is a short pithy saying in general use, stating a general truth or piece of advice.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			} else if($param->sub_type == "proverbs-about-death") {
			        $title = "A list of Xitsonga proverbs about death translated to English.";
			        $title = $title."\n - A proverb is xivuriso in Xitsonga.";
			         $title = $title."\n - A proverb is a short pithy saying in general use, stating a general truth or piece of advice.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			} else if($param->sub_type == "proverbs-about-animals") {
			        $title = "A list of Xitsonga proverbs about animals translated to English.";
			        $title = $title."\n - A proverb is xivuriso in Xitsonga.";
			         $title = $title."\n - A proverb is a short pithy saying in general use, stating a general truth or piece of advice.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			} else if($param->sub_type == "proverbs-about-fruits") {
			        $title = "A list of Xitsonga proverbs about fruits translated to English.";
			        $title = $title."\n - A proverb is xivuriso in Xitsonga.";
			         $title = $title."\n - A proverb is a short pithy saying in general use, stating a general truth or piece of advice.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			          $long = true;
			} else if($param->sub_type == "names") {
			        $title = "A list of Xitsonga names translated from Xitsonga to English.";
			        $title = $title."\n - A name is vito in Xitsonga.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "english") {
			        $title = "A list of 300 words translated from English to Xitsonga.";
			        $title = $title."\n - The list was compiled by website editors.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			} else if($param->sub_type == "xitsonga") {
			        $title = "A list of 300 words translated from Xitsonga to English .";
			        $title = $title."\n - The list was compiled by website editors.";
			         $title = $title."\n - Please read our legal page for terms of use.";
			        $title = $title."\n - The community is correcting the information on this document.";
			        $title = $title."";
			}


			$pdf->setFillColor(251,251,251); 
			$pdf->SetFont('Helvetica',"",9);
                	$pdf->MultiCell(0,5,$title,1,1,true);
                	$pdf->Ln(5);
                	$pdf->SetFont('Helvetica',"",9);
                	
                        $aX = 10;
                        $aY = 50;
                        if($long == false) {
                        $pdf->SetFont('Helvetica',"B",9);
                            if($param->sub_type == "homonyms"  or $param->sub_type == "antonyms" or $param->sub_type == "synonyms") {
                               	    $pdf->Cell(10,5,"",0,0); 
	                            $pdf->Cell(40,5,"Xitsonga",0,0);
	                            $pdf->Cell(60,5,"English",0,0);
	                            $pdf->Cell(70,5,ucfirst($param->sub_type),0,1);
	                            $pdf->SetFont('Helvetica',"",9);
                            } else {
                                 if(!in_array(strtolower($param->sub_type), $supportsImages)) {
	                                 if($param->sub_type == "english") {
		                            $pdf->Cell(10,5,"",0,0); 
		                            $pdf->Cell(40,5,"English",0,0);
		                            $pdf->Cell(130,5,"Xitsonga",0,1);
		                            $pdf->SetFont('Helvetica',"",9);
		                         } else {
		                            $pdf->Cell(10,5,"",0,0); 
		                            $pdf->Cell(40,5,"Xitsonga",0,0);
		                            $pdf->Cell(130,5,"English",0,1);
		                            $pdf->SetFont('Helvetica',"",9);
		                         }
		                 }
	                   }
                        } else {
                            if($param->sub_type == "proverbs" or strpos($param->sub_type,'proverbs') !== false){ 
                            } else {
                            $pdf->SetFont('Helvetica',"B",9);
                   	    $pdf->Cell(10,5,"",0,0); 
                            $pdf->Cell(90,5,"Xitsonga",0,0);
                            $pdf->Cell(90,5,"English",0,1);                            
                            $pdf->SetFont('Helvetica',"",9);
                            }
                        }
                         $pdf->SetFont('Helvetica',"",9);
                        foreach ($aResults[resultsArray] as $key => $value) {

                            $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($value[entity_id]);
                            if($aDetailResults['status']){
                                $array = array();
                                foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                                  $array[$aDetailResult[description]] = $aDetailResult;
                                }
                            }
                            $aTranslation = ucfirst($array[ItemTypeDAO::$ENGLISH_TRANS][content]); 
                           $aExplaination = ucfirst($array[ItemTypeDAO::$EXPLAINATION][content]); 
                            if(in_array(strtolower($param->sub_type), $supportsImages)) {
                                $aImage = $array[ItemTypeDAO::$IMAGE][content];
                                if($aImage == ""){
                                    $aImage = "no_image.jpg";
                                }
				//.". ".ucfirst($value[entity_name])." - ".$aTranslation
                               
                                //.". ".ucfirst($value[entity_name])." - ".$aTranslation
                                $pdf->Cell(10,5,$aCount.".", 0, 0, 'L', false);                     
                                $pdf->Cell(20,5,$pdf->Image("assets/images/entity/thumb_$aImage", 19, $pdf->GetY(), 20), 0, 0, 'L', false);
                                $pdf->MultiCell(160,5,ucfirst($value[entity_name])."\n".$aTranslation, 0, 1);             
				$pdf->Ln(15);
                            }else {
                                if($param->sub_type == "proverbs" or $param->sub_type == "idioms" or $param->sub_type == "riddles" or strpos($param->sub_type,'proverbs') !== false){
                                    $pdf->setFillColor(254,225,135); 
                                    $pdf->Cell(10,5,$aCount,1,0,'',true); 
	                            $pdf->Cell(180,5,ucfirst($value[entity_name]),1,1);
	                            $pdf->MultiCell(190,5,"$aTranslation",1,1);
	                            $pdf->MultiCell(190,5,"$aExplaination ",1,1);
	                            $pdf->Ln(5);
                                } else if($param->sub_type == "surnames"){
                                    $pdf->Cell(0,10,$aCount.". ".ucfirst($value[entity_name]),0,1); 
                                } else if($param->sub_type == "homonyms"  or $param->sub_type == "antonyms" or $param->sub_type == "synonyms") {
                                    if($long == false) {
                                          
	                                    $pdf->Cell(10,5,$aCount,1,0); 
	                                    $pdf->Cell(40,5,ucfirst($value[entity_name]),1,0);
	                                    $pdf->Cell(60,5,$aTranslation,1,0);
	                                    $pdf->Cell(80,5,ucfirst(($array[ucfirst($param->sub_type)][content])),1,1);
	                             }
                                } else {
                                    if($long == false) {
	                                    $pdf->Cell(10,5,$aCount,1,0); 
	                                    $pdf->Cell(40,5,ucfirst($value[entity_name]),1,0);
	                                    $pdf->Cell(140,5,$aTranslation,1,1);
	                             } else {
	                               $pdf->Cell(10,5,$aCount,1,0); 
	                               $pdf->Cell(90,5,ucfirst($value[entity_name]),1,0);
	                               $pdf->Cell(90,5,$aTranslation,1,1);
	                             }
                                }
                            }

                            $aCount ++;
                        }
                        $pdf->Output("generated/".$aFileName.".pdf","F");
                        return $this->formatSuccessFeebackToJSON($aFileName.".pdf");
                    }
                }else{
                    return $this->formatErrorFeebackToJSON("No entities found in system. Try again later");
                }

            }
            return $this->formatErrorFeebackToJSON($aValidation['message']);
        }
        
        public function checkServerMessage() {
            $api_key =  $_REQUEST[api_key];
            $version =  $_REQUEST[version];
            
            $aEntityDAO = new EntityDAO();
             
            $aData['entity_type'] = "System-messages";
            $aResults = $aEntityDAO->listEntityByTypeSortByDate($aData);
            if($aResults['status']){
                $aEntityDetails = new EntityDetailsDAO();
                $id = $aResults[resultsArray]["entity_id"];
                    
                $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($id);
                if($aDetailResults['status']){
                    $array = array();
                    foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                      $array[$aDetailResult[description]] = $aDetailResult;
                    }
                    $aOperationStatus = $array[ItemTypeDAO::$OPERATION_STATUS][content];
                    $aTitle = $aResults[resultsArray]["entity_name"];
                    $aContent = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                    $aUpdate = $array[ItemTypeDAO::$APP_UPDATE][content];
                    
                    
                    if($aUpdate == "" OR !is_numeric($aUpdate)){
                        $aUpdate = 0;
                    }
                    echo JSONDisplay::GetServerMessageJSON($aOperationStatus, $aTitle, $aContent, $aUpdate, $id);
                }else {
                    echo JSONDisplay::GetServerMessageJSON(OPERATION_FAILED, "In my life we will always", "In my life we will always", 0, 0);
                }
            } else {
                echo JSONDisplay::GetServerMessageJSON(OPERATION_FAILED, "In my life we will always", "In my life we will always", 0, 0);
            }
        }
        
        public function getTranslationForWordNew(){
            $word =  $_REQUEST[word];
            $trans =  $_REQUEST[translation];
            $version =  $_REQUEST[version];
            $type =  $_REQUEST[lang];
            
            $aData[item] = $word;
            $aData[translation] = $trans == ""?"Not provided":$trans;
            $aData[caller] = $version == ""?"android_v4.0":$version;
            $aData[type] = $type == ""?"Not specified":$type;
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
            
            $searchWord =  ($type == "xitsonga"?  $trans:$word);
            $aSearch = str_replace(",", ".", $searchWord);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);

            $aActive = FALSE;
            $aEdited = FALSE;
            $aImage = NULL;
 	    $searchWord =  ($type == "xitsonga"?  $trans:$word);
            $aSearch = $searchWord;
            $aSearch = str_replace(",", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = explode(".", $aSearch);
            $aSearch = $aSearch[0];
            
            $post = file_get_contents('http://services.aonaware.com/DictService/DictService.asmx/Define',FALSE,stream_context_create(array(
                    'http' => array(
                        'protocol_version' => 1.1,
                        'user_agent'       => 'PHPExample',
                        'method'           => 'POST',
                        'header'           => "Connection: close rn" .
                                              "Content-length: " . (5  + strlen($aSearch)) . "rn",
                        'content'          => "word=$aSearch",
                        'timeout'          => 1,
                    ),
                )));

                $aExtras = simplexml_load_string($post);
                
                $aDisplay = "";

                $aExtraCount = 0;
                $aTrue = FALSE;

                if($aExtras != NULL){
                    $aSplitter = "1:";
                    for($index = 2; $index < 30; $index ++){
                        $aSplitter = $aSplitter."|$index:";
                    }
                    
                    $aSplitter2 = " 1.";
                    for($index = 2; $index < 30; $index ++){
                        $aSplitter2 = $aSplitter2."| $index".".";
                    }
                    
                    foreach ($aExtras->Definitions->Definition as $aExtra){
                        $aExtraCount ++;
                        if(count($aExtras->Definitions->Definition) == $aExtraCount){
                            $aArray =  preg_split("/$aSplitter/", $aExtra->WordDefinition);
                            
                            if($aExtra->Dictionary->Name == "The Collaborative International Dictionary of English v.0.44") {
                               $aArray =  preg_split("/$aSplitter2/", $aExtra->WordDefinition); 
                            }
                            for($index = 0; $index < count($aArray); $index ++){
                                $indexCount = $index + 1;
                                $aDisplay = $aDisplay.(ucfirst(($aArray[$index])))."@@@\n";
                            }
                        }
  
                        $aTrue = TRUE;
                    }
                }
                if(!$aTrue){
                     $aDisplay = '-@@@';
                } else {
                    $aDefinationCacheDAO = new DefinationCacheDAO();
                    $aDefinationCacheDAO->AddCache($aSearch, $aDisplay,"mobile");
                }
    
            return $aDisplay;
        }
        
        public function getTranslationForWord(){
            $word =  $_REQUEST[word];
            $trans =  $_REQUEST[translation];
            $version =  $_REQUEST[version];
            $type =  $_REQUEST[lang];
            
            $aData[item] = $word;
            $aData[translation] = $trans == ""?"Not provided":$trans;
            $aData[caller] = $version == ""?"android_v2.3":$version;
            $aData[type] = $type == ""?"Not specified":$type;
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
            
            $searchWord =  ($type == "xitsonga"?  $trans:$word);
            $aSearch = str_replace(",", ".", $searchWord);
            $aSearch = str_replace("‚", ".", $aSearch);
            $aSearch = str_replace("‚", ".", $aSearch);
            
            $aEntityDAO = new EntityDAO();
            
            $aURL = strtolower(str_replace("_"," ", $aData[item]));
            
            $aURL = strtolower(str_replace("*","'",$aURL));
            
            $aResults = $aEntityDAO->getEntityByName($aURL);
            $aActive = FALSE;
            $aEdited = FALSE;
            $aImage = NULL;
            
           $aARRAY = "[";
	$aARRAY = $aARRAY."{\"PartOfSpeech\": \"Server\", \"Definitions\":[\"<span style ='color:red'>Your app is outdated. Please update from App store.</span>\"]},";
 $aARRAY = $aARRAY."{\"PartOfSpeech\": \"Update Now\", \"Definitions\":[\"<br/><table><tr><td style ='width:30%'><img width ='100%' style ='border:2px solid white' src ='https://lh3.googleusercontent.com/0gT97kDGR77wjNNP_tWHPo2EGIOMqW4P6izbdRaAg4ItuUwhi6CPCHNrpDc6lJEAnpc=w300'/></td><td><a href =https://play.google.com/store/apps/details?id=com.sneidon.ts.dictionary'>Xitsonga Dictionary</a> - Click to get the mandatory update.</td></tr></table>\"]}";
	
		//$aARRAY = $aARRAY. ",";
	
	

	$aARRAY = $aARRAY. "]";

            
            return "getTranslationForWord(".$aARRAY.")";
        }
        /**
         * Updates or creates a rate detail record for an entity.
         * 
         * @param JSON data
         * @return JSON
         */
        public function rateEntity($data) {
            $aEntityDetails = new EntityDetailsDAO();
            
            // Allow unlogged users to rate entities
            if($this->getCurrentUser()->isSignedIn()){
                $aUserID = $this->getCurrentUser()->getUserID();
            }else{
                $aUserDAO = new UserDAO();
                $aResultUser = $aUserDAO->findRecordWithEmail(json_decode("{\"email\":\"unknown@user.com\"}"));
                if($aResultUser['status']){
                    $aUserID = $aResultUser[resultsArray][user_id];
                }
            }
            
            $aResult = $aEntityDetails->getEntityDetailsByEntityIdAndType($data->entity_id,"Rating");
            if(!$aResult['status']){
                $rating = $data->content;
                $data->content = "1_".$rating;
                $aResult = $aEntityDetails->addEntityDetail($data, $data->entity_id, $aUserID);
                if($aResult['status']){
                    return $this->formatSuccessFeebackToJSON($data->content, OPERATION_SUCCESS);
                }
            }else{
                $content = $aResult[resultsArray]["content"];
                $rating_array = explode("_",$content);
                $people = $rating_array[0] + 1;
                $total = $rating_array[1] + $data->content;
                        
                $id = $aResult[resultsArray]["entity_details_id"];
                $new_rating = $people."_".$total;
                
                $json_string = "{\"id\":\"$id\",\"content\":\"$new_rating\"}";
                $array = json_decode($json_string);
                
                $aTempResult = $aEntityDetails->editEntityDetail($array,$aUserID);
                if($aTempResult['status']){
                    return $this->formatSuccessFeebackToJSON($new_rating, OPERATION_SUCCESS);
                }
            }
            return $this->formatErrorFeebackToJSON($aResult['message']);
        }
        /**
         * Adds detail for entity
         * 
         * @param JSON data
         * @return JSON
         */
        public function addEntityDetail($data) {
            $aEntityDetails = new EntityDetailsDAO();

            $aResult = $aEntityDetails->addEntityDetail($data, $data->entity_id, $this->getCurrentUser()->getUserID());
            if($aResult['status']){
                return $this->formatSuccessFeebackToJSON("Description Added Succesfully", OPERATION_SUCCESS);
            }
            return $this->formatErrorFeebackToJSON($aResult['message']);
        }
        /**
         * Adds a new entity to the system
         * 
         * @param JSON data
         * @return JSON
         */
        public function addEntity($data) {
            
            //Validate input
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationAddEntity($data);
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            //Business validator
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessValidation = $aBusinessDataValidator->businessDataValidationAddEntity($data,$this->getCurrentUser());
            if(!$aBusinessValidation[status]){
                return $this->formatErrorFeebackToJSON($aBusinessValidation['message']);
            }
            
            $aEntity = new EntityDAO();            
            $aResult = $aEntity->addEntity($data,$this->getCurrentUser()->getUserID());
            if($aResult['status']){
                return $this->formatSuccessFeebackToJSON($aResult["message"], OPERATION_SUCCESS);
            }else{
                if($aResult['exists'] == TRUE){
                    return $this->formatErrorFeebackToJSON($aResult['message'], 998);    
                }else{
                    return $this->formatErrorFeebackToJSON($aResult['message']);
                }
            }
        }
        
        /**
         * Adds a new entity to the system
         * 
         * @param JSON data
         * @return JSON
         */
        public function addTranslationConfig($data) {
            //Validate input
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationAddTranslationConfig($data);
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
                
            //Business validator
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
            if(!$aBusinessValidation[status]){
                return $this->formatErrorFeebackToJSON($aBusinessValidation['message']);
            }
            
            $aTranslationConfigDAO = new TranslationConfigDAO();      
            
            $aResult = $aTranslationConfigDAO->AddTranslationConfig($data,$this->getCurrentUser()->getUserID());
            
            if($aResult['status']){
                return $this->formatSuccessFeebackToJSON("Translation added successfully", OPERATION_SUCCESS);
            }else{
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
        }
        /**
         * Update exercise
         * 
         * @return JSON data
         * @param JSON data
         */
        public function editExercise($data) {
            if($data->delete == "0"){
                //Business validator
                $aBusinessDataValidator = new BusinessDataValidator();
                $aBusinessValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
                if(!$aBusinessValidation[status]){
                    return $this->formatErrorFeebackToJSON($aBusinessValidation['message']);
                }
                
                $aExerciseDAO = new ExerciseDAO();
                
                $aResults = $aExerciseDAO->removeExercise($data);
                if($aResults['status']){      
                    return $this->formatSuccessFeebackToJSON($aResults['message']); 
                }
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }else{
                $aInputValidator = new InputValidator();
                $aValidation = $aInputValidator->inputValidationAddExercise($data);

                if(!$aValidation[status]){
                    return $this->formatErrorFeebackToJSON($aValidation['message']);
                }

                //Business data validation
                $aBusinessDataValidator = new BusinessDataValidator();
                $aBusinessDataValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());

                if(!$aBusinessDataValidation[status]){
                    return $this->formatErrorFeebackToJSON($aBusinessDataValidation['message']);
                }

                $aExerciseDAO = new ExerciseDAO();            
                $aResult = $aExerciseDAO->editExercise($data,$this->getCurrentUser()->getUserID());
                if($aResult['status']){
                    return $this->formatSuccessFeebackToJSON("Exercise updated succesfully", OPERATION_SUCCESS);
                }else{
                    return $this->formatErrorFeebackToJSON($aResult['message']);
                }
            }
        }
        /**
         * Create a new exercise on system
         * 
         * @param JSON data {String,"title"} {String,"text"}
         * @return JSON
         */
        public function addNewExercise($data) {
            
            //Input validation
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationAddExercise($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            //Business data validation
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessDataValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
            
            if(!$aBusinessDataValidation[status]){
                return $this->formatErrorFeebackToJSON($aBusinessDataValidation['message']);
            }
            
            $aExerciseDAO = new ExerciseDAO();            
            $aResult = $aExerciseDAO->addNewExercise($data,$this->getCurrentUser()->getUserID());
            if($aResult['status']){
                return $this->formatSuccessFeebackToJSON($aResult['message'], OPERATION_SUCCESS);
            }else{
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
        }
        /**
         * Gets answers for question id
         * 
         * @return JSON data
         * @param JSON data
         */
        public function getAnswersByQuestionID($data) {
            $aAnswersDAO = new AnswersDAO();
             
            $aResults = $aAnswersDAO->getAnswersByQuestionID($data->questionID);
            
            if($aResults[status]){
                return JSONDisplay::GetAnswersJSON($aResults[resultsArray], OPERATION_SUCCESS, "Answers retrieved succesfully");
            }
            return $this->formatErrorFeebackToJSON($aResults['message']);
        }
        /**
         * Add answers for question
         * 
         * @return JSON data
         * @param JSON data
         */
        public function addAnswersForQuestion($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationAddAnswers($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            //Business data validation
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessDataValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
            
            if(!$aBusinessDataValidation[status]){
                return $this->formatErrorFeebackToJSON($aBusinessDataValidation['message']);
            }
            
            $aAnswersDAO = new AnswersDAO();
            $aResult = $aAnswersDAO->deleteAnswersByQuestionID($data->questionID);
            if($aResult[status]){
                $aFailed = FALSE;
                foreach ($data->answers as $key => $value) {
                   $aResult = $aAnswersDAO->addNewAnswer($value,$data->questionID,$this->getCurrentUser()->getUserID());
                   if(!$aResult[status]){
                       $aFailed = TRUE;
                   }
                }
                if(!$aFailed){
                    return $this->formatSuccessFeebackToJSON("Answers added/updated Succesfully", OPERATION_SUCCESS);
                }else{
                     return $this->formatErrorFeebackToJSON($aResult[message]."System error occured while adding one of the answers");
                }
            }else{
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
        }
        /**
         * Edits question for an exercise
         * 
         * @param JSON data
         * @return JSON
         */
        public function editQuestion($data) {
            if($data->delete == "0"){
                //Business validator
                $aBusinessDataValidator = new BusinessDataValidator();
                $aBusinessValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
                if(!$aBusinessValidation[status]){
                    return $this->formatErrorFeebackToJSON($aBusinessValidation['message']);
                }
                
                $aQuestionDAO = new QuestionDAO();
                
                $aResults = $aQuestionDAO->removeQuestion($data);
                if($aResults['status']){      
                    return $this->formatSuccessFeebackToJSON($aResults['message']); 
                }
                return $this->formatErrorFeebackToJSON($aResults['message']);
            } else {
                $aInputValidator = new InputValidator();
                $aValidation = $aInputValidator->inputValidationAddQuestion($data);

                if(!$aValidation[status]){
                    return $this->formatErrorFeebackToJSON($aValidation['message']);
                }

                //Business data validation
                $aBusinessDataValidator = new BusinessDataValidator();
                $aBusinessDataValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());

                if(!$aBusinessDataValidation[status]){
                    return $this->formatErrorFeebackToJSON($aBusinessDataValidation['message']);
                }

                $aQuestionDAO = new QuestionDAO();
                $aResult = $aQuestionDAO->editQuestion($data);
                if($aResult['status']){
                    return $this->formatSuccessFeebackToJSON("Question updated Succesfully", OPERATION_SUCCESS);
                }else{
                    return $this->formatErrorFeebackToJSON($aResult['message']);
                }
            }
        }
        /**
         * Creates question for an exercise
         * 
         * @return JSON string
         * @param JSON data
         */
        public function addNewQuestion($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationAddQuestion($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            //Business data validation
            $aBusinessDataValidator = new BusinessDataValidator();
            $aBusinessDataValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
            
            if(!$aBusinessDataValidation[status]){
                return $this->formatErrorFeebackToJSON($aBusinessDataValidation['message']);
            }
            
            $aQuestionDAO = new QuestionDAO();
            $aResult = $aQuestionDAO->addNewQuestion($data,$this->getCurrentUser()->getUserID());
            
            if($aResult['status']){
                return $this->formatSuccessFeebackToJSON($aResult[message], OPERATION_SUCCESS);
            }else{
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
            
            return $this->formatSuccessFeebackToJSON("Question Added Succesfully", OPERATION_SUCCESS);
        }
        /**
         * Gets exercise by URL
         * 
         * @param String aURL
         * @return PHPArray
         */
        public function getExerciseByURL($aURL) {
            $aURL = strtolower(str_replace("_"," ",$aURL));
            
            $aExerciseDAO = new ExerciseDAO();
            
            $aResult = $aExerciseDAO->getExerciseByURL($aURL);
            
            return $aResult;
        }
        /**
         * Adds a new search item
         * 
         * @param JSON data
         * @return PHPArray
         */
        public function addSearchItem($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationAddEntity($data);
            
            if(!$aValidation[status]){
                return $aValidation;
            }
            $aEntity = new EntityDAO(); 
            
            if($this->getCurrentUser()->isSignedIn()){
                $aUserID = $this->getCurrentUser()->getUserID();
            }else{
                $aUserDAO = new UserDAO();
                
                $aResultUser = $aUserDAO->findRecordWithEmail(json_decode("{\"email\":\"unknown@user.com\"}"));
                if($aResultUser['status']){
                    $aUserID = $aResultUser[resultsArray][user_id];
                }
            }
            
            $aResult = $aEntity->addEntity($data,$aUserID);
            if($aResult['status']){
                return $aResult;
            }
        }
        /**
         * Adds a few number of entities
         * 
         * @param JSON data
         * @return JSON
         */
        public function addEntityBulk($data) {
            $aEntity = new EntityDAO();
            $message = "<br/><b>Batch ".$data->batch_no." results</b><br/>";
            $status = true;
            foreach ($data->entity as $key => $value) {
                $aInputValidator = new InputValidator();
                $aResult = $aInputValidator->inputValidationAddEntity($value);

                if($aResult[status]){
                    $aResult = $aEntity->addEntity($value,$this->getCurrentUser()->getUserID());
                }
                
                if(!$aResult[status]){
                    $status = $aResult[status];
                    $message = $message." <br/> <span style ='color:red'>".$aResult['message']."</span>";
                }else{
                    $message = $message." <br/> <span style ='color:green'>Entity ".$value->name." added successfully.</span>";
                }
            }
            if($status){
                return $this->formatSuccessFeebackToJSON($message, OPERATION_SUCCESS);
            }else{
                return $this->formatErrorFeebackToJSON($message, OPERATION_FAILED);
            }
        }
        /**
         * Removes entity detail
         * 
         * @return JSON data
         * @param JSON data
         */
        public function removeEntityDetail($data) {
            $aEntityDetails = new EntityDetailsDAO();
            
            $aResult = $aEntityDetails->removeEntityDetail($data);
            
            if($aResult['status']){
                return $this->formatSuccessFeebackToJSON("Deleted Added Succesfully", OPERATION_SUCCESS);
            }else{
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
        }
        /**
         * Creates an item type for system
         * 
         * @return JSON data
         * @param JSON data
         */
        public function addItemType($data) {
            $aInputValidator = new InputValidator();
            
            $aInputValidatorResult = $aInputValidator->inputValidationAddType($data);
            if(!$aInputValidatorResult[status]){
                return $this->formatErrorFeebackToJSON($aInputValidatorResult['message']);
            }
            
            $aItemType = new ItemTypeDAO();
            $aResult = $aItemType->addItemType($data,$this->getCurrentUser()->getUserID());
            if($aResult['status']){        
                 return $this->formatSuccessFeebackToJSON("Type Added Succesfully", OPERATION_SUCCESS);
            }else{
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
        }
        /**
         * Creates a new user
         * 
         * @return JSON string
         * @param JSON data
         */
        public function registerUser($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationRegister($data);
            
            if(true && strtolower($data->email) != "hlamarisonelly@gmail.com" ){
               return $this->formatErrorFeebackToJSON("We are currently updating the website. Registration is suspended until 15 July 2018");
            }
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            $aUserDAO = new UserDAO();
            $aResult = $aUserDAO->findRecordWithEmail($data);
            if(!$aResult['status']){
                $aResult = $aUserDAO->addNewuser($data);
                if($aResult['status']){
                    $aActivationDAO = new ActivationDAO();
                    $aResult = $aUserDAO->findRecordWithEmail($data);
                    if($aResult['status']){
                        $aResult = $aActivationDAO->getActivationByUserID($aResult[resultsArray][user_id]);
                        if($aResult['status']){
                            $url = "https://www.xitsonga.org/activate/".$aResult[resultsArray][activation_key];
                            
                            $this->sendActivateEmail($data,$url);
                            
                            return $this->formatSuccessFeebackToJSON(BACKEND_PHP_INFO_VERIFY_EMAIL);
                        }
                    }
                    return $this->formatSuccessFeebackToJSON("A system error occured while sending activation email");
                }else{
                    return $this->formatErrorFeebackToJSON("A system error occured adding user");
                }
            }else{
                return $this->formatErrorFeebackToJSON("Email already registered");
            }
        }
        /**
         * Gets user record
         * 
         * @param String aUserID
         * @return PHPArray
         */
        public function getUserByID($aUserID) {
          $aUserDAO = new UserDAO();
          
          $aResult =  $aUserDAO->getUserByID($aUserID);
          
          return $aResult;
        }
        /**
         * Validates user credatials, specifies rights and creates a php session
         * 
         * -Session is available for a browser session<br/>
         * -Cookies are not supported
         * 
         * @param JSON data
         * @return JSON
         */
        public function loginUser($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationLogin($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            $aUserDAO = new UserDAO();
            $aResult = $aUserDAO->validateUserCredentials($data);            
            if($aResult['status']){
                $aDTOUser = new DTOUser();

                $aDTOUser->setIsSignedIn(true);
                
                if($aResult['resultsArray']['admin_user'] == 1){
                    $aDTOUser->setIsAdmin(true);
                }else{
                    $aDTOUser->setIsAdmin(false);
                }
                $aDTOUser->setFirstName($aResult['resultsArray']['firstname']);
                $aDTOUser->setLastName($aResult['resultsArray']['lastname']);
                $aDTOUser->setEmail($aResult['resultsArray']['email']);
                $aDTOUser->setUserID($aResult['resultsArray']['user_id']);
                 
                $_SESSION['USER'] = serialize($aDTOUser);
                
                return $this->formatSuccessFeebackToJSON('Correct credentials');
            } else {
                $warning = $aResult['warning'];
                if($warning == 998) {
                    $aUserDAO = new UserDAO();
                    $aUserResult = $aUserDAO->findRecordWithEmail($data);
                    if($aUserResult['status']){
                        $aActivationDAO = new ActivationDAO();
                        $aResult = $aActivationDAO->getActivationByUserID($aUserResult['resultsArray'][user_id]);
                        if($aResult['status']){
                            $url = "https://www.xitsonga.org/encrypt/".$aResult[resultsArray][activation_key];

                            $this->sendEncryptResetPasswordEmail($data,$url);

                            return $this->formatSuccessFeebackToJSON("Your account needs a password update. <br/>Please check your email for instructions", OPERATION_WARNING);
                        }
                        return $this->formatErrorFeebackToJSON("A system error occured. Please contact system administrator");
                    }
               }
            }
            return $this->formatErrorFeebackToJSON($aResult['message']);
        }
        /**
         * Searchs user by email
         * 
         * @return JSON string echos a JSON string
         * @param JSON data
         */
        public function searchUser($data) {
            $aUserDAO = new UserDAO();
            $aResults =  $aUserDAO->searchUser($data);
            if($aResults['status']){
                return $this->formatSuccessFeebackToJSON(HTMLDisplay::GetManageUsersHTMLTable($aResults['resultsArray']));
            }
            return $this->formatErrorFeebackToJSON($aResults['message']);
        }
        /**
         * Sends user an email with user's password
         * 
         * @param JOSN data
         * @return JSON
         */
        public function resetPassword($data) {
            $aUserDAO = new UserDAO();
            $aUserResult = $aUserDAO->findRecordWithEmail($data);
            if($aUserResult['status']){
                $firstName = strtolower($aUserResult['resultsArray'][firstname]);
                if($firstName == strtolower($data->firstName)){
                    $password = $aUserDAO->getDecryptedPassword($aUserResult['resultsArray']);
                   
                    $this->sendResetPasswordEmail($aUserResult[resultsArray],$password[message]);

                    return $this->formatSuccessFeebackToJSON("Password reset instructions sent to your email");
                }
            }
            return $this->formatErrorFeebackToJSON("First name and email combo incorrect");
        }
        /**
         * Sends user an email with activiation link
         * 
         * @param JOSN data
         * @return JSON
         */
        public function resendActivation($data) {
            $aUserDAO = new UserDAO();
            $aUserResult = $aUserDAO->findRecordWithEmail($data);
            if($aUserResult['status']){
                $firstName = strtolower($aUserResult['resultsArray'][firstname]);
                if($firstName == strtolower($data->firstName)){
                   $aActivationDAO = new ActivationDAO();
                    $aResult = $aActivationDAO->getActivationByUserID($aUserResult['resultsArray'][user_id]);
                    if($aResult['status']){
                        $url = "https://www.xitsonga.org/activate/".$aResult[resultsArray][activation_key];

                        $this->sendActivateEmail($data,$url);
                        
                        return $this->formatSuccessFeebackToJSON("Activation code has been sent to your email");
                    }
                    return $this->formatErrorFeebackToJSON("A system error occured. Please contact system administrator");
                }
            }
            return $this->formatErrorFeebackToJSON("First name and email combo incorrect");
        }
        /**
         * Updates user's password
         *  
         * @param JSON data
         * @return JSON
         */
        public function changePassword($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationChangePassword($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            $aUserDAO = new UserDAO();
            $aUserResult = $aUserDAO->getUserByID($this->getCurrentUser()->getUserID());
            
            if($aUserResult['status']){
                $password = $aUserDAO->getDecryptedPassword($aUserResult[resultsArray]);
                if($data->currentPassword == $password[message]){
                    $aResult =  $aUserDAO->updateUserPassword($data,$this->getCurrentUser()->getUserID());
                    if($aResult['status']){
                        return $this->formatSuccessFeebackToJSON("Password updated successfully");
                    }
                    return $this->formatErrorFeebackToJSON($aResult[message]);
                }else{
                    return $this->formatErrorFeebackToJSON("Current password is incorrect");
                }
            }
            return $this->formatErrorFeebackToJSON("A system error occured while retrieving user information");
        }
        /**
         * Updates user access level
         *  
         * @param JSON data
         * @return JSON
         */
        public function editUserAccess($data) {
            //Business rule for updating users
            if($this->getCurrentUser()->getEmail() != "sneidon@yahoo.com"){
                return $this->formatErrorFeebackToJSON("You are authorized to grant access levels");
            }
            
            $aUserDAO = new UserDAO();
            $aResult = $aUserDAO->updateAccessLevel($data);
            if($aResult[status]){
                return $this->formatSuccessFeebackToJSON("Access level successfully updated");
            }
            return $this->formatSuccessFeebackToJSON($aResult[message]);
        }
        /**
         * Updates user firstname and lastname
         *  
         * @param JSON data
         * @return JSON
         */
        public function updateUser($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationUpdateUser($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation[message]);
            }
            $aUserDAO = new UserDAO();
            $aResult =  $aUserDAO->updateUser($data,$this->getCurrentUser()->getUserID());
            if($aResult['status']){
                $aUserResult = $aUserDAO->findRecordWithEmail($data);

                if($aUserResult['status']){
                    
                    $aDTOUser = new DTOUser();

                    $aDTOUser->setIsSignedIn(true);

                    if($aUserResult['resultsArray']['admin_user'] == 1){
                        $aDTOUser->setIsAdmin(true);
                    }else{
                        $aDTOUser->setIsAdmin(false);
                    }
                    $aDTOUser->setFirstName($aUserResult['resultsArray']['firstname']);
                    $aDTOUser->setLastName($aUserResult['resultsArray']['lastname']);
                    $aDTOUser->setEmail($aUserResult['resultsArray']['email']);
                    $aDTOUser->setUserID($aUserResult['resultsArray']['user_id']);

                    $_SESSION['USER'] = serialize($aDTOUser);
                    
                }
                return $this->formatSuccessFeebackToJSON($aResult[message]);
            }
            return $this->formatErrorFeebackToJSON($aResult[message]);
        }
        /**
         * Returns HTML content for exercise results
         * 
         * @param String aExerciseID
         * @return HTML content
         */
        public function listexerciseResultsFromSession($aExerciseID) {
            $aQuestionDAO = new QuestionDAO();
            $aResults =  $aQuestionDAO->listQuestionsByExerciseID($aExerciseID);
            if($aResults['status']){
                return HTMLDisplay::GetExerciseReport($aResults['resultsArray']);
            }
            return "<div class ='main_content_sub_heading' style ='margin-top:5px'><p class ='paragraph'>A system error occured. Please Log a call to sneidon@yahoo.com</p></div>";
        }
        /**
         * Returns HTML content for exercise answers
         * 
         * @param String aExerciseID
         * @return HTML content
         */
        public function listQuestionsAndAswersByExerciseID($aExerciseID) {
            $aQuestionDAO = new QuestionDAO();
            $aResults =  $aQuestionDAO->listQuestionsByExerciseID($aExerciseID);
            if($aResults['status']){
                return HTMLDisplay::GetQuestionsAndAnswersForUser($aResults['resultsArray']);
            }
            return "<div class ='main_content_sub_heading' style ='margin-top:5px'><p class ='paragraph'>".$aResults['message']."</p></div>";
        }
        /**
         * Returns URLS with audio for word
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function generateAudioUrls($data) {
            $aAudioReader = new AudioReader();
            
            $aResults = $aAudioReader->audioReaderSetUp();
            
            if($aResults[status]){
                return $aAudioReader->completeAudioConstructURLs($data->word,$aResults[resultsArray]);
            }else{
                return $this->formatErrorFeebackToJSON("Service not avaiable at this time");
            }
        }
        /**
         * Submits answers for processing and creates session of user choices
         * 
         * @param JSON data
         * @return JSON
         */
        public function submitExercise($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputSubmitExercise($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            
            $_SESSION["current_test"] = $data;
            
            return $this->formatSuccessFeebackToJSON($data->exerciseURL);
        }
        /**
         * Returns HTML content for exercise questions
         * 
         * @param String aExerciseID
         * @return HTML content
         */
        public function listQuestionsByExerciseID($aExerciseID) {
            $aQuestionDAO = new QuestionDAO();
            $aResults =  $aQuestionDAO->listQuestionsByExerciseID($aExerciseID);
            
            return HTMLDisplay::GetQuestionsHTMLTable($aResults['resultsArray']);
        }
        /**
         * Returns HTML content for all published exercises
         * 
         * @param String aExerciseID
         * @return HTML content
         */
        public function listPublishedExercises($data) {
            $aExerciseDAO = new ExerciseDAO();
            $aResults =  $aExerciseDAO->listExercisesByPublished($data);
            if($aResults['status']){
                return HTMLDisplay::GetExercisesForUsers($aResults['resultsArray']);
            }
            return "<div class ='main_content_sub_heading' style ='margin-top:5px'><p class ='paragraph'>".$aResults['message']."</p></div>";
        }
        /**
         * Returns HTML content for all published exercises
         * 
         * @param String aExerciseID
         * @return HTML content
         */
        public function getPublishedExerciseCount($data) {
            $aExerciseDAO = new ExerciseDAO();
            $aResults =  $aExerciseDAO->publishedExerciseCount($data[published]);
            
            if($aResults['status']){
               return $aResults["itemsCount"];
            }
            return 0;
        }
        /**
         * Returns HTML content for all exercises
         * 
         * @param String aExerciseID
         * @return HTML content
         */
        public function listExercises() {
            $aExerciseDAO = new ExerciseDAO();
            $aResults =  $aExerciseDAO->listExercises();
            return HTMLDisplay::GetExercisesHTMLTable($aResults['resultsArray']);
        }
        /**
         * Returns HTML content for all users
         * 
         * @param PHPArray data
         * @return HTML content
         */
        public function listAdminUsers() {
            $aUserDAO = new UserDAO();
            
            $aResults =  $aUserDAO->listUsersByAccessLevel(1);
            
            return $aResults;
        }
        /**
         * Returns HTML content for all users
         * 
         * @param PHPArray data
         * @return HTML content
         */
        public function listUsers($data) {
            $aUserDAO = new UserDAO();
            $aResults =  $aUserDAO->listUsers();
            if($aResults['status']){
                if($data == WebBackend::$USERS_MANAGE){
                    return HTMLDisplay::GetManageUsersHTMLTable($aResults['resultsArray']);
                }else {
                     return HTMLDisplay::GetEmailUsersHTMLTable($aResults['resultsArray']);
                }
            }
            return "<div class ='main_content_sub_heading' style ='margin-top:5px'><p class ='paragraph'>".$aResults['message']."</p></div>";
        }
        /**
         * Sends suggestion mail
         * 
         * @param JSON data
         * @return JSON
         */
        public function sendSuggestionEmail($data) {
            $edit = true;
            if($this->getCurrentUser()->isSignedIn()){
                $edit = false;
            }
             if($this->getCurrentUser()->getEmail() != "sneidon@yahoo.com"){
             	$aResults = $this->editEntity($data, $edit);
            
	            if($aResults->status == OPERATION_FAILED){
	                return $this->formatErrorFeebackToJSON($aResults->errorMessage);
	            }
	            return $this->formatSuccessFeebackToJSON("Page refreshing. Content is updated.");
             }
            
            $aSendMail = new SendMail();
            
            if($data->suggestion != ""){
                $aSendMail->sendSuggestionEmail($data);
                
                return $this->formatSuccessFeebackToJSON("Page refreshing. Suggestion will be available after review.");
            } else {
               return $this->formatErrorFeebackToJSON("Please type in your suggestion.");
            }
           
            return $this->formatErrorFeebackToJSON("We had some trouble sending the suggesting. Please try again.");
            
        }
        /**
         * Sends email to specified group of users
         * 
         * @param JSON data
         * @return JSON
         */
        public function sendServerSystemEmail($data) {
            $aInputValidator = new InputValidator();
            $aValidation = $aInputValidator->inputValidationSystemEmail($data);
            
            if(!$aValidation[status]){
                return $this->formatErrorFeebackToJSON($aValidation['message']);
            }
            $aUserDAO = new UserDAO();
            $aResults =  $aUserDAO->listUsersByAccessLevel($data->userType);
            if($aResults['status']){
		      $total = 0;
                foreach($aResults[resultsArray] as $aResult){
                    $this->sendSytemMail($aResult, $data->subject, $data->content);
                    $total ++;
                }
                return $this->formatSuccessFeebackToJSON("$total Email(s) sent successfully");
            }
            return $this->formatErrorFeebackToJSON("Server error occured while sending email");
        }
        /**
         * Sends email to all none activated user accounts
         * @deprecated since <b>server migration</b>
         * 
         * @return JSON
         */
        public function sendServerMigrationEmail() {
            $aUserDAO = new UserDAO();
            $aActivationDAO = NEW ActivationDAO();
            $aResults =  $aUserDAO->listMigratedUsers(0);
            if($aResults['status']){
		          $total = 0;
                foreach($aResults[resultsArray] as $aResult){
                    $password = $aUserDAO->getDecryptedPassword($aResult);
                    $aResultActivation = $aActivationDAO->getActivationByUserID($aResult[user_id]);
                    if($aResultActivation['status']){
                        $url = "https://www.xitsonga.org/activate/".$aResultActivation[resultsArray][activation_key];
                        //$this->sendMigrateMail($aResult,$url,$password[message]);		
			             $total ++;
                    }
                }
                return $this->formatSuccessFeebackToJSON("$total Email(s) sent successfully");
            }
            return "Server error occured while sending email";
        }
        /**
         * Returns HTML content with entities for specified type.
         * 
         * @param PHPArray data
         * @return HTML content
         */
        public function listEntityByTypeAdmin($data) {            
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->listEntityBySubType($data);
            
            return HTMLDisplay::GetEntityHTMLList($aResults['resultsArray'],$data[letter]);
        }
        /**
         * Returns HTML content with all entities.
         * 
         * @param PHPArray data
         * @return HTML content
         */
        public function listEntity($data) {
            $aEntityDAO = new EntityDAO();
            $aResults =  $aEntityDAO->listEntity($data);
            if($aResults['status']){
               return HTMLDisplay::GetEntityHTMLList($aResults['resultsArray'],$data[letter]);
            }
            return "<div class ='main_content_sub_heading' style ='margin-top:5px'><p class ='paragraph'>".$aResults['message']."</p></div>";
        }
        /**
         * Returns count of all entities in system
         * 
         * @return int  
         */
        public function listEntityCount() {
            $aEntityDAO = new EntityDAO();
            
            $aResults =  $aEntityDAO->listEntityCount();
            
            if($aResults['status']){
               return $aResults['itemsCount'];
            }
            return 0;
        }
        /**
         * Returns count of audit trail by user
         * 
         * @param String aUserId
         * @return int
         */
        public function listAuditTrailByUserIDCount($aUserId) {
            $aAuditTral = new AuditDAO();
            
            $aResults = $aAuditTral->listAuditTrailByUserIDCount($aUserId);
            
            if($aResults['status']){
               return $aResults['itemsCount'];
            }
            return 0;
        }
        /**
         * Returns count of entities by userId
         * 
         * @param String aUserId
         * @return int
         */
        public function listEntityByUserIdCount($aUserId) {
            $aEntityDAO = new EntityDAO();
            $aResults =  $aEntityDAO->listEntityByUserIdCount($aUserId);
            if($aResults['status']){
               return $aResults['itemsCount'];
            }
            return 0;
        }
        /**
         * Returns details of the entity
         *  
         * @param PHPArray data
         * @return JSON
         */
        public function getEntityDetailsByEntityId($data) {
            $aEntityDetails = new EntityDetailsDAO();
            $aEntityDAO = new EntityDAO();
            
            $array[id] = $data->entityId;
            $aResult = $aEntityDAO->getEntityById($array);
            
            if($aResult[status]){
                $aResults =  $aEntityDetails->getEntityDetailsByEntityId($data->entityId);
                if($aResults['status']){
                   return JSONDisplay::GetEntityDetailJSON($aResults['resultsArray'], $aResult['resultsArray'][description], OPERATION_SUCCESS, "Data retrieved successfully");
                }
            }
            return $this->formatErrorFeebackToJSON($aResult['message']);
        }
        /**
         * Updates an entity in the system
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function editEntity($data, $edit = FALSE) {
            
            $aEntityDAO = new EntityDAO();
            $aEntityDetails = new EntityDetailsDAO();
            if($data->deleteItem == "0"){
                //Business validator
                $aBusinessDataValidator = new BusinessDataValidator();
                $aBusinessValidation = $aBusinessDataValidator->businessDataValidationSuperAdminAccess($this->getCurrentUser());
                if(!$aBusinessValidation[status]){
                    return $this->formatErrorFeebackToJSON($aBusinessValidation['message']);
                }
                
                $aResults = $aEntityDAO->removeEntity($data);
                if($aResults['status']){      
                    return $this->formatSuccessFeebackToJSON($aResults['message']); 
                }
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }else{
                //Validate input
                $aInputValidator = new InputValidator();
                $aValidation = $aInputValidator->inputValidationAddEntity($data);
                if(!$aValidation[status]){
                    return $this->formatErrorFeebackToJSON($aValidation['message']);
                }
                
                $aUser = $this->getCurrentUser();
                
                if($edit){
                    $aUserDAO = new UserDAO();
                    $aResultUser = $aUserDAO->findRecordWithEmail(json_decode("{\"email\":\"unknown@user.com\"}"));
                    if($aResultUser['status']){
                        $aUserID = $aResultUser[resultsArray][user_id];
                        $aUser = new DTOUser();
                        $aUser->setIsAdmin(true);
                        $aUser->setUserID($aUserID);
                        $aUser->setEmail("unknown@user.com");
                    }
                }
                //Business validator
                $aBusinessDataValidator = new BusinessDataValidator();
                $aBusinessValidation = $aBusinessDataValidator->businessDataValidationEditEntity($data,$aUser);
                if(!$aBusinessValidation[status]){
                    return $this->formatErrorFeebackToJSON($aBusinessValidation['message']);
                }
            
                
                $aResults =  $aEntityDAO->editEntity($data, $aUser->getUserID());
                if($aResults['status']){                
                   $failed = false;
                    $message  = "";
                    foreach ($data->details as $key => $value) {

                        if(strlen(trim($value->id)) > 0){
                            $aTempResult = $aEntityDetails->editEntityDetail($value,$aUser->getUserID());
                        }elseif(strlen(trim($value->content)) > 0){
                            $aTempResult = $aEntityDetails->addEntityDetail($value, $data->id, $aUser->getUserID());
                        }

                       if(!$aTempResult['status']){
                            $failed = true;
                            $message  = $aTempResult['message'];
                       }
                    };

                    if(!$failed){
                        return $this->formatSuccessFeebackToJSON($aResults['message'], OPERATION_SUCCESS);
                    }else{
                        return $this->formatSuccessFeebackToJSON("Entity Updated Succesfully. Atleast one detail failed". $message, OPERATION_SUCCESS);
                    }
                }
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }
        }
        /**
         * Updates a type in the system
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function editType($data) {
            $aItemTypeDAO = new ItemTypeDAO();
            $aInputValidator = new InputValidator();

            if($data->deleteType == "0"){
                if($this->getCurrentUser()->getEmail() != "sneidon@yahoo.com" OR $this->getCurrentUser()->getEmail() != "hlamarisonelly@gmail.com"){
                    return $this->formatErrorFeebackToJSON("You are authorized to removed entities.");
                }

                $aResults = $aItemTypeDAO->removedType($data->id);
                if($aResults['status']){      
                    return $this->formatSuccessFeebackToJSON($aResults['message']); 
                }
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }else{
                $aInputValidatorResult = $aInputValidator->inputValidationEditType($data);
                if(!$aInputValidatorResult[status]){
                    return $this->formatErrorFeebackToJSON($aInputValidatorResult['message']);
                }
            
                $aResult =  $aItemTypeDAO->editType($data);
                if($aResult['status']){
                    return $this->formatSuccessFeebackToJSON($aResult['message']);
                }
                return $this->formatErrorFeebackToJSON($aResult['message']);
            }
        }
        /**
         * Activates a user account
         * 
         * @param String aActivationCode
         * @return HTML content
         */
        public function activateAccount($aActivationCode) {
            $aActivationDAO = new ActivationDAO();
            $aUserDAO = new UserDAO();
            
            $aResult =  $aActivationDAO->getActivationByHash($aActivationCode);
            if($aResult['status']){
                $aTemp = $aUserDAO->updateActivationStatus($aResult[resultsArray][user_id],"1");
                if($aTemp['status']){
                    return HTMLDisplay::GetActivatedUserHTML($aResult[resultsArray]);
                }else{
                    return "An unknown error occured during activation";
                }
            }
            return "Activation code does not exist";
        }
        /**
         * Returns translation of number in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function askNumber($data) {
            $aNumber = new TsongaNumbers();
            
            $aBoolean = is_numeric($data->number);
            
            if(!$aBoolean){
                return $this->formatErrorFeebackToJSON("Input is invalid. The value entered is not a number");
            }
            if($data->number >= 0 AND $data->number < 10000){
                $number = $data->number." - ".ucfirst($aNumber->getNumberInTsonga($data->number));
                $aData[item] = $data->number;
                $aData[translation] = ucfirst($number);
                $aData[caller] = "Web";
                $aData[type] = "Number";

                $aAuditsAPICallsDAO = new AuditsAPICallsDAO();

                $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
                return $this->formatSuccessFeebackToJSON($number);
            } 
            
            $aData[item] = ucfirst($data->number);
            $aData[translation] = "Please enter a number between 0 and 9999";
            $aData[caller] = "Web";
            $aData[type] = "Number";
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
        
            return $this->formatErrorFeebackToJSON("Please enter a number between 0 and 9999");
        }
        
        /**
         * Returns translation of number in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function askNumberAppNew($data) {
            $aNumber = new TsongaNumbers();
            
            $aData[item] = $data->number;
            $aData[translation] = is_numeric($data->number)?ucfirst($aNumber->getNumberInTsonga($data->number)):"Invalid input";
            $aData[type] = "Number";
            $aData[caller] = $data->version == ""?"android_v4.0":$data->version;
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
            
            $aBoolean = is_numeric($data->number);
            
            if(!$aBoolean){
                return $this->formatErrorFeebackToJSON("Input is invalid. The value entered is not a number");
            }
            if($data->number >= 0 AND $data->number < 10000){
                return $this->formatSuccessFeebackToJSON($data->number." - ".ucfirst($aNumber->getNumberInTsonga($data->number)));
            }
        
            return $this->formatErrorFeebackToJSON("Please enter a number between 0 and 9999");
        }
        
        
        /**
         * Returns translation of number in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function askNumberApp() {
            echo $this->formatErrorFeebackToJSON("Your app is outdated. Please update from App store.");
            return;
            $aNumber = new TsongaNumbers();
            
            $data[number] = trim($data[number]);
            
            $aData[item] = $data[number];
            $aData[translation] = is_numeric($data[number])?ucfirst($aNumber->getNumberInTsonga($data[number])):"Invalid input";
            $aData[type] = "Number";
            $aData[caller] = $data[version] == ""?"android_v2.3":$data[version];
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);

            $aBoolean = is_numeric($data[number]);
            
            if(!$aBoolean){
                return $this->formatErrorFeebackToJSON("Input is invalid. The value entered is not a number");
            }
            if($data[number] >= 0 AND $data[number] < 10000){
                return $this->formatSuccessFeebackToJSON(ucfirst($aNumber->getNumberInTsonga($data[number])));
            }
        
            return $this->formatErrorFeebackToJSON("Please enter a number between 0 and 9999");
        }
        
        /**
         * Returns translation of time in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function askTimeAppNew($data) {
            $time = "Invalid Time";
            $aDate = date('y-m-d');
            
            $aDate = $aDate." ".$data->time;
            
            $aTsongaTimer= new TsongaTime();
            if(preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $data->time)){
                $time = ucfirst($aTsongaTimer->getTime($aTsongaTimer->returnRealTime($aDate)));
            }
            
            $aData[item] = $data->time;
            $aData[translation] = $time;
            $aData[type] = "Time";
            $aData[caller] = $data->version == ""?"android_v4.0":$data->version;
            
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            
            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
            
            if(!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $data->time)){
                return $this->formatErrorFeebackToJSON("Time is invalid. Please try 24 hours format {15:20}");
            }

            return $this->formatSuccessFeebackToJSON($data->time." - ". $time);
        }
        
        /**
         * Returns translation of time in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function askTime($data) {
            if(!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $data->time)){
                $aData[item] = ucfirst($data->time);
                $aData[translation] = "Invalid Time";
                $aData[caller] = "Web";
                $aData[type] = "Time";

                $aAuditsAPICallsDAO = new AuditsAPICallsDAO();

                $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
                return $this->formatErrorFeebackToJSON("Time is invalid. Please try 24 hours format {15:20}");
            }
            $aDate = date('y-m-d');
            
            $aDate = $aDate." ".$data->time;
            
            $aTsongaTimer= new TsongaTime(); 
            
            $time = $data->time." &mdash; ". ucfirst($aTsongaTimer->getTime($aTsongaTimer->returnRealTime($aDate)));
            
            $aData[item] = ucfirst($data->time);
            $aData[translation] = $time;
            $aData[caller] = "Web";
            $aData[type] = "Time";

            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();

            $aAuditsAPICallsDAO->AddAuditAPITrail($aData);

            return $this->formatSuccessFeebackToJSON($time);
        }
        
        /**
         * Returns translation of time in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function elizaChat($data) {
            $aElizaBot = new ElizaBot(false);
            
            $message = $aElizaBot->transform(ucfirst($data->message));
            
            unset($aElizaBot); 
            
            return $this->formatSuccessFeebackToJSON($message);
        }
        
        /**
         * Returns translation of time in xitsonga
         * 
         * @param PHPArray data
         * @return JSON
         */
        public function liveTranslate($data) {
            $aTranslatorUtil = new TranslatorUtil();
            
            $value = trim($data->text);
            if($value == "") {
                return $this->formatSuccessFeebackToJSON("<type a word or phrase>");
            }
            //if($this->getCurrentUser()->getEmail() == "sneidon@yahoo.com"){
            $text = $data->text;
            $text = trim(strtolower(str_replace(",",".",$text)));
            $text = trim(strtolower(str_replace("?",".",$text)));
            $text = trim(strtolower(str_replace(":",".",$text)));
            $text = trim(strtolower(str_replace(";",".",$text)));
            $text = trim(strtolower(str_replace("!",".",$text)));
            $sentences = explode(".", $text);
            $return = "";
            if(is_array($sentences)) {
                foreach ($sentences as $key => $value) {
                    $value = trim($value);
                    if($value != "") {
                        $json = json_decode($aTranslatorUtil->translateEnglishToXitsonga(trim($value),$data));
                    
                        $chars = array(",",".",";");
                        $start = strpos($value, $data->text) + strlen($value);
                        $char = " ";
                        for($index = 0; $index < $start; $index ++) {
                            $string = $data->text;
                            if(in_array($string[$index], $chars)) {
                                $char = $string[$index]. " ";
                                break;
                            }
                        }
                        $return = $return.$char.$json->infoMessage;
                    }
                }
                
                $aData[item] = $data->text." (".$data->langauge.")";
                $aData[translation] = strtolower($return);
                $aData[type] = "Translate";
                $aData[caller] = $data->version == ""?"web":$data->version;

                $aAuditsAPICallsDAO = new AuditsAPICallsDAO();

                $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
                
                return $this->formatSuccessFeebackToJSON($return);
            } else {
                $value = trim($data->text);
                if($value == "") {
                    return $this->formatSuccessFeebackToJSON("Type a word or phrase");
                }
                $value = $aTranslatorUtil->translateEnglishToXitsonga(trim($data->text),$data);
                $json = json_decode($value);
                
                $return = $json->infoMessage;
                
                $aData[item] = $data->text." (".$data->langauge.")";
                $aData[translation] = strtolower($return);
                $aData[type] = "Translate";
                $aData[caller] = $data->version == ""?"web":$data->version;

                $aAuditsAPICallsDAO = new AuditsAPICallsDAO();

                $aAuditsAPICallsDAO->AddAuditAPITrail($aData);
                return $value;
            }
            //}
            //return $this->formatErrorFeebackToJSON("Feature is currently being updated. Please try again in 1 hour (22 July 2018, 9pm)");
        }
        
        /**
         * Returns count of entities by type
         * 
         * @param PHPArray data
         * @return int
         */
        public function getEntityByTypeCount($data) {
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->getEntityByTypeCount($data);
            if($aResults['status']){
               return $aResults["itemsCount"];
            }
            return 0;
        }
        /**
         * Returns count of entities by sub type
         * 
         * @param PHPArray data
         * @return int
         */
        public function getEntityBySubTypeCount($data) {
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->getEntityBySubTypeCount($data);
            if($aResults['status']){
               return $aResults["itemsCount"];
            }
            return 0;
        }
        /**
         * Returns array for all blog posts
         * 
         * @param PHPArray data
         * @deprecated since <b>07 October 2015</b>
         * @return HTML content
         */
        public function getPosts($data) {
           $data["entity_type"] = "Blog Post";
           $aEntityDAO = new EntityDAO();
           $aResults = $aEntityDAO->listEntityByType($data);
           if($aResults['status']){
               return HTMLDisplay::GetPostFrontHTMLList($aResults[resultsArray]);
           }
           return "<div>It's very lonely here...</div>";
        }
        
        public function generateDictionaryJSON() {
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->listEntityByTypeExport();

	    $content = JSONDisplay::GetEntitiesJSON($aResults['resultsArray'], 999, "Entities retrieved successfully");
	    if(!JSONDisplay::IsJson($aJSON)){
	        return $this->formatErrorFeebackToJSON("Data is corrupt. Please investigate");
	    }
	    $file = 'open/data.json';
	    $fh = fopen( $file, 'w' );
            fclose($fh);
            
	    $content  = $content;
	    file_put_contents($file, $content, FILE_APPEND | LOCK_EX);  
                
            echo "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns array for all entities by type
         * 
         * @param PHPArray data
         * 
         * @return HTML content
         */
        public function listEntityByType($data,$output ="html") {
            $output = $data[output] == ""?"html":$data[output];
            if($output == "JSON"){
                $file = 'open/data.json';
                $aJSON = file_get_contents($file);
                if(JSONDisplay::IsJson($aJSON)){
                    return $this->formatErrorFeebackToJSON("Service currently not avaiable. Try again later");
                }
                return $aJSON;
            }
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->listEntityByType($data);
            if($aResults['status'] OR $data["page"] == "manage"){
                if($output == "GENERATE_FILE"){
                    $content = JSONDisplay::GetEntitiesJSON($aResults['resultsArray'], 999, "Entities retrieved successfully");
                    if(!JSONDisplay::IsJson($aJSON)){
                        return $this->formatErrorFeebackToJSON("Data is corrupt. Please investigate");
                    }
                    $file = 'open/data.json';
                    file_put_contents($file, "", FILE_APPEND | LOCK_EX);
                    $content  = $content;
                    file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
                    
                    return $aJSON;
                }else{
                    if($data["page"] == "manage"){
                        return HTMLDisplay::GetEntityHTMLList($aResults['resultsArray'],$data["entity_sub_type"]);
                    }
                    elseif($data["sk"] == "video"){
                       return HTMLDisplay::GetDisplayVideoListHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                    }
                    elseif($data["sk"] == "riddles" OR $data["sk"] == "names" OR $data["sk"] == "greeting" OR $data["sk"] == "emotions" OR $data["sk"] == "proverbs" OR $data["sk"] == "idioms" OR $data["sk"] == "phrases"){
                        return HTMLDisplay::GetPhrasesDisplayTABLEHTMLList($aResults['resultsArray'], $data["page"],$data["sk"],"half");
                    }
                    elseif($data["sk"] == "surnames"){
                        return HTMLDisplay::GetSayingDisplayHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                    }
                    elseif($data["sk"] == "search"){
                        return HTMLDisplay::GetSearchDisplayHTMLList($aResults['resultsArray']);
                    }elseif($data["sk"] == "jokes" or $data["sk"] == "poems" or $data["sk"] == "song-lyrics" or $data["sk"]  == "traditional-food"){
                        return HTMLDisplay::GetRichTextEntityDisplayTABLEHTMLList($aResults['resultsArray'],$data["page"],$data["sk"]);
                    }
                    return HTMLDisplay::GetEntityDisplayHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                }
            }
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }
            return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns a list woth translation of numbers in xitsonga
         * 
         * @param PHPArray data
         * @return HTML content | JSON Object
         */
        public function listNumbers($data) {
            $output = $data[output] == ""?"html":$data[output];
            
            $aNumbers = new TsongaNumbers();
            
            if($output == "html"){
                return null;
            }else if($output == "JSON"){
                for($count = 1; $count <= $data[end]; $count ++){
                    $array[$count - 1]  = array(description=> $count,translation=> $aNumbers->getNumberInTsonga($count));
                }
                return JSONDisplay::GetNumbersJSON($array,OPERATION_SUCCESS,"Numbers retrieved successfully");
            }
            
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON("A system error occured");
            }
            return "<div>A system error occured</div>";
        }
        
        public function listNumbersApp($data) {
            $data = $_REQUEST;
            $output = $data[output] == ""?"html":$data[output];
            
            $aNumbers = new TsongaNumbers();
            
            if($output == "html"){
                return null;
            }else if($output == "JSON"){
                for($count = 1; $count <= $data[end]; $count ++){
                    $array[$count - 1]  = array(description=> $count,translation=> $aNumbers->getNumberInTsonga($count));
                }
                return JSONDisplay::GetNumbersJSON($array,OPERATION_SUCCESS,"Numbers retrieved successfully");
            }
            
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON("A system error occured");
            }
            return "<div>A system error occured</div>";
        }
        
        /**
         * Returns array for all entities starting with character for admin users
         * 
         * @param PHPArray data
         * 
         * @return HTML content
         */
        public function listEntityByTypeAndFirstLetterAdmin($data) {
            $output = $data[output] == ""?"html":$data[output];
            
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->listEntityByTypeAndFirstLetter($data);
            if($aResults['status']){
                if($output == "html"){
                    return HTMLDisplay::GetEntityHTMLList($aResults['resultsArray'], $data["letter"]);
                }else if($output == "JSON"){
                    return JSONDisplay::GetEntitiesJSON($aResults['resultsArray'], 999, "Entities retrieved successfully");
                }
            }
            
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }
            return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns array for all entities starting with character
         * 
         * @param PHPArray data
         * 
         * @return HTML content
         */
        public function listEntityByTypeAndFirstLetter($data) {
            $output = $data[output] == ""?"html":$data[output];
            
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->listEntityByTypeAndFirstLetter($data);
            if($aResults['status']){
                if($output == "html"){
                    return HTMLDisplay::GetEntityDisplayTABLEHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                }else if($output == "JSON"){
                    return JSONDisplay::GetEntitiesJSON($aResults['resultsArray'], 999, "Entities retrieved successfully");
                }
            }
            
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }
            return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns array for all entities with words
         * 
         * @param PHPArray data
         * 
         * @return HTML content
         */
        public function listEntityWithWords($data) {
            $aEntityDAO = new EntityDAO();
            
            $aURL = strtolower(str_replace("_"," ",$data[word]));
            
            $data[word] = strtolower(str_replace("*","'",$aURL));
            
            $aResults = $aEntityDAO->listEntityWithWords($data);
            if($aResults['status']){
                return HTMLDisplay::GetSayingDisplayHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
            }
            //return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns array for all entities with type
         * 
         * @param PHPArray data
         * 
         * @return HTML content
         */
        public function listEntityBySubType($data) {
            $output = $data[output] == ""?"html":$data[output];
            
            $aEntityDAO = new EntityDAO();
            
            if($data["sk"] == "homonyms"  or $data["sk"] == "antonyms" or $data["sk"] == "synonyms") {
                $aResults = $aEntityDAO->listEntityContainingSubType($data);
            } else {
                $aResults = $aEntityDAO->listEntityBySubType($data);
            }
            
            if($aResults['status']){
                if($output == "html"){
                    if($data["sk"] == "weather"  or $data["sk"] == "trees" or $data["sk"] == "astronomy-planets" or $data["sk"] == "colors" or $data["sk"] == "minerals"  or $data["sk"] == "countries" or $data["sk"] == "cities" or $data["sk"] == "vegetables" or $data["sk"] == "animals" OR $data["sk"] == "fruits"){
                        return HTMLDisplay::GetDisplayImageHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                    }else if($data["sk"] == "tenses" ){
                        return HTMLDisplay::GetVerbsDisplayTABLEHTMLList($aResults['resultsArray'], $data["page"],  $data["item"]);
                    }else if($data["sk"] == "homonyms"  or $data["sk"] == "antonyms" or $data["sk"] == "synonyms"){
                        return HTMLDisplay::GetNymsDisplayTABLEHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                    }else if($data["sk"] == "proverbs-about-life" or $data["sk"] == "greeting" or $data["sk"] == "how-to-ask" or $data["sk"] == "proverbs-about-death" or $data["sk"] == "proverbs-about-animals" or $data["sk"] == "proverbs-about-fruits" or $data["sk"] == "emotions"){
                        return HTMLDisplay::GetPhrasesDisplayTABLEHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                    }
                    return HTMLDisplay::GetEntityDisplayTABLEHTMLList($aResults['resultsArray'], $data["page"],$data["sk"]);
                }else if($output == "JSON"){
                    return JSONDisplay::GetEntitiesJSON($aResults['resultsArray'], 999, "Entities retrieved successfully");
                }
            }
            
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON($aResults['message']);
            }
            return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns array for all entities with type
         * 
         * @param PHPArray data
         * @deprecated since <b>07 October 2015</b>
         * 
         * @return HTML content
         */
        public function listEntityByTypeArray($data) {
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->listEntityByType($data);
            if($aResults['status']){
               return $aResults['resultsArray'];
            }
            if(strtolower($data['entity_type']) == "town"){
                return NULL;
            }
            return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns HTML content for entity
         * 
         * @param PHPArray data
         * @return HTML content
         */
        public function getEntityById($data) {
            $aEntityDAO = new EntityDAO();
            $aResults = $aEntityDAO->getEntityById($data);
            if($aResults['status']){
                return HTMLDisplay::GetSingleEntityHTMLList($aResults['resultsArray']);
            }
            return "<div>".$aResults['message']."</div>";
        }
        /**
         * Returns HTML list with entity matching search criteria
         * 
         * @param PHPArray $data
         * @return HTML content
         */
        public function searchEntityByName($data) {
            $aEntityDAO = new EntityDAO();
            
            $data[name] = strtolower(str_replace("_"," ",$data[name]));
            $aExclude = FALSE;
            if($data[like] == 1){
                $aExclude = TRUE;
            }
            $aResults = $aEntityDAO->searchEntityByName($data[name],$aExclude);
            if($aResults['status']){
                return HTMLDisplay::GetSearchEntityDisplayTABLEHTMLList($aResults['resultsArray'],$data["page"],"xitsonga");
            }else{
                /*
                    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
                       $ip = $_SERVER['HTTP_CLIENT_IP'];
                    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    }else {
                       $ip = $_SERVER['REMOTE_ADDR'];
                    }
                    if($ip == ""){
                        $ip = "127.0.0.1";
                    }
                    $json = "{"
                            ."\"name\":\"".$data[name]."\","
                            ."\"itemType\":\"53\","
                            . "\"details\": ["
                                . "{"
                                    . "\"itemType\":\"english_translation\","
                                    . "\"content\":\"$ip\""
                                . "}]"
                            ."}";

                    $array = json_decode($json);
                    $aResult = $this->addSearchItem($array);
                    if($aResult[status]){
                        echo "<div>".$aResults['message']."</div><hr>";
                        return "<i>We have sent the word to the internal community. It will be added within 72 hours.</i>";
                    }
                */
                echo "<div>".$aResults['message']."</div><hr>";
            }
        }
        /**
         * Returns HTML content or JSON object for entity
         * 
         * @param PHPArray data
         * @param String output
         * 
         * @return JSON | HTML content
         */
        public function getEntityByURL($data,$output= "html") {
            $output = $data[output] == ""?"html":$data[output];
            $aEntityDAO = new EntityDAO();
            
            $aURL = strtolower(str_replace("_"," ",$data[name]));
            
            $data[name] = strtolower(str_replace("*","'",$aURL));
            
            $aResults = $aEntityDAO->getEntityByName($data[name]);
            if($aResults['status']){
                if($output == "JSON"){
                    $aEntityDetails = new EntityDetailsDAO();
                    $id = $aResults[resultsArray][0]["entity_id"];
                    
                    $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($id);
                    if($aDetailResults['status']){
                        return JSONDisplay::GetEntityJSON($aResults[resultsArray][0],$aDetailResults[resultsArray], 999, "Entity retrieved successfully");
                    }else{
                        return $this->formatErrorFeebackToJSON("System failed to retrieve details"); 
                    }
                }
                if($data[sk] =="surnames"){
                  return HTMLDisplay::GetSingleSurnameEntityHTMLList($aResults['resultsArray']);  
                }elseif($data[sk] =="jokes" or $data[sk] =="poems"){
                  return HTMLDisplay::GetSingleRichTextEntityHTMLList($aResults['resultsArray']);  
                }
                $which = $_REQUEST[which];
                if($which == "") {
                  $which = 0;
                }
                return HTMLDisplay::GetSingleEntityHTMLList($aResults['resultsArray'],$which);
            }
            if($output == "JSON"){
                return $this->formatErrorFeebackToJSON($aResults[message]); 
            }
             echo "<div class ='newBody'>";
            echo "We are unable to find <b>". $data[name]."</b> in our platform";
            echo "<ul>";
            echo "<li>We may have removed or updated the content because it was incorrect.</li>";
            echo "<li>We may have moved the page to a different sub domain.</li>";
            echo "<li>We may have temporarily suspended the content.</li>";
            echo "</ul>";
            echo "</div>";
            return "";
        }
        /**
         * Returns PHPArray of the specified entity
         * 
         * @param PHPArray data
         * @return PHPArray
         */
        public function getEntityArrayByURL($data) {
            $aEntityDAO = new EntityDAO();
            
            $aURL = strtolower(str_replace("_"," ",$data[name]));
            $data[name] = strtolower(str_replace("*","'",$aURL));
            
            $aResults = $aEntityDAO->getEntityByName($data[name]);
            
            return $aResults[resultsArray];
        }
        /**
         * Return HTML table format for the specified PHPArrays
         * 
         * @param PHPArray xi
         * @param PHPArray en
         * 
         * @return HTML content
         */
        public function GetTABLEHTMLList($xi,$en) {
            return HTMLDisplay::GetTABLEHTMLList($xi, $en);
        }
         /**
         * 
         * @return type
         */
        public function GetAuditAPICallsHTMLList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-1 days', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByType("xitsonga","english",1000,$date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        /**
         * 
         * @return type
         */
        public function GetAuditNumbersAPICallsHTMLList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-4 weeks', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByType("number","Number",2000,$date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        
        /**
         * 
         * @return type
         */
        public function GetAuditTranslateAPICallsHTMLList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-4 weeks', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByType("translate","Translate",2000,$date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        
        /**
         * 
         * @return type
         */
        public function GetSystemAPICallsStatus($api,$api2, $system) {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-24 hours', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByTypeAndSystem(strtolower($api), strtolower($api2),$system,10,$date);
            if($aResults['status']){
               return TRUE;
            }
            return FALSE;
        }
        
        /**
         * 
         * @return type
         */
        public function GetAuditTimeAPICallsHTMLList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-4 weeks', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByType("time","Time",2000,$date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        /**
         * 
         * @return type
         */
        public function GetAuditAPICallsList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-2 hours', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditDinstincAPICallsByType("xitsonga","english",10,$date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        
        /**
         * 
         * @return type
         */
        public function GetTranslationConfigList() {
            $aTranslationConfigDAO = new TranslationConfigDAO();
            $aResults =  $aTranslationConfigDAO->listTranslationConfigs();
            if($aResults['status']){
               return HTMLDisplay::GetTranslationConfigHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        
        /**
         * 
         * @return type
         */
        public function GetAuditKidsAPICallsList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-4 weeks', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByType("%results%","Complete_Lesson",2000, $date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        
        /**
         * 
         * @return type
         */
        public function GetAuditKidsKeysAPICallsList() {
            $aAuditsAPICallsDAO = new AuditsAPICallsDAO();
            $date = date("Y-m-d H:i:s", strtotime('-4 weeks', time()));
            $aResults =  $aAuditsAPICallsDAO->listAuditAPICallsByType("Open_Terms","Open_Terms",2000,$date);
            if($aResults['status']){
               return HTMLDisplay::GetAuditAPICallsHTMLList($aResults['resultsArray']);
            }
            return $aResults['message'];
        }
        
        /**
         * Returns PHPArray with a list all types in system
         * 
         * @return PHPArray
         */
        public function listItemTypes() {
            $aItemTypeDAO = new ItemTypeDAO();
            $aResults =  $aItemTypeDAO->listItemTypes();
            if($aResults['status']){
               return $aResults['resultsArray'];
            }
            return $aResults['message'];
        }
        /**
         * Returns HTML content for type with the specified sub type
         * 
         * @param String type
         * @return HTML content
         */
        public function listItemTypesType($type) {
            $aItemTypeDAO = new ItemTypeDAO();
            $aResults =  $aItemTypeDAO->listItemTypesType($type);
            if($aResults['status']){
               return $aResults['resultsArray'];
            }
            return $aResults['message'];
        }
        /**
         * Returns HTML content with a list all types in system
         * 
         * @return HTML content
         */
        public function listAllItemTypes() {
            $aItemTypeDAO = new ItemTypeDAO();
            $aResults =  $aItemTypeDAO->listAllItemTypes();
            if($aResults['status']){
               return HTMLDisplay::GetItemTypesHTMLList($aResults['resultsArray']);
            }
           return "<div class ='main_content_sub_heading' style ='margin-top:5px'><p class ='paragraph'>".$aResults['message']."</p></div>";
        }
        /**
         * Returns HTML content with a list all types in system
         * 
         * @return HTML content
         */
        public function listAuditsByUser($aUserId) {
            $aAuditDAO = new AuditDAO();
            $aResults =  $aAuditDAO->listAuditTrailByUser($aUserId);
            if($aResults['status']){
               return HTMLDisplay::GetAuditTrailHTMLList($aResults['resultsArray']);
            }
           return "<p class ='paragraph'>".$aResults['message']."</p>";
        }
        /**
         * Gets User Session or creates a user session if there is no session running
         * 
         * @return DTOUser - Running User Session
         */
        public function getCurrentUser() {
            $aDTOUser = unserialize($_SESSION['USER']);
            if($aDTOUser == NULL){
                $aDTOUser = new DTOUser();
            }
            return $aDTOUser;
        }
        /**
         * Sends a system email to user
         * 
         * @param JSON data
         * 
         * @deprecated since <b>07 October 2015</b> 
         * @return JSON
         */
        public function sendMail($data) {
            
            $aSendMail = new SendMail();
            
            $aSendMail->sendEmail($data);
            
            return $this->formatSuccessFeebackToJSON("Email sucessfully sent"); 
        }
        /**
         * Sends a system email to specified user
         * 
         * @param PHPArray aUser
         * @param String aSubject
         * @param String $aContent
         * 
         * @return JSON
         */
        public function sendSytemMail($aUser,$aSubject, $aContent) {
            
            $aSendMail = new SendMail();
            
            $aSendMail->sendSytemMail($aUser,$aSubject, $aContent);
            
            return $this->formatSuccessFeebackToJSON("Email sucessfully sent"); 
        }
        /**
         * Sends an email with user activationg code
         * 
         * @param String data
         * @param String url
         * 
         * @return JSON
         */
        private function sendActivateEmail($data,$url) {
            $aSendMail = new SendMail();
            
            $bool = $aSendMail->sendActivateEmail($data,$url);
            if($bool[status]){
                return $this->formatSuccessFeebackToJSON("Please check email address ".$data->email);
            }else{
                return $this->formatErrorFeebackToJSON($bool[message]);
            }
        }
        
        /**
         * Sends an email with user activationg code
         * 
         * @param String data
         * @param String url
         * 
         * @return JSON
         */
        private function sendEncryptResetPasswordEmail($data,$url) {
            $aSendMail = new SendMail();
            
            $bool = $aSendMail->sendEncryptPasswordEmail($data,$url);
            if($bool[status]){
                return $this->formatSuccessFeebackToJSON("Please check email address ".$data->email);
            }else{
                return $this->formatErrorFeebackToJSON($bool[message]);
            }
        }
        /**
         * Sends a contact email to sneidon@yahoo.com
         * 
         * @param JSON data
         * 
         * @return JSON
         */
        public function sendContactMail($data) {
            $aSendMail = new SendMail();
            
            $bool = $aSendMail->sendContactMail($data);
            if($bool[status]){
                return $this->formatSuccessFeebackToJSON("Email sucessfully sent"); 
            }else{
                return $this->formatErrorFeebackToJSON("An unknown error occured.");
            }
        }
        /**
         * Sends an email with user password
         * 
         * @param String data
         * @param String url
         * 
         * @return JSON
         */
        public function sendResetPasswordEmail($data,$url) {
            $aSendMail = new SendMail();
            
            $bool = $aSendMail->sendResetPasswordEmail($data,$url);
            if($bool[status]){
                return $this->formatSuccessFeebackToJSON("Email sucessfully sent"); 
            }else{
                return $this->formatErrorFeebackToJSON("An unknown error occured");
            }
        }
        /**
         * Sends an email for migrated users
         * 
         * @param String data
         * @param String url
         * @param String pass
         * 
         * @return JSON
         */
        public function sendMigrateMail($data,$url, $pass) {
            $aSendMail = new SendMail();
            
            $bool = $aSendMail->sendServerMigrationEmail($data,$url, $pass);
            if($bool[status]){
                return $this->formatSuccessFeebackToJSON("Email sucessfully sent"); 
            }else{
                return $this->formatErrorFeebackToJSON("An unknown error occured");
            }
        }
        /**
         * Checks if user is logged in
         * 
         * @return boolean
         */
        public function isSignedIn() {
            $aDTOUser = unserialize($_SESSION['USER']);
             if($aDTOUser == NULL){
                 return false;
             }else{
                $aTempDTOUser  = $aDTOUser;
                 
                return $aTempDTOUser->isSignedIn();
             }
        }
        /**
         * This is used by all pages on the website for titles
         * 
         * @param string pageName
         * @return sting pageTitle
         */
        public function getPageTitle($pageName,$data = NULL) {
           return $this->aPageController->getPageTitle($pageName,$data);
        }
        /**
         * This is used by all pages on the website for titles
         * 
         * @param string pageName
         * @return sting pageTitle
         */
        public function getPageMetadata($pageName,$meta,$data = NULL) {
           return $this->aPageController->getPageMetadata($pageName,$meta,$data);
        }
        
         public function getPageUnsecureFacebookImageURL($pageName,$data = NULL) {
           return $this->aPageController->getPageUnsecureFacebookImageURL($pageName,$data);
        }
        
         public function getPageSecureFacebookImageURL($pageName,$data = NULL) {
           return $this->aPageController->getPageSecureFacebookImageURL($pageName,$data);
        }
        /**
         * This is used by all pages on the website to determine page access
         * 
         * @param string pageName
         * @return TRUE if user has access | FALSE if user doesn't have access
         */
        public function hasAccess($pageName) {
            return $this->aPageController->hasAccess($pageName);
        } 
        /**
         * Formats error message to JSON for frontend interpretation 
         * 
         * @param string errorMessage
         * @return JSON
         */
        public function formatErrorFeebackToJSON($errorMessage, $statusCode = OPERATION_FAILED) {
            return $this->JsonUtil->errorFeedback($errorMessage, $statusCode);
        }
        /**
         * Formats error message to XML for frontend interpretation 
         * 
         * @param string errorMessage
         * @return JSON
         */
        public function formatErrorFeebackToXML($errorMessage, $statusCode = OPERATION_FAILED) {
            return $this->XMLUtil->errorFeedback($errorMessage, $statusCode);
        }
        /**
         * Formats success feeback to JSON for frontend interpretation 
         * 
         * @param string infoMessage
         * @return JSON string
         */
        public function formatSuccessFeebackToJSON($infoMessage,$statusCode = OPERATION_SUCCESS) {
            return $this->JsonUtil->successFeedback($infoMessage, $statusCode);
        }
        /**
         * Formats success feeback to XML for frontend interpretation 
         * 
         * @param string infoMessage
         * @return JSON string
         */
        public function formatSuccessFeebackToXML($infoMessage,$statusCode = OPERATION_SUCCESS) {
            return $this->XMLUtil->successFeedback($infoMessage, $statusCode);
        }
    }
?>