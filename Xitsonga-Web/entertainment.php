<?php
    $pageName = 'entertainment';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"poems";
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
             $("#addDescription").click(function(e){
                e.preventDefault();
                
                var id = $('.entity_id').attr('id');
                var type = $("select#itemType").val();
                var content = $("#addDescText").val();

                var vItemJSON = {
                     "entity_id": id,
                     "itemType":type,
                     "content": content
                };

                MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_entity_detail,vItemJSON);
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
                window.location = "search/" + $("#word").val();
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
                        <h4>Stories & Poems</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="writing/poems">Poems</a></span></li>
                    </ul>
                    <ul class="list-unstyled list-spaces">
                        <li><a href="writing/short-stories">Short Stories</a></span></li>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Xitsonga Music</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="writing/song-lyrics">Song lyrics</a></span></li>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Recipes</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="writing/traditional-food">Food Recipes</a></span></li>
                    </ul>
                </div>
            </aside>
            <article class="col-md-6 maincontent">
                <div class="row">
                    <div class="new_heading">
                        <h4>
                            <?php
                                echo "<a href ='kaya'>Home</a> > <a href ='writing'>Writing</a> > ";
                                $aTitle = str_replace("_"," ",$sk);
                                echo ucwords($aTitle);
                            ?>  
                        </h4>
                    </div>
                </div>
                <?php
                    if($item != NULL){
                        $data["page"] = "writing";
                        $data["sk"] = $sk;
                        $data["name"] = $item;
                        
                        echo $aWebbackend->getEntityByURL($data);

                        echo "<hr>";
                    }else{
                        echo "<div class ='newBody'>";
                        $sk = strtolower($sk);
                        
                        if($sk == "song-lyrics") {
                            echo "A collection of Xitsonga song lyris. The songs include traditonal and gospel genres";
                            echo "<ul>";
                            echo "<li>A song is <b>risimu</b> in Xitsonga</li>";
                            echo "<li>Music is <b>vunanga</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else if($sk == "jokes") {
                            echo "A collection of Xitsonga jokes";
                            echo "<ul>";
                            echo "<li>Laugh is <b>hleka</b> in Xitsonga</li>";
                            echo "<li>Laughter is <b>mafenya</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else if($sk == "poems") {
                            echo "A collection of Xitsonga poems";
                            echo "<ul>";
                            echo "<li>Poem is <b>xiphatu</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else if($sk == "traditional-food") {
                            echo "A collection of Xitsonga traditional food recipes";
                            echo "<ul>";
                            echo "<li>Method is <b>maendlelo</b> in Xitsonga</li>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else if($sk == "short-stories") {
                            echo "A collection of Xitsonga short stories";
                            echo "<ul>";
                            echo "<li>The community is correcting the information on this page.</li>";
                            echo "</ul>";
                        }else{
                            echo "We are unable to find content matching your request";
                            echo "<ul>";
                            echo "<li>We may have moved the page to a different sub domain.</li>";
                            echo "<li>We may have temporarily suspended the content.</li>";
                            echo "</ul>";
                        }
                       
                        echo "</div>";
                        
                        echo "<hr>";
                        
                        $item_per_page = 20;
                        $data["entity_type"] = $aTitle;
                        $data["page"] = "writing";
                        $data["sk"] = $sk;
                         
                        require_once 'assets/html/pages_setup_1.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityByType($data)."</div>";

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