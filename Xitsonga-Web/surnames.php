<?php
    $pageName = 'people';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"names";
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
                        <h4>Names & Surnames</h4>
                         <p>
                        </p>
                    </div>
                </div>
             
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="people/names">Xitsonga names</a></span></li>
                        <li><a href="people/surnames">Xitsonga surnames</a></span></li>
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
                        <li><a href="people/animals">Animals names</a></li>
                        <li><a href="people/grasshoppers">Grasshopper names</a></li>
                        <li><a href="people/trees">Tree names</a></li>
                        <li><a href="people/fruits">Fruit names</a></li>
                        <li><a href="people/vegetables">Vegetables names</a></li>
                    </ul>
                </div>
                
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                         <h4>Places & Things</h4>  
                   
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="people/countries">Countries names</a></li>
                        <li><a href="people/cities">Cities names</a></li>
                    </ul>
                </div>
            </aside>
            <article class="col-md-6 maincontent">
                 <div class="row">
                    <div class="new_heading">
                        <h4>
                            <?php
                                echo "<a href ='kaya'>Home</a> > <a href ='people'>Naming</a> > ";
                                $aTitle = str_replace("_"," ",$sk);
                                echo ucwords($aTitle);
                            ?>  
                        </h4>
                    </div>
                </div>
                <?php
                    if($item != NULL){
                        $data["page"] = $pageName;
                        $data["sk"] = $sk;
                        $data["name"] = $item;

                        echo $aWebbackend->getEntityByURL($data);

                        //echo "<a class='btn btn-primary btn-large' href ='$_SERVER[REQUEST_URI]#openModal'>Add description</a>";
                        $data["word"] = $item;
                        echo $aWebbackend->listEntityWithWords($data);
                        
                        echo "<br/>";
                    } elseif($sk == "surnames"){
                        echo "<div class ='newBody'>";
                        if($aTitle == "surnames"){
                            echo "A list of Xitsonga surnames";
                            echo "<ul>";
                            echo "<li>A surname is <b>xivongo</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }
                         echo "</div>";
                        echo "<hr>";
                        $item_per_page = 30;
                        $data["entity_type"] = $aTitle;
                        $data["page"] = $pageName;
                        $data["sk"] = $sk;

                        require_once 'assets/html/pages_setup_1.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityByType($data)."</div>";

                        require_once 'assets/html/pages.php'; 
                        
                        echo "<br/>";
                    }else{
                        echo "<div class ='newBody'>";
                        if($aTitle == "cities"){
                            echo "A list city names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A city is <b>doroba</b> in Xitsonga.</li>";
                            echo "<li>The information was sourced from FanathePurp";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "countries"){
                            echo "A list country names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A country is <b>tiko</b> in Xitsonga.</li>";
                            echo "<li>The information was sourced from FanathePurp";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "names"){
                            echo "A list of Xitsonga names translated from <b>Xitsonga to English</b>.";
                            echo "<ul>";
                            echo "<li>A name is <b>vito</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "colors"){
                            echo "A colors names in Xitsonga translated from <b>Xitsonga to English</b>.";
                            echo "<ul>";
                            echo "<li>A color is <b>muhlovo</b> in Xitsonga</li>";
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
                        }elseif($aTitle == "grasshoppers"){
                            echo "A list of grasshopper/locust names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A grasshopper is <b>njiya</b> in Xitsonga.</li>";
                            echo "<li>Many Xitsonga speakers eat grasshoppers, or at least used to</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }elseif($aTitle == "trees"){
                            echo "A list tree names translated from <b>Xitsonga to English</b>";
                            echo "<ul>";
                            echo "<li>A tree is <b>nsinya</b> in Xitsonga.</li>";
                            echo "<li>The information was sourced from FanathePurp";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }
                        echo "</div>";
                        require_once './assets/html/save_as.php';
                        
                        $item_per_page = 30;
                        $data["entity_sub_type"] = $aTitle;
                        $data["page"] = $pageName;
                        $data["sk"] = $sk;
                        require_once 'assets/html/pages_setup_2.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityBySubType($data)."</div>";

                        require_once 'assets/html/pages.php';
                        
                        echo "<br/>";
                      
                    }
                    
                    if($aTitle == "grasshoppers"){
                        echo "<hr>";
                        echo "Parts of the data was sourced from <a target ='_tab' href ='http://madyondza.blogspot.com/'>http://madyondza.blogspot.com/</a>";
                        echo "<hr>";
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