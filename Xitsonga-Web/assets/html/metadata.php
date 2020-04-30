<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<title>
    <?php 
        $data['sk'] = isset($_REQUEST['sk'])? $_REQUEST['sk']:NULL;
        $data['name'] = isset($_REQUEST['_'])? $_REQUEST['_']:NULL;
        $title = $aWebbackend->getPageTitle($pageName, $data); 
        
        echo $title;
        
        $description = $aWebbackend->getPageMetadata($pageName,"desc",$data);
     ?>
</title>

<meta charset="utf-8">
<meta name="viewport"    content="width=device-width, initial-scale=1.0">
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
<meta name="description" content="<?php echo $description; ?>">
<meta name="keywords" content="<?php echo $aWebbackend->getPageMetadata($pageName,"keywords",$data); ?>">
<meta name="apple-itunes-app" content="app-id=1361367210">
<meta name="google-play-app" content="app-id=com.sneidon.ts.dictionary">

<meta property="og:title"            content="<?php echo $title; ?>">
<meta property="og:description"      content="<?php echo $description; ?>">

<meta property="og:image"            content="<?php echo $aWebbackend->getPageUnsecureFacebookImageURL($pageName,$data); ?>">
<meta property="og:image:secure_url" content="<?php echo $aWebbackend->getPageSecureFacebookImageURL($pageName,$data); ?>">
<meta property="og:image:type"       content="image/jpg">
<meta property="og:image:width"      content="500">
<meta property="og:image:height"     content="200">

<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?php echo $title; ?>" />
<meta name="twitter:description" content="<?php echo $description; ?>" />