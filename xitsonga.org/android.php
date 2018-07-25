<?php
    $pageName = 'android';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
        
    if(!$aWebbackend->hasAccess($pageName)){
        
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
</head>

<body class="home">
    <?php
        require_once './assets/html/nav.php';
    ?>
    <!-- container -->
        <div class="container app">
            <br/>
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 0px">
                <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > <a href ='appstore/'>Apps</a> </h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Store</h4>
                    </div>
                </div>
                <p style ="font-size: 14px">
                    Our platform is used by varying people for many different reasons. 
                    People use both laptop and mobile phones to access our content. 
                    The applications below are all free for anyone to use.
                    Remember to read our legal and privacy policy.
                </P>
                <hr>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Product Offering</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/AppIcon.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a target = "_tab" href ="https://play.google.com/store/apps/details?id=com.sneidon.ts.dictionary">Xitsonga Dictionary</a>
                                     <p style ="font-size: 14px">
                                        A free Android offline dictionary with thousands of Xitsonga words.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/AppIcon.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a target = "_tab" href ="https://itunes.apple.com/app/id1361367210">Xitsonga Dictionary</a>
                                     <p style ="font-size: 14px">
                                        A free iOS offline dictionary with thousands of Xitsonga words.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/puzzle_new.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a target = "_tab" href ="https://play.google.com/store/apps/details?id=com.sneidon.ts.wordsearch">Xitsonga WordSearch</a>
                                     <p style ="font-size: 14px">
                                          A free Android puzzle game aimed at helping people learn the Xitsonga language.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/puzzle_new.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a target = "_tab" href ="https://itunes.apple.com/app/id1406013381">Xitsonga WordSearch</a>
                                     <p style ="font-size: 14px">
                                          A free iOS puzzle game aimed at helping people learn the Xitsonga language.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/kidsapp.png" width ="100" class="pull-left"/>
                                    <a target = "_tab" href ="https://play.google.com/store/apps/details?id=com.sneidon.ts.kids">Xitsonga For Kids</a>
                                     <p style ="font-size: 14px">
                                        A free Android application aimed at helping kids learn Xitsonga.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/AppIcon.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href ="kaya">Xitsonga Online Dictionary</a>
                                     <p style ="font-size: 14px">
                                          A free online dictionary with with thousands of Xitsonga words.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                   
                </div>
                 <br/>
                <div class="row">
                    
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/dev.png" width ="100" class="pull-left"/>
                                    <a href ="rest-api/">Xitsonga Dictionary API</a>
                                     <p style ="font-size: 14px">
                                        Simple RESTful API for Xitsonga word translations.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Services</h4>
                    </div>
                </div>
                <br/>
                <div>
                    We are currently not offering any services. We are focusing on developing tools for learning.
                </div>
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Contact Us</h4>
                    </div>
                </div>
                <br/>
                <div>
                    <a>sneidon.dumela@gmail.com</a> / 0710112950
                </div>
                <br/><br/>
            </article>
             <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
                <?php
                    require_once './assets/html/side_nav_right.php';
                ?>
            </aside>
     </div>
    <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>