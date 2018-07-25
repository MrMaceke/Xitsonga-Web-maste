<?php
    $pageName = 'manage';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"init";
    $first = isset($_REQUEST['-']) && $_REQUEST['-'] != ""? $_REQUEST['-']:"";
    $item = isset($_REQUEST['_']) && $_REQUEST['_'] != ""? $_REQUEST['_']:""; 

     
    if(!$aWebbackend->hasAccess($pageName)){
        header('Location: ../access');
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
    <link href="assets/css/jBox.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/editor.css" rel="stylesheet" type="text/css"/>
    
    <script type="text/javascript" src ="assets/js/jquery.js"></script>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/jquery.noty.packaged.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryM.js"></script>
    <script type="text/javascript" src ="assets/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
    <script type="text/javascript" src ="assets/js/jquery.form.js"></script>
    <script type="text/javascript" src ="assets/js/editor.js"></script>
    <script type="text/javascript" src ="assets/js/WQDFA12907.js"></script>
    <script type="text/javascript" src ="assets/js/WQDFA12908.js"></script>
    <script type ="text/javascript">
        var editor;
        $(document).ready(function(){
            
            $('#audits_api_data_table').dataTable({
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "order": [
                    [ 1, "desc" ]
                ]
            });
            
            $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
                editor.inline( this );
            } );
        });
    </script>
</head>

<body class="home">
   
    <?php
        require_once './assets/html/nav.php';
    ?>
     <?php
        //$br = "<br/>";
        if($sk =='users'){
           require_once './assets/html/send_mail_dialog.php'; 
        }elseif($sk == "exercises"){
            if($item != ""){
                $aResult = $aWebbackend->getExerciseByURL($item);
               
                $question_id = $aResult[resultsArray][exercise_id];  
                $exercise_id = $aResult[resultsArray][exercise_id];  
            }
            require_once './assets/html/add_exercise_dialog.php'; 
        }elseif($sk =='types'){
            require_once './assets/html/add_type_dialog.php'; 
        } elseif($sk =='translation_configs'){
            require_once './assets/html/add_translation_dialog.php'; 
        }else{
            require_once './assets/html/add_entity_dialog.php'; 
            $br = "";
        }
    ?>

    <div class="container adminContainer">
        <div class="row">
           
            <aside class="col-md-4 sidebar sidebar-left left_admin marginTablet">
                <?php
                    
                   if(strtolower($aDTOUser->getEmail()) == "sneidon@yahoo.com"){
                ?>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>System Developer</h4>
                    </div>
                </div>
                
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a target ='_tab' href="https://xitsonga.org/webBackend.php?method=generateDictionaryJSON">Refresh</a></li>
                        <li><a href="manage/types">Types</a></li>
                        <li><a href="manage/users">Users</a></li>
                        <li><a href="manage/entity">Entity</a></li>
                        <li><a href="manage/translation_configs">Translations</a></li>
                    </ul>
                </div>
                <br/>
                <?php
                   }
                ?>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Exercises</h4>
                    </div>
                </div>
              
                <div class="widget sub_links sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="manage/exercises">Exercises</a></li>
                    </ul>
                </div>
                <br/>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Audit Trail Apps</h4>
                    </div>
                </div>
                <div class="widget sub_links sub_links">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="manage/dictionary_app">Dictionary App</a></li>
                        <li><a href="manage/numbers_app">Numbers in App</a></li>
                        <li><a href="manage/time_app">Time in App</a></li>
                        <li><a href="manage/translate_app">Translate in App</a></li>
                        <li><a href="manage/kids_app">Kids App</a></li>
                        <li><a href="manage/kids_terms">Kids Terms</a></li>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Audit Trail Editors</h4>
                    </div>
                </div>
              
                <div class="widget sub_links sub_links">
                    <ul class="list-unstyled list-spaces">
                        <?php
                            $array = $aWebbackend->listAdminUsers();
                            foreach ($array[resultsArray] as $key => $value) {
                                $text = lcfirst($value[user_id]);
                                echo "<li><a href ='manage/contributors?_=$text'>".ucwords(strtolower($value[firstname]))." ".ucwords(strtolower($value[lastname]))."</a></li>";
                            }
                        ?>
                    </ul>
                </div>
                <br/>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Find by Type</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <?php
                            $content_type = 1;
                            $array = $aWebbackend->listItemTypesType($content_type);

                            foreach ($array as $key => $value) {
                                $text = lcfirst($value[description]);
                                if($text != "--Default--"){
                                    echo "<li><a href ='manage/entity?_=$text'>$value[description]</a></li>";
                                }
                            }
                        ?>
                    </ul>
                </div>
                <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Find by Dictionary</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <?php
                            $content_type = 3;
                            $array = $aWebbackend->listItemTypesType($content_type);

                            foreach ($array as $key => $value) {
                                $text = lcfirst($value[description]);
                                if($text != "--Default--"){
                                    echo "<li><a href ='manage/tag?_=$text'>$value[description]</a></li>";
                                }
                            }
                        ?>
                    </ul>
                </div>
                 <div class="row widget sub_heading">
                    <div class="col-xs-12">
                        <h4>Find by Tag</h4>
                    </div>
                </div>
                <div class="widget sub_links">
                    <ul class="list-unstyled list-spaces">
                        <?php
                            $content_type = 4;
                            $array = $aWebbackend->listItemTypesType($content_type);

                            foreach ($array as $key => $value) {
                                $text = lcfirst($value[description]);
                                if($text != "--Default--"){
                                    echo "<li><a href ='manage/tag?_=$text'>$value[description]</a></li>";
                                }
                            }
                        ?>
                    </ul>
                </div>
                <hr>
            </aside>
            <br/>
            <article class="col-md-8 maincontent right_admin">
                <header class="page-header">
                  <h4 class ='heading_deco redColor'>
                        <?php
                            if($item != ""){
                                $aTitle = str_replace("_"," ",$sk." - ".$item);
                            }else{
                                $aTitle = str_replace("_"," ",$sk);
                            }
                            if($sk =='init'){ 
                                echo "Admin Panel";
                            }else if($sk =='contributors'){ 
                                $aArray = $aWebbackend->getUserByID($item);
                                if($aArray[status]){
                                    $aArray = $aArray[resultsArray];
                                    echo "<a href ='contributor/$item'>".ucwords(strtolower($aArray[firstname]))." ".  ucwords(strtolower($aArray[lastname]))."</a>". " audit trail";
                                }
                            }else{
                                echo ucwords($aTitle);
                            }
                        ?>  
                    </h4>
                </header>
                <?php
                    if($sk =='init'){ 
                ?>
                <img src ="assets/images/tibbr-security-admin-banner.png" width="100%"/>
                <br/><br/>
                <h4>Acquiring access</h4>
                <hr>
                <ul>
                    <li>Only administrator users can add and edit content on the website.</li>
                    <li>You can request administrator access by sending an email to info@xitsonga.org</li>
                </ul>
                <br/>
                <h4>Guidelines and documents</h4>
                <ul>
                    <li>Adding and editing entity documentation and rules. Download <a target="_tab" href ="assets/documents/Xitsonga processes.pdf">PFD document</a> - published 26 October 2015</li>
                    <!--<li>Direction of xitsonga.org for the next 18 months. Download <a target="_tab" href ="assets/documents/Xitsonga.org goals for the 18 months.pdf">PFD document</a> - published 27 September 2015</li>-->
                </ul>
                <hr>
                <?php
                    }
                    elseif($sk == "exercises"){
                        
                        if($item != ""){
                           
                            $aResult = $aWebbackend->getExerciseByURL($item);
                            if($aResult[status]){
                                echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openQuestionsModal'>Add Question</a>  <a class ='btn main_action' href ='manage/exercises'>Back</a><br/><hr>";
                                $text = str_replace("_"," ",$item);
                                /*
                                echo "<div class ='heading_div'>";
                                echo strtoupper($text);
                                echo "</div>";
                                 * 
                                 */
                                
                                if($aResult[resultsArray][published] == 1){
                                    echo "<div class ='heading_div' style ='background:#FFFACD'>";
                                    echo "Exercise is already in production. You must edit with caution.";
                                    echo "</div>";
                                    echo "<hr>";
                                }
                            }else{
                                echo $aResult[message];
                            }
                            $question_id = $aResult[resultsArray][exercise_id];  
                            $exercise_id = $aResult[resultsArray][exercise_id];  
                            echo $aWebbackend->listQuestionsByExerciseID($exercise_id);
                            echo "<hr>";
                        }else{
                            echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openExerciseModal'>Add Exercise</a><br/><hr>";

                            echo $aWebbackend->listExercises();
                            
                            echo "<hr>";
                        }
                    } elseif($sk =='translation_configs'){ 
                        echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openTranslationModal'>Add Translation Config</a> <br/><hr>";
                        
                        echo $aWebbackend->GetTranslationConfigList();
                        
                        echo "<hr>";
                                                
                    }
                    elseif($sk =='tag'){ 
                        echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openModal'>Add Entity</a> <br/><hr>";
                        
                       
			$data["start"] = 0;
                        $data["end"] = 3000;
                        $data['entity_sub_type'] = "$item";
                        
                        echo $aWebbackend->listEntityByTypeAdmin($data);
                        
                        echo "<hr>";
                                                
                    }elseif($item == "xitsonga" OR $item == "english"){
                        if($first == ""){
                            $first = "a";
                        }
                        echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openModal'>Add Entity</a><br/><hr>";

                        $data['page'] = "manage";
                        $data["entity_type"] = $item;
                        $data["letter"] = $first;
                        $data["sk"] = $sk;
                        $data["html"] = "table";
                        
                        echo "<div style ='margin:0px'>".$aWebbackend->listEntityByTypeAndFirstLetterAdmin($data)."</div>";
                        
                        require_once './assets/html/letters_2.php';
                        
                        echo "<hr>";
                        
                    }elseif($sk == "contributors"){
                        
                        echo $aWebbackend->listAuditsByUser($item);
                        
                        echo "<hr><br/>";
                        
                    }elseif($item != ""){
                         echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openModal'>Add Entity</a><br/><hr>";
                        
                        $item_per_page = 3000;
                        $data['page'] = "manage";
                        $data["entity_type"] = $item;
                        
                        require_once 'assets/html/pages_setup_2.php';

                        $data["start"] = $start;
                        $data["end"] = $end;
                        
                        echo $aWebbackend->listEntityByType($data);
                        
                        echo "<hr>";
                                                
                    } elseif($sk =='dictionary_app') {
                        
                        echo $aWebbackend->GetAuditAPICallsHTMLList();
                      
                        echo "<hr>";
                        
                    }elseif($sk =='numbers_app') {
                        
                        echo $aWebbackend->GetAuditNumbersAPICallsHTMLList();
                         echo "<hr>";
                        
                    }elseif($sk =='time_app') {
                        
                        echo $aWebbackend->GetAuditTimeAPICallsHTMLList();
                        echo "<hr>";
                        
                    }
                    elseif($sk =='translate_app') {
                        
                        echo $aWebbackend->GetAuditTranslateAPICallsHTMLList();
                        echo "<hr>";
                        
                    }elseif($sk =='kids_app') {
                        
                        echo $aWebbackend->GetAuditKidsAPICallsList();
                        
                         echo "<hr>";
                        
                    }elseif($sk =='kids_terms') {
                        
                        echo $aWebbackend->GetAuditKidsKeysAPICallsList();
                        
                         echo "<hr>";
                        
                    }elseif($sk =='users' and strtolower($aDTOUser->getEmail()) == "sneidon@yahoo.com"){
                        echo "<a id = 'openSendMailModalButton' class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openSendMailModal'>System Emails</a> <br/><hr>";

                        echo $aWebbackend->listUsers(WebBackend::$USERS_MANAGE);
                        echo "<hr>";
                        
                    }elseif($sk =='types' AND strtolower($aDTOUser->getEmail()) == "sneidon@yahoo.com"){
                        echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openModal'>Add Type</a><br/><hr>";
                        echo $aWebbackend->listAllItemTypes();
                        
                        echo "<hr>";
                    }else if($sk =='entity' AND strtolower($aDTOUser->getEmail()) == "sneidon@yahoo.com"){ 
                        echo "<a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openModal'>Add Entity</a> <a class='btn btn-primary btn-large overlay' href ='$_SERVER[REQUEST_URI]#openUploadModal'>Upload Entities</a><br/><hr>";
                        
                        //$item_per_page = 100;
                        //require_once 'assets/html/pages_setup_3.php';
                        
			//$data["start"] = $start;
                        //$data["end"] = $end;
                        //$data['page'] = "manage";
                        if($first == ""){
                            $first = "a";
                        }
                        $data['letter'] = "$first";

                        echo $aWebbackend->listEntity($data);
                        
                        echo "<hr>";
                        
                        require 'assets/html/letters_1.php'; 
                        echo "<hr>";
                    }
                ?>
            </article>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>