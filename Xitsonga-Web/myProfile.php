<?php
    $pageName = 'myProfile';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"profile_details";
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
    <script type="text/javascript" src ="assets/js/user.js"></script>
    <script>
        $(document).ready(function(e){
            $("#update_user").click(function(e){
                e.preventDefault();

                var vUserData = new Array();
                var pass = Math.random().toString(36).slice(-8);
                vUserData["email"] = $("#email").val();
                vUserData["firstName"] = $("#firstname").val();
                vUserData["lastName"] = $("#lastname").val();
                vUserData["password"] = pass;
                vUserData["cpassword"] = pass;
                
                USER_PROCESSOR.backend_call(USER_CONSTANTS.function.update_user,USER_DATA.registration_json(vUserData));
            });
            
            $("#change_password").click(function(e){
                e.preventDefault();

                var vUserData = new Array();
                vUserData["current_password"] = $("#current_password").val();
                vUserData["password"] = $("#password").val();
                vUserData["cpassword"] = $("#cpassword").val();

                USER_PROCESSOR.backend_call(USER_CONSTANTS.function.change_password,USER_DATA.change_password_json(vUserData));
            }) 
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
        $aFirstName = ucfirst(strtolower($aDTOUser->getFirstName()));
        $aLastname = ucfirst(strtolower($aDTOUser->getLastName()));
    ?>
    <br/>
    <div class="container">
        <div class="row">
            <aside class="col-md-3 sidebar sidebar-left" style ="background: none;border:none;box-shadow: none">
                <table>
                    <tr>
                        <td>
                           <div class ='profile_picture'><img src ="assets/images/user/avatar.png" alt ="Sneidon Dumela"/></div>
                        </td>
                        <td><h4><a href ='myprofile'><?php echo $aFirstName. " ".$aLastname; ?></a></h4></td>
                        </tr>
                </table>
                <br/>
                <div class="row widget sub_heading" style ="background:#FFFFFF">
                    <div class="col-xs-12">
                        <h4>Personal space</h4>
                    </div>
                </div>
                <div class="widget">
                    <ul class="list-unstyled list-spaces" style ="margin-top: 5px;">
                        <li><img src ="assets/images/info.png" alt="" align ='left' width ='15'  style ="margin-top:5px;margin-right: 5px;"/><a href="myprofile/profile_details">Biography</a></li>
                        <li><img src ="assets/images/edit.png" alt="" align ='left' width ='15' style ="margin-top:5px;margin-right: 5px;"/><a href="myprofile/edit_profile">Edit Bio</a></li>
                        <li><img src ="assets/images/settings.png" alt="" align ='left' width ='15' style ="margin-top:5px;margin-right: 5px;"/><a href="myprofile/settings">Settings</a></li>
                    </ul>
                </div>
            </aside>
            <article class="col-md-6 maincontent">
                <?php
                    if($sk =="profile_details"){
                        require_once './assets/html/myprofile_view.php';
                    }elseif($sk =="edit_profile"){
                        require_once './assets/html/myprofile_edit.php';
                    }elseif($sk =="settings"){
                        require_once './assets/html/myprofile_settings.php';
                    }elseif($sk =="preferences"){
                        require_once './assets/html/myprofile_preference.php';
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