<?php
    $pageName = 'scholar';
    require_once 'webBackend.php';
    require_once './php/TsongaTime.php';
    require_once './php/TsongaNumbers.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"xitsonga"; 
    $first = isset($_REQUEST['-']) && $_REQUEST['-'] != ""? $_REQUEST['-']:"a"; 
    $item = isset($_REQUEST['_']) && $_REQUEST['_'] != ""? $_REQUEST['_']:NULL; 
    $success = isset($_REQUEST['success']) && $_REQUEST['success'] != ""? $_REQUEST['_']:NULL; 
    
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
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/dict.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryM.js"></script>
    <script type="text/javascript" src ="assets/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready(function(e){
            $("#number").click(function(e){
                e.preventDefault();

                var vData = new Array();

                vData["number"] = $("#number_val").val();

                DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.ask_number,DICT_DATA.number_json(vData))
            });
            
             $("#time_botton").click(function(e){
                e.preventDefault();

                var vData = new Array();

                vData["time"] = $("#number_val").val();

                DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.ask_time,DICT_DATA.time_json(vData))
            });
            
            
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
            }); 
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
        <div class="row" style ="">
            <aside class="col-md-3 sidebar sidebar-left marginTablet">
                <div>
                    <form class ='basic_form'>
                        <div class="row">
                            <div class ="col-md-12">
                                <input id ="word" type="text" class="form-control" placeholder="Search">
                                <button class="btn btn-action margin_class search" type="submit">Search</button>
                            </div>
                        </div>
                   </form>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Xitsonga Dictionary</h4>
                    </div>
                </div>
                
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="dictionary/xitsonga">A-Z Xitsonga - English</a></li>
                        <li><a href="dictionary/english">A-Z English - Xitsonga</a></li>
                    </ul>
                </div>

		<div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4 style =''>Dictionary Categories</h4>
                    </div>
                </div>
                
		<div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="dictionary/chiefdom">Chiefdom</a></li>
                        <li><a href="dictionary/weather">Weather</a></li>
                        <li><a href="dictionary/technology">Technology</a></li>
                        <li><a href="dictionary/terminology">Terminologies</a></li>
                        <li><a href="dictionary/colors">Colors</a></li>
                        <li><a href="dictionary/minerals">Minerals</a></li>
                        <li><a href="dictionary/astronomy-planets">Planets</a></li>
                        <li><a href="dictionary/direction">Directions</a></li>
                        <li><a href="dictionary/family-relationships">Family</a></li>
                        <li><a href="dictionary/body-parts">Body Parts</a></li>
                        <li><a href="dictionary/job-titles">Job Titles</a></li>
                    </ul>
                </div>
                
                 <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Animals & Fruits</h4>
                          <p>
                          </p>
                    </div>
                </div>
               
                 <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="dictionary/animals">Animals names</a></li>
                        <li><a href="dictionary/grasshoppers">Grasshopper names</a></li>
                        <li><a href="dictionary/trees">Tree names</a></li>
                        <li><a href="dictionary/fruits">Fruit names</a></li>
                        <li><a href="dictionary/vegetables">Vegetables names</a></li>
                    </ul>
                </div>
                
                 <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Time Measurements</h4>
                          <p>
                          </p>
                    </div>
                </div>
                
                 <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="dictionary/days">Days of the week</a></li>
                        <li><a href="dictionary/months">Months of the year</a></li>
                        <li><a href="dictionary/seasons">Seasons of the year</a></li>
                        <li><a href="dictionary/time">Time in Xitsonga</a></li>
                        <li><a href="dictionary/measurements">Time measurements</a></li>
                    </ul>
                </div>
                
                 <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Numbers</h4>
                          <p>
                        </p>
                    </div>
                </div>
                
                 <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="dictionary/numbers">Numbers in Xisonga</a></li>
                    </ul>
                </div>
                <?php
                     require './assets/html/google_ads.php';
                ?>
            </aside>
            <article class="col-md-6 maincontent">
                <div class="row">
                    <div class ='new_heading'>
                        <h4>
                            
                            <?php
                                echo "<a href ='kaya'>Home</a> > <a href ='dictionary'>Dictionary</a> > ";
                                
                                $aTitle = str_replace("_"," ",$sk);

                                if(strtolower($aTitle) == "english"){
                                    echo "A-Z English - Xitsonga";
                                }elseif(strtolower($aTitle) == "xitsonga"){
                                   echo "A-Z Xitsonga - English";
                                }else{
                                    echo ucwords($aTitle);
                                }
                            ?>
                        </h4>
                    </div>
                </div>
                <?php
                    if($success != NULL){
                        echo '<div class ="newBody">Suggestion is being processed. Thank you :)</div>';
                    }
                    //require_once './assets/html/save_as.php';
                    
                    $array_by_type = Array("xitsonga","english");
                    if($item != NULL){
                        $data['page'] = "dictionary";
                        $data["sk"] = $sk;
                        $data["name"] = $item;
                         
                        echo $aWebbackend->getEntityByURL($data);
                        
                        //echo "<a class='btn btn-primary btn-large' href ='$_SERVER[REQUEST_URI]#openModal'>Add description</a>";
                        
                        $data["word"] = $item;
                        echo $aWebbackend->listEntityWithWords($data);
                        
                        echo "<br/>";
                    }
                    elseif($sk == "xitsonga" OR $sk == "english"){
                        echo '<div class ="newBody">';
                        echo 'Thousands of words translated from <a href="dictionary/xitsonga">Xitsonga to English</a> and <a href="dictionary/english">English to Xitsonga</a>';
                        echo "<ul>";
                        echo "<li>The list was compiled by website editors.";
                        echo "<li>The community is correcting the information on this page.</li>";
                        echo "</ul>";
                        echo "</div>";
                        
                        require_once './assets/html/save_as.php';
                        
                        $data["entity_type"] = $aTitle;
                        $data["letter"] = $first;
                        $data["page"] = "dictionary";
                        $data["sk"] = $sk;
                        $data["html"] = "table";
                                                
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityByTypeAndFirstLetter($data)."</div>";
                        
                        require_once './assets/html/letters.php';
                        
                        echo "<br/>";

                    }elseif($sk == "numbers"){
                        echo "<div class ='number_div newBody'>
                                <div class ='error'></div><form class ='basic_form'><span>Enter a number between 0 and 9999</span> <input id ='number_val' size ='10'/> <button clas ='btn' id ='number'>Submit</button></form>
                             </div><hr>";
                        $aDisplay = "<table id ='dictionary_data_table' cellspacing='0' width='100%'>";
                        $aDisplay = $aDisplay
                        ."<thead>"
                            ."<tr>"
                            . "<th>Xitsonga</th>"
                            . "<th>English</th>"
                            . "<th>Plural</th>"
                            ."</tr>"
                        . "</thead>";
                        
                        $aDisplay = $aDisplay."<tr>";
                        $aDisplay = $aDisplay."<td>Khume</td>";
                        $aDisplay = $aDisplay."<td>Ten</td>";
                        $aDisplay = $aDisplay."<td>Makume</td>";
                        $aDisplay = $aDisplay."</tr>";
                        
                        $aDisplay = $aDisplay."<tr>";
                        $aDisplay = $aDisplay."<td>Dzana</td>";
                        $aDisplay = $aDisplay."<td>Hundred</td>";
                        $aDisplay = $aDisplay."<td>Madyana</td>";
                        $aDisplay = $aDisplay."</tr>";
                        
                        $aDisplay = $aDisplay."<tr>";
                        $aDisplay = $aDisplay."<td>Gidi</td>";
                        $aDisplay = $aDisplay."<td>Thousand </td>";
                        $aDisplay = $aDisplay."<td>Magidi</td>";
                        $aDisplay = $aDisplay."</tr>";
                        
                        $aDisplay = $aDisplay."<tr>";
                        $aDisplay = $aDisplay."<td>Gidi ya magidi</td>";
                        $aDisplay = $aDisplay."<td>Million</td>";
                        $aDisplay = $aDisplay."<td>Magidi ya magidi</td>";
                        $aDisplay = $aDisplay."</tr>";
                        
                        $aDisplay = $aDisplay."<tr>";
                        $aDisplay = $aDisplay."<td>Biliyoni</td>";
                        $aDisplay = $aDisplay."<td>Billion</td>";
                        $aDisplay = $aDisplay."<td>Tibiliyoni</td>";
                        $aDisplay = $aDisplay."</tr>";
                        
                        $aDisplay = $aDisplay."</table>";
                        
                        echo $aDisplay;
			$aNumber = new TsongaNumbers();
			$aFirst = 0; $aCount = 0; $aLast = 0;
			$displayHeader = true;
			for($aNumberCount = 0; $aNumberCount <= 100; $aNumberCount ++){
                            if($displayHeader) {
                                if($aNumberCount == 0 OR $aNumberCount == 10) {
                                        $aLast = $aNumberCount + 9;
                                } else {
                                        $aFirst = $aFirst + 1;
                                        $aLast = $aNumberCount + 9;
                                }
                                if($aFirst == 90){
                                        $aLast = $aNumberCount + 10;
                                }
                                echo "<hr><div class ='singlenumber'> <h4 class ='heading_deco red'>Numbers $aFirst - $aLast</h4></div><hr>";
                                $displayHeader = false;
                            }

                            echo "<div class ='singlenumber'>";
                            //echo "<b class ='blue'>$aNumberCount</b> <span>".$aNumber ->getNumberInTsonga($aNumberCount). " <img src ='audio/play_sound.png' width ='25px'/></span>";
                            echo "<b class ='blue'>$aNumberCount</b> <span>".$aNumber ->getNumberInTsonga($aNumberCount). "</span>";
                            echo "</div>";

                            if($aNumberCount == 9){
                                    echo "<br/>";
                               $displayHeader = true;
                               $aFirst = $aNumberCount + 1;
                            }else if(($aNumberCount + 1) % 10 == 0 AND $aNumberCount != 0 AND ($aNumberCount) != 10 AND ($aNumberCount) != 99){
                               echo "<br/>";
                               $displayHeader = true;
                               $aFirst = $aNumberCount;
                            }
			}
			 echo "<br/>";                      
                    }elseif($sk == "time"){
                          echo "<div class ='number_div newBody'>
                                <div class ='error'></div><span>Enter a time in format { HH:mm } - { 13:30 } - { 24 hours format }</span><br/><br/> <form class ='basic_form'><input id ='number_val' size ='10'/> <button clas ='btn' id ='time_botton'>Submit</button></form>
                             </div><hr>";
                          
                        $aTsongaTimer = new TsongaTime();

                        $en = Array("January","February","March","April","May","June","July","August","September","October","November","December");
                        $xi = Array("Sunguti","Nyenyenyana","Nyenyankulu","Dzivamusoko","Mudyaxihi","Khotavuxika","Mawuwani","Mhawuri","Ndzhati","Nhlangula","Hukuri","N'wendzamhala");

                        $aCurrentTime = $aTsongaTimer->currentTime();

                        $aTime = $aTsongaTimer->returnRealTime($aCurrentTime);

                        $aDate = $aTsongaTimer->returnRealDate($aCurrentTime);

                        $aDay = $aDate[2];
                        $aMonth = $aDate[1] - 1;
                        $aYear = "20".$aDate[0];

                        $aMinute = $aTime[1];
                        $aHour = $aTime[0];
                        if($aMinute < 10){
                            $aMinute = "0".$aMinute;
                        }
                        echo "<ol class='breadcrumb' style ='box-shadow:none'>
                                <li><a>Current date</a></li>
                                <li class='active'>$aDay $en[$aMonth] $aYear, $aHour:$aMinute</li>
                            </ol>";
                        
                        echo "<div class='jumbotron top-space' style ='margin-top:25px'><h4>Date in Xitsonga &mdash; $aDay $xi[$aMonth] $aYear</h4></div>";

                        echo "<div class='jumbotron top-space' style ='margin-top:15px'><h4>Time in Xitsonga &mdash; ".ucfirst($aTsongaTimer->getTime($aTsongaTimer->returnRealTime($aTsongaTimer->currentTime())))."</h4></div>";

                    }elseif($sk == "calendar"){  
                        echo "<div class ='newBody'>";
                        echo "A list of days, months and seasons translated from <b>Xitsonga to English</b>";
                        echo "<ul>";
                        echo "<li>A day is <b>siku</b> in Xitsonga.</li>";
                        echo "<li>A month is <b>n'hweti</b> in Xitsonga.</li>";
                        echo "<li>A season is <b>nguva</b> in Xitsonga.</li>";
                        echo "</ul>";
                        echo "</div>";
                        echo "<br/>";
                        echo "<h4  class ='red'>Days of the week</h4>";
                        echo "<hr>";
                        $en = Array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
                        $xi = Array("Musumbhunuko","Ravumbirhi","Ravunharhu","Ravumune","Ravuntlhanu","Mugimeto","Sonto");
                        
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>";
                        echo "<h4 class ='red'>Months of the year</h4>";
                        echo "<hr>";
                        $en = Array("January","February","March","April","May","June","July","August","September","October","November","December");
                        $xi = Array("Sunguti","Nyenyenyana","Nyenyankulu","Dzivamusoko","Mudyaxihi","Khotavuxika","Mawuwani","Mhawuri","Ndzhati","Nhlangula","Hukuri","N'wendzamhala");

                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>";
                        echo "<h4 class ='red'>Seasons of the year</h4>";
                        echo "<hr>";

                        $en = Array("Summer","Spring","Winter","Autum");
                        $xi = Array("Ximumu","Ximun'wana","Xixika","Xixikana");

                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);
                        
                        echo "<br/>";
                    }elseif($sk == "days"){  
                        echo "<div class ='newBody'>";
                        echo "A list of days translated from <b>Xitsonga to English</b>";
                        echo "<ul>";
                        echo "<li>A day is <b>siku</b> in Xitsonga.</li>";
                        echo "</ul>";
                        echo "</div>";
                        echo "<br/>";
                        echo "<h4  class ='red'>Days of the week</h4>";
                        echo "<hr>";
                        $en = Array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
                        $xi = Array("Musumbhunuko","Ravumbirhi","Ravunharhu","Ravumune","Ravuntlhanu","Mugimeto","Nsoto");
                        
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>";
                    }elseif($sk == "months"){  
                        echo "<div class ='newBody'>";
                        echo "A list of months translated from <b>Xitsonga to English</b>";
                        echo "<ul>";
                        echo "<li>A month is <b>n'hweti</b> in Xitsonga.</li>";
                        echo "</ul>";
                        echo "</div>";
                        echo "<br/>";                        
                        echo "<h4 class ='red'>Months of the year</h4>";
                        echo "<hr>";
                        $en = Array("January","February","March","April","May","June","July","August","September","October","November","December");
                        $xi = Array("Sunguti","Nyenyenyana","Nyenyankulu","Dzivamusoko","Mudyaxihi","Khotavuxika","Mawuwani","Mhawuri","Ndzhati","Nhlangula","Hukuri","N'wendzamhala");

                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>";
                    }elseif($sk == "seasons"){  
                        echo "<div class ='newBody'>";
                        echo "A list of seasons translated from <b>Xitsonga to English</b>";
                        echo "<ul>";
                        echo "<li>A season is <b>nguva</b> in Xitsonga.</li>";
                        echo "</ul>";
                        echo "</div>";
                        echo "<br/>";
                       
                        echo "<h4 class ='red'>Seasons of the year</h4>";
                        echo "<hr>";

                        $en = Array("Summer","Spring","Winter","Autum");
                        $xi = Array("Ximumu","Ximun'wana","Xixika","Xixikana");

                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);
                        
                        echo "<br/>";
                    }elseif($sk == "terminology"){
                        
                        echo '<div class ="newBody">';
                        echo 'Terminologies used in different industries translated from <b>Xitsonga to English</b>';
                        echo "<ul>";
                        echo "<li>The community verified the list is correct.</li>";
                        echo "</ul>";
                        echo "</div><br/>";
                        
                        echo "<h4 class ='red'>Medical Terminology</h4>";
                        echo "<hr>";
						$en = Array("Human Immunodeficiency Virus","birth weight","sex","death","sanitation","handicap","disability");
                        $xi = Array("xitsongwatsongwana xa nsawuto","ntiko wa ku velekiwa","rimbewu","rifu","nkululo","ku tsoniwa","vutsoniwa");
						
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>";
                        echo "<h4 class ='red'>Legal Terminology</h4>";
                        echo "<hr>";
                        $en = Array("Legal","law","questionnaire","marital status","single","relationship","partner", "married","separated","divorced","widow","widower","place of birth","residence","occupation","employed","race","religion","gender","investigation","plaintiff","resolved case","unresolved case","proxy","vote");
                        $xi = Array("Xinawu","nawu","khwexinere","xiyimo xa vukati","ku nga tekiwangi","Vuxaka","muringani","tekile","hambanile","muthariwa","noni","nguluve","ndhawu ya ku velekiwa","vutshamo","ntirho","loyi a thoriweke","rixaka","vukhongeri","rimbewu","ndzavisiso","mumangari","mhaka leyi ololoxiweke","mhaka leyi nga ololoxiwangiki","muyimeri","vhoti");
                           
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);
                       echo "<br/>";
                        echo "<h4 class ='red'>Technology Terminology</h4>";
                        echo "<hr>";

                        $en = Array("cellphone","computer","laptop","mouse","wireless","printer","cable");
                        $xi = Array("selefoni","khomphyuta/khomphyutara","khomphyuta yo xixingiwa","mawusi","wayilese","pirintara","khebulu");

                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);
		
                        echo "<br/>";
                        echo "<h4 class ='red'>Mathematical Terminology</h4>";
                        echo "<hr>";
                        $en = Array("addition","subtraction","multiplication","division","object","circle","square","triangle","cylinder","rectangle","sphere");
                        $xi = Array("hlanganisa","susa","andzisa","avanyisa","nchumu","rhandzavula","xikwere","yinhla nharhu","silindara","	rhekithengula","rhandzavula");
    
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>";
                        echo "<h4 class ='red'>Textbook Terminology</h4>";
                        echo "<hr>";
                        $en = Array("Teacher's Guide","Learner's Book","Workbook","Grade","Term","Unit","Activity","Lesson","page","Assessment","Informal Assessment","Classroom management","Programme of Assessment","Resources","Learning Programme","Systemic assessment","Integration");
                        $xi = Array("Xiletelo xa Mudyondzisi","Buku ya Mudyondzi","Buku ya ntirho","Giredi","Nguva","Yuniti","Ngingiriko","Dyondzo","pheji","Hlelelo","Mahlelelo ya nkalamafundza","Mafambiselo ya kamara yo dyondzela","Nongoloko wa Mahlelelo","Swipfuneta","	Nongonoko wo Dyondza","	Ndlela ya Mahlelelo","Nhlanganiso");
                        
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);
						
                        echo "<br/>";
                        echo "<h4 class ='red'>Subjects Terminology</h4>";
                        echo "<hr>";
                        $en = Array("Arts and Culture","Economic and Management Sciences","Life Orientation","Life Skills","Literacy","Mathematics","Numeracy","Natural Sciences","Social Sciences");
                        $xi = Array("Vutshila ni mfuwo","Sayense ya Ikhonomi na Mafambisele","Ndzhendzeleko wa vutomi","Vutshila bya Vutomi","Litheresi","Tinhlayo","Nyumeresi","Sayense ya Ntumbuluko","Sayense ya Vutomi");
    
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);

                        echo "<br/>"; 
						
						echo "<hr>";
						echo "Parts of the data was sourced from <a target ='_tab' href ='https://iitranslation.com/resources/English-Xitsonga.html'>https://iitranslation.com/resources/English-Xitsonga.html</a>";
						echo "<hr>";
                    }elseif($sk == "measurements"){						
						echo "<div class ='newBody'>";
                        echo "A list of time measurements  translated from <b>Xitsonga to English</b>";
                        echo "<ul>";
                        echo "<li>Time is <b>nkarhi</b> in Xitsonga.</li>";
                        echo "<li>Measure is <b>pima</b> in Xitsonga.</li>";
                        echo "</ul>";
                        echo "</div>";
                        echo "<br/>";
                        $en = Array("Second","Seconds","Minute","Minutes","Hour","Hours","day","days","week","weeks","month","months","year","years","yesterday","today","tommorow");
                        $xi = Array("sekende","tisekende","minete","timinete","awara","tiawara","siku","masiku","vhiki","mavhiki","n'hweti","tin'hweti","lembe","malembe","tolo","namuntlha","mundzuku");
                        
                        echo $aWebBackend->GetTABLEHTMLList($xi,$en);
                        
                        echo "<br/>";
                        
						echo "<hr>";
						echo "Parts of the data was sourced from <a target ='_tab' href ='https://iitranslation.com/resources/English-Xitsonga.html'>https://iitranslation.com/resources/English-Xitsonga.html</a>";
						echo "<hr>";
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
                        }elseif($aTitle == "colors"){
                            echo "A list of colors translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A color is <b>muhlovo</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "body-parts"){
                            echo "A list of body parts translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A body part is <b>xirho xa mirhi</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "weather"){
                            echo "A list of seasons and weather condiction translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>Weather is <b>maxelo</b> in Xitsonga.</li>";
                            echo "<li>A season is <b>nguva</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "animals"){
                            echo "A list of birds, wild and domestic animals names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A bird is <b>xinyenyana</b> in Xitsonga.</li>";
                            echo "<li>A domestic animal is <b>xiharhi xa le kaya</b> in Xitsonga.</li>";
                            echo "<li>A wild animal is <b>xiharhi xa le nhoveni</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "grasshoppers"){
                            echo "A list of grasshopper/locust names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A grasshopper is <b>njiya</b> in Xitsonga.</li>";
                            echo "<li>Many Xitsonga speakers eat grasshoppers, or at least used to</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "family-relationships"){
                            echo "A list of words used to descibe family relationships from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A family is <b>ndyangu</b> in Xitsonga.</li>";
                            echo "<li>A relationship is <b>vuxaka</b> in Xitsonga.</li>";
                            echo "<li>A relative is <b>xaka</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "fruits"){
                            echo "A list fruits names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>Fruits translates to <b>mihandzu</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "vegetables"){
                            echo "A list vegetables names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>Vegetables translates to <b>matsavu</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "money"){
                            echo "Money matters translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>Money translates to <b>mali</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "trees"){
                            echo "A list tree names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A tree is <b>nsinya</b> in Xitsonga.</li>";
                            echo "<li>The information was sourced from FanathePurp";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "job-titles"){
                            echo "A list of job titles  translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A job is <b>ntirho</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "direction"){
                            echo "Directions translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "minerals"){
                            echo "Minerals names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>Minerals are <b>swicelwa</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "chiefdom"){
                            echo "Words which describes chiefdom hierarchies translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                             echo "<li>Chiefdom is <b>vuhosi</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "astronomy-planets"){
                            echo "Planet names and astronomy terms translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A planet is <b>nyeleti</b> in Xitsonga. Nyeleti also means means star.</li>";
                            echo "<li>Astronomy is <b>ntivo tinyeleti</b> in Xitsonga which means knowledge of the stars.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "technology"){
                            echo "Digital and technology terms translated from <b>English to Xitsonga</b>";
                            echo "<ul>";
                            echo "<li>Technology is <b>nhluvukiso</b> in Xitsonga.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "adjectives"){
                            echo "A list of adjectives translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A adjective is <b>rihlawuri</b> in Xitsonga</li>";
                            echo "<li>A <a href ='http://www.gingersoftware.com/content/grammar-rules/adjectives/'>adjective</a> are words that describe or modify other words. They can identify or quantify another person or thing in the sentence.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else{
                            $aFound = TRUE;
                            echo "We are unable to find content matching your request";
                            echo "<ul>";
                            echo "<li>We may have moved the page to a different sub domain.</li>";
                            echo "<li>We may have temporarily suspended the content.</li>";
                            echo "</ul>";
                        }
                        
                        echo "</div>";
                        if(!$aFound){
                            require_once './assets/html/save_as.php';
                            echo "<br/>";
                            $item_per_page = 30;
                            $data["entity_sub_type"] = $aTitle;
                            $data["page"] = "dictionary";
                            $data["sk"] = $sk;

                            require_once 'assets/html/pages_setup_2.php';

                            $data["start"] = $start;
                            $data["end"] = $end;
                            echo "<div style ='margin:0px'>".$aWebbackend->listEntityBySubType($data)."</div>";

                            require_once 'assets/html/pages.php';


                            if($aTitle == "trees"){
                                echo "<br/>";
                                echo "<hr>";
                                echo "Data was sourced from <a target ='_tab' href ='http://fanathepurp.co.za/a-list-of-tsonga-fruits-plants-and-trees-and-their-scientific-names/'>http://fanathepurp.co.za</a>";
                                echo "<hr>";
                            } else if($aTitle == "chiefdom"){
                                echo "<br/>";
                                echo "<hr>";
                                echo "Parts of the data was sourced from <a target ='_tab' href ='http://www.vivmag.co.za/archives/12425'>http://www.vivmag.co.za</a>";
                                echo "<hr>";
                            } else if($aTitle == "minerals"){
                                echo "<br/>";
                                echo "<hr>";
                                echo "Parts of the data was sourced from <a target ='_tab' href ='http://www.vivmag.co.za'>http://www.vivmag.co.za</a>";
                                echo "<hr>";
                            } else if($aTitle == "grasshoppers"){
                                echo "<br/>";
                                echo "<hr>";
                                echo "Parts of the data was sourced from <a target ='_tab' href ='http://madyondza.blogspot.com/'>http://madyondza.blogspot.com/</a>";
                                echo "<hr>";
                            }
                        }
                        
                        echo "<br/>";
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