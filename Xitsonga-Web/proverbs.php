<?php
    $pageName = 'sayings';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"proverbs";
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
            $(".search").click(function(e){
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
                        <h4>Proverbs</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="proverbs/proverbs">Swivuriso - Proverbs</a></span></li>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Idioms & Riddles</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="proverbs/idioms">Swivulavulelo  - Idioms</a></span></li>
                        <li><a href="proverbs/riddles">Mintshayito  - Riddles</a></span></li>
                    </ul>
                </div>
                 <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Categories</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="proverbs/proverbs-about-life">Life</a></span></li>
                        <li><a href="proverbs/proverbs-about-death">Death</a></span></li>
                        <li><a href="proverbs/proverbs-about-animals">Animals</a></span></li>
                        <li><a href="proverbs/proverbs-about-fruits">Fruits</a></span></li>
                    </ul>
                </div>
            </aside>
            <article class="col-md-6 maincontent">
                <div class="row">
                    <div class ='new_heading'>
                        <h4>
                       <?php
                            echo "<a href ='kaya'>Home</a> > <a href ='proverbs'>Proverbs</a> > ";
                            $aTitle = str_replace("_"," ",$sk);
                            echo "Xitsonga ".  strtolower(str_replace("-"," ",$aTitle));
                        ?> 
                        </h4>
                    </div>
                </div>
                <?php
                    if($item != NULL){
                        $data["page"] = "proverbs";
                        $data["sk"] = $sk;
                        $data["name"] = $item;
                        
                        echo $aWebbackend->getEntityByURL($data);
                                                
                        echo "<br/>";
                        
                    }else if($sk == "proverbs" or $sk == "riddles" or $sk == "idioms"){
                        echo "<div class ='newBody'>";
                        if($sk == "proverbs"){
                            echo "A list of  <b>Xitsonga proverbs</b> translated to English.";
                            echo "<ul>";
                            echo "<li>A proverb is <b>xivuriso</b> in Xitsonga.</li>";
                            echo "<li>A proverb is a short pithy saying in general use, stating a general truth or piece of advice.</li>";
                            echo "<li>The translation in english are directed and some are interpretations.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else if($sk == "riddles"){
                            echo "A list of  <b>Xitsonga riddles</b> translated to English.";
                            echo "<ul>";
                            echo "<li>A riddles is <b>tshayito</b> in Xitsonga.</li>";
                            echo "<li>An riddle is a question or statement intentionally phrased so as to require ingenuity in ascertaining its answer or meaning, typically presented as a game.</li>";
                            echo "<li>The translation in english are directed and some are interpretations.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else if($sk == "idioms"){
                            echo "A list of  <b>Xitsonga idioms</b> translated to English.";
                            echo "<ul>";
                            echo "<li>An idiom is <b>xivulavulelo</b> in Xitsonga.</li>";
                            echo "<li>An idiom is a group of words established by usage as having a meaning not deducible from those of the individual words (e.g., rain cats and dogs, see the light).</li>";
                            echo "<li>The translation in english are directed and some are interpretations.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else{
                            echo str_replace("-"," ", $sk);
                        }
                        echo "</div>";
                        require_once './assets/html/save_as.php';

                        $item_per_page = 30;
                        $data["entity_type"] = $aTitle;
                        $data["page"] = "proverbs";
                        $data["sk"] = $sk;

                        require_once 'assets/html/pages_setup_1.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityByType($data)."</div>";

                        require_once 'assets/html/pages.php'; 
                        
                        echo "<br/>";
                    } else {
                        echo "<div class ='newBody'>";
                        echo "A list of ".ucfirst(str_replace("-"," ", $sk));
                        echo "<ul>";
                            echo "<li>A proverb is <b>xivuriso</b> in Xitsonga.</li>";
                            echo "<li>A proverb is a short pithy saying in general use, stating a general truth or piece of advice.</li>";
                            echo "<li>The translation in english are directed and some are interpretations.</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                        echo "</ul>";
                        echo "</div>";
                        require_once './assets/html/save_as.php';
                        
                        $item_per_page = 30;
                        $data["entity_sub_type"] = $aTitle;
                        $data["page"] = "proverbs";
                        $data["sk"] = $sk;

                        require_once 'assets/html/pages_setup_1.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityBySubType($data)."</div>";

                        require_once 'assets/html/pages.php'; 
                        
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