<?php
    $pageName = 'grammar';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"verbs";
    $item = isset($_REQUEST['_']) && $_REQUEST['_'] != ""? $_REQUEST['_']:NULL; 

    if(!$aWebbackend->hasAccess($pageName)){
        header('Location: access');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        require_once './assets/html/metadata.php';
        require_once './assets/html/script.php';
        require_once './assets/html/script_2.php';
    ?>
    <link href="assets/css/jqueryui.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
        <script type="text/javascript" src ="assets/js/jqueryM.js"></script>
    <script type="text/javascript">
        $(document).ready(function(e){
             $(".link_group").click(function(e){
                e.preventDefault();
                
                var subType = $(this).attr('id');
                var documentType = $(this).attr('rel');
                
                var vItemJSON = {
                    "sub_type": subType,
                    "documentType" : documentType
                };


                MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.download_type_as_PDF,vItemJSON);
            });
            
             $(function(){
                  $('.star-rating').rating(function(vote, event){
                       var id = $('.desc_heading h4').attr('id');
                        var type = "rating";
                        var content = vote;

                        var vItemJSON = {
                             "entity_id": id,
                             "itemType":type,
                             "content": content
                        };
                        
                        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.rate_entity,vItemJSON);
                  });
             });
        });
    </script>
          <script>
        $(document).ready(function(e){
            $(".btn-action").click(function(e){
                e.preventDefault();
                window.location = "search?sk=" + $("#word").val();
            }) 
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
    <br/>
    <div class="container">
		<?php
			require_once './assets/html/google_ads.php';   
		?>
        <div class="row">
            <aside class="col-md-3 sidebar sidebar-left marginTablet">
             
                <div>
                    <form class ='basic_form'>
                        <div class="top-margin">
                            <input id ="word" type="text" class="form-control" placeholder="Search">
                            
                            <button class="btn btn-action margin_class search" type="submit">Search</button>
                        </div>
                   </form>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Xitsonga Grammar</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="grammar/verbs">Verbs - Riendli</a></li>
                        <li><a href="grammar/adverbs">Adverbs - Riengeteri</a></li>
                        <li><a href="grammar/nouns">Nouns - Riviti</a></li>
                        <li><a href="grammar/pronouns">Pronouns - Risivi</a></li>
                        <li><a href="grammar/adjectives">Adjectives - Rihlawuri</a></li>
                        <li><a href="grammar/conjunctions">Conjunctions - Rihlanganisi</a></li>
                    </ul>
                </div>
                 <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Verb Tenses</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="grammar/tenses?_=present">Present tense</a></li>
                        <li><a href="grammar/tenses?_=past">Past tense</a></li>
                        <li><a href="grammar/tenses?_=continuous">Continuous tense</a></li>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                         <h4>Nym Words</h4>  
                   
                    </div>
                </div>
                 <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="grammar/homonyms">Homonyms - Mafana peletwana</a></li>
                        <li><a href="grammar/synonyms">Synonyms - Vamavizweni </a></li>
                        <li><a href="grammar/antonyms">Antonyms - Marito fularha</a></li>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                         <h4>Common Phrases</h4>  
                   
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="grammar/greeting">Greetings</a></li>
                        <li><a href="grammar/emotions">Emotions & feelings</a></li>
                        <li><a href="grammar/how-to-ask">Ask for things</a></li>
                        <li><a href="grammar/phrases">Common phrases</a></li>
                    </ul>
                </div>
                
            </aside>
            <article class="col-md-6 maincontent">
                <div class="row">
                    <div class="new_heading">
                        <h4>
                            <?php
                                echo "<a href ='kaya'>Home</a> > <a href ='grammar'>Language & Grammar</a> > ";
                                $aTitle = str_replace("_"," ",$sk);
                                echo ucwords($aTitle);
                            ?>  
                        </h4>
                    </div>
                </div>
                <?php
                   
                    if($item != NULL AND $sk != "tenses"){
                        $data["page"] = "grammar";
                        $data["sk"] = $sk;
                        $data["name"] = $item;

                        echo $aWebbackend->getEntityByURL($data);
                        
                        $data["word"] = $item;
                        echo $aWebbackend->listEntityWithWords($data);
                         echo "<br/>";
                        
                    } elseif($sk == "tenses"){
                        echo "<div class ='newBody'>";
                        if($item == "present"){
                            echo "A list of verbs in present tense translated from <b>Xitsonga to English</b>.";
                            echo "<ul>";
                            echo "<li>A verb is <b>riendli</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/verbs/'>verb</a> is a word used to describe an action, state, or occurrence, and forming the main part of the predicate of a sentence, such as hear, become, happen.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                            
                            echo "</div>";
                            echo "<div class ='newBody'>";
                             echo " The present tense is formed by simply using the personal pronoun along with the verb.";
                             echo "<ul>";
                            echo "<li>Ndzi lava swakudya – I want food</li>";
                            echo "<li>U kota ku sweka – S/He knows how to cook</li>";
                            echo "</ul>"; 
                        }elseif($item == "past"){
                            echo "A list of verbs in past tense translated from <b>Xitsonga to English</b>.";
                            echo "<ul>";
                            echo "<li>A verb is <b>riendli</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/verbs/'>verb</a> is a word used to describe an action, state, or occurrence, and forming the main part of the predicate of a sentence, such as hear, become, happen.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                            
                             echo "</div>";
                            echo "<div class ='newBody'>";
                             echo "This is for in one of three ways.";
                             echo "<ul>";
                            echo "<li>Generally, one drops the 'a' from the verb and adds the prefix '-ile'</li>";
                            echo "<li>With verbs that end with -ala, in the past change to -ele or -ale.</li>";
                            echo "<li>In many cases merely changing the last 'a' in the verb to an 'e' indicates past action</li>";
                            echo "</ul>"; 
                        }elseif($item == "continuous"){
                            echo "A list of verbs in continuous tense translated from <b>Xitsonga to English</b>.";
                            echo "<ul>";
                            echo "<li>A verb is <b>riendli</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/verbs/'>verb</a> is a word used to describe an action, state, or occurrence, and forming the main part of the predicate of a sentence, such as hear, become, happen.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                            
                             echo "</div>";
                            echo "<div class ='newBody'>";
                             echo "Generally, to indicate ongoing actions in the present one takes the personal pronoun, drops the 'i' and adds 'a'.";
                             echo "<ul>";
                            echo "<li>Wa hemba – You are lying</li>";
                            echo "<li>Ndzi nghena (e)ndlwini – I am entering the house</li>";
                            echo "</ul>"; 
                        }else{
                             echo "A verb is <i>riendli</i> in Xitsonga."; 
                        }
                        echo "</div>";
                         echo "<br/>";
                         
                          require_once './assets/html/save_as.php';
                          
                        $item_per_page = 15;
                        $data["entity_sub_type"] = "verbs";
                        $data["page"] = "grammar";
                        $data["sk"] = $sk;
                        $data["item"] = $item;
                        require_once 'assets/html/pages_setup_2.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityBySubType($data)."</div>";

                        require_once 'assets/html/pages.php';
                        
                        echo "<br/>";
                    }
                    elseif($sk == "phrases"){
                        echo "<div class ='newBody'>";
                        echo "This is a list of <b>common Xitsonga phrases</b> translated to English.";
                        echo "<ul>";
                            echo "<li>A phrase is <b>xivulwana</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        echo "</div>";
                        echo "<hr>";
                        $item_per_page = 30;
                        $data["entity_type"] = $aTitle;
                        $data["page"] = "grammar";
                        $data["sk"] = $sk;

                        require_once 'assets/html/pages_setup_1.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityByType($data)."</div>";

                        require_once 'assets/html/pages.php'; 
                        
                        echo "<br/>";
                    }else{
                        echo "<div class ='newBody'>";
                        if($aTitle == "verbs"){
                            echo "A list of verbs translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A verb is <b>riendli</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/verbs/'>verb</a> is a word used to describe an action, state, or occurrence, and forming the main part of the predicate of a sentence, such as hear, become, happen.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "nouns"){
                            echo "A list of nouns translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A noun is <b>riviti</b> in Xitsonga.</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/nouns/'>noun</a> is a part of speech that denotes a person, animal, place, thing, or idea.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "pronouns"){
                            echo "A list of pronouns translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A pronoun is <b>risivi</b> in Xitsonga.</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/pronoun/'>pronoun</a> is a word that can function by itself as a noun phrase and that refers either to the participants in the discourse (e.g., I, you ) or to someone or something mentioned elsewhere in the discourse (e.g., she, it, this ).</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "adverbs"){
                             echo "A list of adverb translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A adverb is <b>riengeteri</b> in Xitsonga.</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/adverb/'>adverb</a> is a word or phrase that modifies or qualifies an adjective, verb, or other adverb or a word group, expressing a relation of place, time, circumstance, manner, cause, degree, etc. (e.g., gently, quite, then, there ).</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "adjectives"){
                            echo "A list of adjectives translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A adjective is <b>rihlawuri</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/adjectives/'>adjective</a> are words that describe or modify other words. They can identify or quantify another person or thing in the sentence.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "conjunctions"){
                            echo "A list of conjunctions translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A conjunction is <b>rihlannganisi</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/conjunctions/'>conjunction</a> is part of speech that is used to connect words, phrases, clauses, or sentences. Conjunctions are considered to be invariable grammar particle, and they may or may not stand between items they conjoin.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "greeting"){
                            echo "A list of <b>ways of greeting</b> people in Xitsonga.";
                            echo "<ul>";
                            echo "<li>A phrase is <b>xivulwana</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "homonyms"){
                            echo "A list of homonyms translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A homonyms are <b>mafana peletwana</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://dictionary.reference.com/browse/homonym'>homonyms</a> are words each of two or more words having the same spelling but different meanings and origins (e.g., pole and pole).</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "antonyms"){                            
                            echo "A list of antonyms translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A antonyms are <b>marito fularha</b> in Xitsonga</li>";
                            echo "<li>An <a href ='http://dictionary.reference.com/browse/antonym'>antonym</a> is a word opposite in meaning to another (e.g., bad and good ).</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "synonyms"){                            
                            echo "A list of synonyms translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>Synonyms are <b>vamavizweni</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://dictionary.reference.com/browse/synonym'>synonym</a> a word having the same or nearly the same meaning as another in the language, as happy, joyful, elated.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else{
                            
                            echo "A list of phrases about ".ucfirst(str_replace("-"," ", $sk));
                            echo "<ul>";
                            echo "<li>A phrase is <b>xivulwana</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }
                        echo "</div>";
                        
                        require_once './assets/html/save_as.php';
                         
                        $item_per_page = 30;
                        $data["entity_sub_type"] = $aTitle;
                        $data["page"] = "grammar";
                        $data["sk"] = $sk;
                        require_once 'assets/html/pages_setup_2.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityBySubType($data)."</div>";

                        require_once 'assets/html/pages.php';
                        
                        echo "<br/>";
                        
                        if($aTitle == "pronouns"){
                            echo "<hr>";
                            echo "Parts of the data was sourced from <a target ='_tab' href ='http://madyondza.blogspot.co.za/2014/05/tsonga-as-foreign-language.html'>http://madyondza.blogspot.co.za</a>";
                            echo "<hr>";
                        }else if($aTitle == "conjunctions"){
                            echo "<hr>";
                            echo "Parts of the data was sourced from <a target ='_tab' href ='https://ts.wikipedia.org/wiki/RIHLANGANISI'>https://ts.wikipedia.org/wiki/rihlannganisi</a>. The Page edited by Amos Mahonisi";
                            echo "<hr>";
                        }
                        
                        
                    }
                ?>
            </article>
             <aside class="col-md-2 sidebar sidebar-right marginRightTablet fillWebsite">
               <?php
                   require_once './assets/html/side_nav_right.php';
               ?>
            </aside>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>