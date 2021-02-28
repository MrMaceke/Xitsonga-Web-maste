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
            <a href="/award">Awards</a>
            <a href="products/">Products</a>
            <a href="rest-api/">DevAPI</a>
            <a href="status/">Status</a>
            <a href="signout/">SignOut</a>
            &nbsp;&nbsp;&nbsp;
            <?php
                }else{
            ?>
            <a href="assets/html/award.php">Awards</a>
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
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span style="color:white">MENU</span></button>
            <a style ='color:black;font-size:14px' class="navbar-brand logo_text" href="kaya"><img title = "Xitsonga.org" src ="assets/images/Artwork_2.png" width ='35'/>&nbsp;XITSONGA.ORG</a>&nbsp;&nbsp;&nbsp;
        </div>
        <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav pull-right">
                    <li class="active"><a href="dictionary"></span>Xitsonga Dictionary</a></li>
                    <li class="active"><a href="proverbs">Proverbs & Idioms</a></li>
                    <li class="active"><a href="grammar">Language & Grammar</a></li>
                    <li class="active"><a href="people">Xitsonga Names</a></li>
                    <li class="active"><a href="translator"></span>Xitsonga Translator</a></li>
                    <!--<li class="active"><a href="chatbot"></span>ChatBot</a></li>-->
                    <!--<li class="active"><a href="learn">Exercises</a></li>-->
                    <li class="active"><a href="writing">Poems & Stories</a></li>
                </ul>
        </div>
    </div>
</div>
<?php
  if(rand(2,2) == 2 && FALSE){
?>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-2115635082855707",
          enable_page_level_ads: true
     });
</script>
<?php
} else  {
    /*
   $random = rand(1,3);
   echo "<a href ='https://applefloors.co.za/' target ='_tab'>";
   if($random == 1){
     echo "<div style ='width:100%;margin:10px;text-align: center;'><img src ='assets/images/slider1.png' width ='600px' style =''/></div>";
   } else if($random == 2){
     echo "<div style ='width:100%;margin:10px;text-align: center;'><img src ='assets/images/slide2.png' width ='600px' style =''/></div>";
   } else if($random == 3){
     echo "<div style ='width:100%;margin:10px;text-align: center;'><img src ='assets/images/slide3.png' width ='600px' style =''/></div>";
   }
   echo "</a>";
     *
     */
}
?>
