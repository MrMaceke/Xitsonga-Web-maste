<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
 */
 
 
?>
<?php
     if($_SERVER['HTTP_HOST'] == "192.168.0.100" or $_SERVER['HTTP_HOST'] == "localhost"){
        echo '<base href="https://localhost/Xitsonga/" />'; 
        $actual_link = "https://localhost/Xitsonga/";
        echo "<link rel='canonical' href='".$actual_link."' />";
     }else{
        echo '<base href="https://www.xitsonga.org/" />'; 
        $actual_link = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        echo "<link rel='canonical' href='".$actual_link."' />";
    }
?>
<link rel="shortcut icon" href="assets/images/Artwork_2.png">

<link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<!-- Custom styles for our template -->
<link rel="stylesheet" href="assets/css/bootstrap-theme.css" media="screen" >
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet" href="assets/css/dialog.css">
<link rel="stylesheet" href="assets/css/scroll.css"> 
<link rel="stylesheet" href="assets/css/rating.css">
<link rel="stylesheet" href="assets/css/jquery.ui.chatbox.css">
<link rel="stylesheet" href="assets/css/jqueryui.css">
<link rel="stylesheet" href="assets/css/jquery-ui.theme.css">
<link rel="stylesheet" href="assets/css/jquery.smartbanner.css" type="text/css" media="screen">
<meta name="msapplication-TileImage" content="assets/images/AppIcon.png" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="assets/js/html5shiv.js"></script>
<script src="assets/js/respond.min.js"></script>
<![endif]-->
<?php
     if($_SERVER['HTTP_HOST'] != "192.168.0.100" AND $_SERVER['HTTP_HOST'] != "localhost"){
 ?>    

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64434902-1', 'auto');
  ga('send', 'pageview');

</script>
<?php
     }
?>
<script type="text/javascript">
if ( window.self !== window.top ) {
    window.top.location.href=window.location.href;
}
</script>
