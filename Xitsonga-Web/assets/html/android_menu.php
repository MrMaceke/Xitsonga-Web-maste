<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="navbar navbar-inverse headroom navbar-fixed-top" style ="margin-top:-5px;">
    <div class ='top_links_cover'>
    <div class="container">
        <div style ='float:right;font-size:12px;' class ='top_links'>
             <?php
                $aWebBackend = new WebBackend();
                $aDTOUser = $aWebBackend->getCurrentUser();
                if($aDTOUser->isSignedIn()){
                    $aFirstName = ucfirst(strtolower($aDTOUser->getFirstName()));
            ?>
            <a href="myprofile"><?php echo $aFirstName; ?></a>
             <?php
                if($aDTOUser->isAdmin()){
                     echo "<a href='manage/#'>MyAdmin</a>";
                }
            ?>
            <a href="signout">SignOut</a>
            &nbsp;&nbsp;&nbsp;
            <?php
                }else{
            ?>
            <a href="login">Login</a>
            <a href="register">Register</a>
            <a href="accounts">Reset</a>
            &nbsp;&nbsp;
            <?php
                }
            ?>
        </div>
    </div>
    </div>
    <div class="container">
        <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a style ='color:black' class="navbar-brand logo_text" href="kaya"><img src ='assets/images/logo_new.png' width ='40'/>&nbsp;xitsonga.org</a>
        </div>
        <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav pull-right">
                    <li class="active"><a href="kaya">Home</a></li>
                    <li class="active"><a href="privacy">Privacy</a></li>
                    <li><a class="" target = "_tab" href="https://play.google.com/store/apps/developer?id=Sneidon">Google PlayStore</a></li>
                </ul>
        </div>
    </div>
</div> 