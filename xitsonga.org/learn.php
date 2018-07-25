<?php
    $pageName = 'learn';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"learn";
    $item = isset($_REQUEST['_']) && $_REQUEST['_'] != ""? $_REQUEST['_']:NULL; 
    $state = isset($_REQUEST['_']) && $_REQUEST['-'] != ""? $_REQUEST['-']:NULL; 
    
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
    <script>
        $(document).ready(function(e){
            $(".btn-action").click(function(e){
                e.preventDefault();
                
                var vQuestionId = "";
                
                var vDetail  = {};
                var vCount = 0;
                $(".question_div").each(function(e){
                    vQuestionId = $(this).attr('id');
                    vDetail[vCount] = {
                        "questionId": $(this).find('.title').attr('id'),
                        "answerId": $(this).find('.answersStyle input:checked').val()
                    };
                    vCount ++;
                });

                var vJSON = {
                    "exerciseID": vQuestionId,
                    "exerciseURL": $("#exercise_url").val(),
                    "answers":vDetail
                };
                
                MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.submit_exercise, vJSON);
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
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 0px" class ='app_div'>
                <div class="row">
                    <div class="new_heading">
                        <h4>
                            <?php
                                echo "<a href ='kaya'>Home</a> > Learn ";
                            ?>  
                        </h4>
                    </div>
                </div>
                <?php
                    if($item != NULL){
                        
                    }else{
                ?>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Xitsonga Exercises</h4>
                    </div>
                </div>
                <?php
                    }
                ?>
                <?php
                   if($item != NULL){
                       $aResult = $aWebbackend->getExerciseByURL($item);
                 
                       echo "<input type ='hidden' id ='exercise_url' value ='$item'/>";
                       if($aResult[status] && $state != NULL){
                            
                            $aExerciseID = $aResult[resultsArray][exercise_id];
                            $aContent = $aResult[resultsArray][exercises_text];
                            $aTitle = $aResult[resultsArray][exercise_title];
                            
                            echo "<div class='rating_div' style ='margin-bottom: -15px'>   
                                    <div class ='desc_heading'>
                                        <h4 id ='vision'>$aTitle</h4>
                                    </div>
                                </div>";
                           
                           echo "<div style ='margin-bottom:15px;color:white;margin-left:-15px;margin-top:-10px;margin-right:-15px;padding:10px;background:url(assets/images/gradient-background.jpg);border:1px solid #CCCCCC'>Thanks for taking the test. The results are as follows</div>";
                           
                           echo $aWebbackend->listexerciseResultsFromSession($aExerciseID);
                       }
                       elseif($aResult[status]){
                            $aExerciseID = $aResult[resultsArray][exercise_id];
                            $aContent = $aResult[resultsArray][exercises_text];
                            
                            $aTitle = $aResult[resultsArray][exercise_title];
                            
                            echo "<div class='rating_div' style ='margin-bottom: -15px'>   
                                    <div class ='desc_heading'>
                                        <h4 id ='vision'>$aTitle</h4>
                                    </div>
                            </div>";
                            
                           echo "<div style ='margin-bottom:15px;color:white;margin-left:-15px;margin-top:-10px;margin-right:-15px;padding:10px;background:url(assets/images/gradient-background.jpg);border:1px solid #CCCCCC'>$aContent</div>";
                            
                            echo $aWebbackend->listQuestionsAndAswersByExerciseID($aExerciseID);
                            
                            echo '<table><tr><td><button class="btn btn-action" type="submit">Submit test</button></td><td><div class ="loading_image"></div></td></tr></table><hr>';
                       }else{
                           echo $aResult[message];
                       }
                   }else{
                        $item_per_page = 8;
                        $data["published"] = 1;
                        
                        require_once 'assets/html/pages_setup_4.php';
                        
                        $data["page"] = "learn";
                        $data["start"] = $start;
                        $data["end"] = $end;
                        
                        echo "<div style ='margin:0px'>".$aWebbackend->listPublishedExercises($data)."</div>";
                        
                        require_once 'assets/html/pages.php';
                        
                        echo "<br/>";
                   }
                ?>
            </article>
             <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
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