<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="navbar navbar-inverse navbar-top headroom" style="margin-top: -2px;">
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
                     echo "<a href='manage'>MyAdmin</a>";
                }
            ?>
	    <a href="products/">Products</a>
            <a href="rest-api/">DevAPI</a>
            <a href="status/">Status</a>
            <a href="signout/">SignOut</a>
            &nbsp;&nbsp;&nbsp;
            <?php
                }else{
            ?>
            <a href="login/">Login</a>
            <a href="products/">Products</a>
	   <a href="rest-api/">DevAPI</a>
            <a href="status/">Status</a>
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
                <a style ='color:black;font-size:14px' class="navbar-brand logo_text" href="kaya"><img src ="assets/images/AppIcon.png" width ='35'/>&nbsp;XITSONGA.ORG</a>
        </div>
        <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav pull-right">
                    <li class="active"><a href="rest-api/">Try it</a></li>
                    <li class="active"><a href="contact/">Support</a></li>
                    <li class="active"><a href="legal/">Terms Of Use</a></li>
                </ul>
        </div>
    </div>
</div> 
