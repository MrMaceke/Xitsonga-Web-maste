<?php
require_once 'php/DictionaryJSONCache.php';

error_reporting(E_ERROR);
ini_set('display_errors', 1);
set_time_limit(5000);


function cleanString($param) {
    if($param == "--Default--" || $param =="-" || $param =="[]"){
        return "";
    }
    
    $param = str_replace("proverbs-about-", "", $param);
    return $param;
}

function imageUrl($param) {
    if($param == "--Default--" || $param =="-" || $param == ""){
        return "";
    }
    return "https://www.xitsonga.org/assets/images/entity/".$param;
}

$dictionaryFile = file_get_contents("open/data.json");
$dictionaryJson = json_decode($dictionaryFile);

$englishToXitsongaWordList = array();
$xitsongaToEnglishWordList = array();
$categoryList = array();
$oxfordEnglishDefinitionsList = array();

$supported = array("english","xitsonga","phrases","proverbs","idioms","groups");
foreach ($dictionaryJson->entities as $key => $value) {
    $id = trim($value->id);
    $item = trim($value->description);
    $translation = trim($value->translation);
    $speech = cleanString(trim($value->dictionaryType));
    $type = cleanString(trim($value->type));
    $category = cleanString(trim($value->subtype));
    $imageUrl = imageUrl(trim($value->image));
    $extra = cleanString(trim($value->extra));
    
    if($type == "english") {
        $englishToXitsongaWordList[$id] = array(
            "xitsonga"=> $translation,
            "english"=> $item,
            "partOfSpeech"=> $speech,
            "extra" =>$extra,
            "imageUrl" =>$imageUrl
        );
        $dictionaryJSONCache = DictionaryJSONCache::wordFullDefination($item);
        if(count($dictionaryJSONCache) > 1) {
            $oxfordEnglishDefinitionsList[strtolower($item)] = $dictionaryJSONCache;
        }
    } else if($type == "xitsonga") {
        $xitsongaToEnglishWordList[$id] = array(
            "xitsonga"=> $item,
            "english"=> $translation,
            "partOfSpeech"=> $speech,
            "extra" =>$extra,
            "imageUrl" =>$imageUrl
        );
        
        $seachItem = trim($translation); 
        $seachItem = str_replace(",", ".", $seachItem);
        $seachItem = str_replace("‚", ".", $seachItem);
        $seachItem = str_replace("‚", ".", $seachItem);
        $seachItem = explode(".", $seachItem);
        $seachItem = trim($seachItem[0]);
        
        if($seachItem != "" && str_word_count($seachItem) == 1) {
            $dictionaryJSONCache = DictionaryJSONCache::wordFullDefination($seachItem);
            if(count($dictionaryJSONCache) > 1) {
                $oxfordEnglishDefinitionsList[strtolower($seachItem)] = $dictionaryJSONCache;
            }
        }
    } else if($type == "proverbs") {
        $xitsongaProverbs[$id] = array(
            "xitsonga"=> $item,
            "english"=> $translation,
            "englishTranslation" =>$extra,
            "imageUrl" =>$imageUrl
        );
    } else if($type == "idioms") {
        $xitsongaIdioms[$id] = array(
            "xitsonga"=> $item,
            "english"=> $translation,
            "englishTranslation" =>$extra,
            "imageUrl" =>$imageUrl
        );
    } else if($type == "phrases") {
        $xitsongaPhrases[$id] = array(
            "xitsonga"=> $item,
            "english"=> $translation,
            "explaination" =>$extra,
            "imageUrl" =>$imageUrl
        );
    } else if($type == "groups") {
        $groupsPhrasesList[$id] = array(
            "xitsonga"=> $translation,
            "english"=> $item,
            "imageUrl" =>$imageUrl
        );
    }
    
    if($category != "" && in_array($type, $supported)) {
        $categoryList[$type][$category]["data"][] = $id;
        $categoryList[$type][$category]["meta"] = array(
            "description"=> "$category",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        );        
    }
}

$app_version = array(
    "ios"=> array(
        "minSupported"=>1,
        "current"=>1
    ),
    "android"=> array(
        "minSupported"=>1,
        "current"=>1
    )
);

$categories = $categoryList;
        
$content = array(
    "static"=> array(
        "meta"=> array(
            "description"=>"Static content.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        ),
        "data"=> array (
            "time" => array(
                "days"=> array (
                    "meta"=> array(
                        "description"=>"Days of the weeks.",
                        "authors"=> array("Mukondleteri Dumela"),
                        "source"=> "https://www.xitsonga.org."
                    ),
                    "data"=>array(
                        "0"=> array(
                            "xitsonga"=>"Musumbhuku",
                            "english"=>"Monday"
                        ),
                        "1"=> array(
                            "xitsonga"=>"Ravumbirhi",
                            "english"=>"Tuesday"
                        ),
                        "2"=>array(
                            "xitsonga"=>"Ravunharhu",
                            "english"=>"Wednesday"
                        ),
                        "3"=> array(
                            "xitsonga"=>"Ravumune",
                            "english"=>"Thursday"
                        ),
                        "4"=> array(
                            "xitsonga"=>"Ravuntlhanu",
                            "english"=>"Friday"
                        ),
                        "5"=> array(
                            "xitsonga"=>"Mugimeto",
                            "english"=>"Saturday"
                        ), 
                        "6"=> array(
                            "xitsonga"=>"Sonto",
                            "english"=>"Sunday"
                        )
                    )
                ),
                "seasons"=> array (
                    "meta"=> array(
                        "description"=>"Seasons of the year.",
                        "authors"=> array("Mukondleteri Dumela"),
                        "source"=> "https://www.xitsonga.org."
                    ),
                    "data"=>array(
                        "0"=> array(
                            "xitsonga"=>"Ximumu",
                            "english"=>"Summer"
                        ),
                        "1"=> array(
                            "xitsonga"=>"Ximun'wana",
                            "english"=>"Spring"
                        ),
                        "2"=> array(
                            "xitsonga"=>"Xixika",
                            "english"=>"Winter"
                        ),
                        "3"=> array(
                            "xitsonga"=>"Xixikana",
                            "english"=>"Autum"
                        )
                    )
                ),
                "months"=> array(
                    "meta"=> array(
                        "description"=>"Months of the year.",
                        "authors"=> array("Mukondleteri Dumela"),
                        "source"=> "https://www.xitsonga.org."
                    ),
                    "data"=>array(
                        "0"=> array(
                            "xitsonga"=>"Sunguti",
                            "english"=>"January"
                        ),
                        "1"=> array(
                            "xitsonga"=>"Nyenyenyana",
                            "english"=>"February"
                        ),
                        "2"=> array(
                            "xitsonga"=>"Nyenyankulu",
                            "english"=>"March"
                        ),
                        "3"=> array(
                            "xitsonga"=>"Dzivamusoko",
                            "english"=>"April"
                        ),
                        "4"=> array(
                            "xitsonga"=>"Mudyaxihi",
                            "english"=>"May"
                        ),
                        "5"=> array(
                            "xitsonga"=>"Khotavuxika",
                            "english"=>"June"
                        ),
                        "6"=> array(
                            "xitsonga"=>"Mawuwani",
                            "english"=>"July"
                        ),
                        "7"=> array(
                            "xitsonga"=>"Mhawuri",
                            "english"=>"August"
                        ),
                        "8"=> array(
                            "xitsonga"=>"Ndzhati",
                            "english"=>"September"
                        ),
                        "9"=> array(
                            "xitsonga"=>"Nhlangula",
                            "english"=>"October"
                        ),
                        "10"=> array(
                            "xitsonga"=>"Hukuri",
                            "english"=>"November"
                        ),
                        "11"=> array(
                            "xitsonga"=>"N'wendzamhala",
                            "english"=>"December"
                        )
                    )
                )
            )
        )
    ),
    "proverbs"=> array(
        "meta"=> array(
            "description"=>"Proverbs and meanings.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        ),
        "data"=>$xitsongaProverbs
    ),
    "idioms"=> array(
        "meta"=> array(
            "description"=>"Idioms and meanings.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        ),
        "data"=>$xitsongaIdioms
    ),
    "phrases"=> array(
        "meta"=> array(
            "description"=>"Phrases translated to Xitsonga.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        ),
        "data"=>$xitsongaPhrases
    ),
    "groups"=> array(
        "meta"=> array(
            "description"=>"Words and phrases describing things.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        ),
        "data"=>$groupsPhrasesList
    )
);

$definitions = array (
    "oxfordEnglish" => array(
        "meta"=> array(
            "description"=>"A list of english words and definitions.",
            "source"=> "https://www.oed.com."
        ),
        "data" => $oxfordEnglishDefinitionsList
    )
);

$dictionies = array(
    "xitsongaXitsonga"=> array(
        "meta"=> array(
            "description"=>"Xitsonga words described in Xitsonga.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> " https://www.xitsonga.org."
        ),
        "data"=>$xitsongaToXitsongaWordList
    ),
    "xitsongaEnglish"=> array(
        "meta"=> array(
            "description"=>"Words translated from Xitsonga to English.",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> " https://www.xitsonga.org."
        ),
        "data"=>$xitsongaToEnglishWordList
    ),
    "englishXitsonga"=> array(
        "meta"=> array(
            "description"=>"Words translated from English to Xitsonga",
            "authors"=> array("Mukondleteri Dumela", "Akani Maluleke", "Hlamariso Ngobeni"),
            "source"=> "https://www.xitsonga.org."
        ),
        "data"=>$englishToXitsongaWordList
    )
);

$firebase = array(
    "appVersion"=> $app_version,
    "categories"=> $categories,
    "content"=> $content,
    "definitions"=> $definitions,
    "dictionaries"=> $dictionies
);

echo json_encode($firebase); 