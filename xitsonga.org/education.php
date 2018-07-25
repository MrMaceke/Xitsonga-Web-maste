<?php
    $pageName = 'videos';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"Featured";
    $item = isset($_REQUEST['_']) && $_REQUEST['_'] != ""? $_REQUEST['_']:"test_video"; 
     
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
    <link href="assets/css/dialog.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/video-js.css" rel="stylesheet" type="text/css"/>    
    
    <script type="text/javascript" src ="assets/js/jquery.js"></script>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryM.js"></script>
    <script type="text/javascript" src ="assets/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
    <script type="text/javascript" src ="assets/js/video.dev.js"></script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
    <br/>
    <div class="container">
        <div class="row">
            
            <article class="col-md-8 maincontent right marginTablet" style ="margin-left: 0px">
                <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > Tutorials</h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">
                            <?php
                            $aTitle = str_replace("_"," ",$sk);
                            echo ucwords($aTitle);
                        ?>  
                        </h4>
                    </div>
                </div>
                <?php
                    if($item != NULL){
                        $data["page"] = "learn";
                        $data["sk"] = "video";
                        $data["name"] = $item;
                        
                        $aRecord =  $aWebbackend->getEntityArrayByURL($data);
                        
                        $aResult = $aRecord[0];
                        
                        $aEntityDetails = new EntityDetailsDAO();
                        $aDetailResults =  $aEntityDetails->getEntityDetailsByEntityId($aResult[entity_id]);
                        if($aDetailResults['status']){
                            $array = array();
                            foreach($aDetailResults['resultsArray'] as $aDetailResult){ 
                              $array[$aDetailResult[description]] = $aDetailResult;                              
                            }
                        }
                        
                        $title = $aResult[entity_name];
                        $desc = $array[ItemTypeDAO::$ENGLISH_TRANS][content];
                        $url = $array[ItemTypeDAO::$URL_REFERENCE][content];
                        //$url = "xzx20dkb.mp4";
                        $aImg = $array[ItemTypeDAO::$IMAGE][content];
                        if($aImg == ""){
                            $aImg = "no_image.png";
                        }
                    }
                ?>
                
                <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="100%" 
                       poster="assets/images/entity/<?php echo $aImg; ?>"
                    data-setup={ "controls": true, "autoplay": false, "preload": "auto" }>
                  <source src="assets/videos/<?php echo $url ?>" type='video/mp4' />
                  <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                </video>
                <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">
                            <?php
                             echo $title;
                        ?>  
                        </h4>
                    </div>
                </div>
                <p><?php echo $desc; ?></p>
            </article>
             <aside class="col-md-4 sidebar sidebar-right marginRightTablet">
                <div class="row widget">
                    <div class="col-xs-12">
                            <h4>Videos</h4>
                            <hr>
                            <?php
                                $item_per_page = 3;
                                $data["entity_type"] = "video";
                                $data["page"] = "tutorials";
                                $data["sk"] = "video";

                                require_once 'assets/html/pages_setup_1.php';

                                $data["start"] = $start;
                                $data["end"] = $end;
                                echo "<div style ='margin:0px'>".$aWebbackend->listEntityByType($data)."</div>";
                                
                                require_once 'assets/html/pages.php';
                            ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>