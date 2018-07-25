<?php
    $pageName = 'profile';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
    
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:NULL;
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
    ?>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
        
        $aResult = $aWebbackend->getUserByID($sk);
        
        if($aResult[status]){
            $aFirstName = $aResult[resultsArray][firstname];
            $aLastname = $aResult[resultsArray][lastname];
            $user = 1;
        }else{
            $user = -1;
        }
        
    ?>
   
    <div class="container">
    <br/>
        <div class="row">
           <aside class="col-md-3 sidebar sidebar-left" style ="background: none;border:none;box-shadow: none">
               <?php
                 if($user == -1){
                     
                 }else{
               ?>
                <table>
                    <tr>
                        <td>
                           <div class ='profile_picture'><img src ="assets/images/user/avatar.png" alt ="<?php echo $aFirstName. " ".$aLastname; ?>"/></div>
                        </td>
                        <td><h4><a href ='<?php echo "contributor/".$sk; ?>'><?php echo $aFirstName. " ".$aLastname; ?></a></h4></td>
                        </tr>
                </table>
                <br/>
                <div class="row widget sub_heading" style ="background:#FFFFFF">
                    <div class="col-xs-12">
                        <h4>Contributor space</h4>
                    </div>
                </div>
                <div class="widget">
                    <ul class="list-unstyled list-spaces" style ="margin-top: 5px;">
                        <li><img src ="assets/images/info.png" alt="" align ='left' width ='15'  style ="margin-top:5px;margin-right: 5px;"/><a href='<?php echo "contributor/".$sk; ?>'>Biography</a></li>
                    </ul>
                </div>
                <?php
                 }
                ?>
            </aside>
            
             <article class="col-md-6 maincontent">
              
                <?php
                    //$aDTOUser = new DTOUser();
                    if($user == 1){
                        $aFirstName = ucfirst(strtolower($aDTOUser->getFirstName()));
                        $aLastname = ucfirst(strtolower($aDTOUser->getLastName()));
                        $aEmail = $aResult[resultsArray][email];
                        $aRegistration = ucfirst(strtolower(""));
                        $access = "Website User or Learner";

                        $admin = $aResult[resultsArray][admin_user];
                        if($admin == 1){
                            $access = "Administrator";  
                        }
                    }
                ?>
                <?php
                   if($user == -1){echo "<div class='desc_heading' style ='margin-top:-10px'><h4>User no found</h4></div>"; }
                   else{
                ?>
                 <div class="row">
                    <div class ='new_heading'>
                        <h4>
                            
                            <?php
                                echo "<a href ='kaya'>Home</a> > Profile";
                            ?>
                        </h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Contact Information</h4>
                    </div>
                </div>
                <table>
                    <tr>
                        <td><img src ="assets/images/email_1.png" alt="" width ='25' style ="margin-right: 10px;"/></td>
                        <td><?php echo strtolower($aEmail); ?></td>
                    </tr>
                    <tr>
                        <td><img src ="assets/images/tick.png" alt="" width ='25' style ="margin-right: 10px;"/></td>
                        <td><?php echo strtolower($access); ?></td>
                    </tr>
                </table>
                 <br/>
                 <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Contributions</h4>
                    </div>
                </div>
                <table>
                    <tr>
                        <td><b>Content added</b> is <?php echo $aWebbackend->listEntityByUserIdCount($aResult[resultsArray][user_id]); ?> in total.</td>
                    </tr>
                    <tr>
                        <td><b>Content edited</b> is <?php echo $aWebbackend->listAuditTrailByUserIDCount($aResult[resultsArray][user_id]); ?> in total.</td>
                    </tr>
                </table>
                 <?php }?>
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
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>