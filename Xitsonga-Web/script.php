<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
     if($_SERVER['HTTP_HOST'] == "192.168.0.100" or $_SERVER['HTTP_HOST'] == "localhost"){
        $server = $_SERVER['HTTP_HOST'] ;
        echo '<base href="http://'.$server.'/tsonga/" />';
     }else{
        echo '<base href="http://www.xitsonga.org" />';
        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        echo "<link rel='canonical' href='".$actual_link."' />";
    }
?>
<!--<base href="http://localhost/tsonga/" />-->
<!--<base href="http://192.168.0.100/tsonga/" />-->
<link rel="shortcut icon" href="assets/images/logo_new.png">

<!--<link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">-->
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
<meta name="msapplication-TileImage" content="https://lh3.googleusercontent.com/V3QvH9kp4TNDWiwYsLKp2A35ad9-IsJnDUNjo-F3Lb0XLUyTo6Gb7ExLIO3tnEFT3vU=w300-rw" />
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
<div id="fb-root"></div>
<!--
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=454713284683866";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
-->
<?php
     }
?>
