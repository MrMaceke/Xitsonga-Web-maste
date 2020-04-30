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
                        <h4><a href ='kaya'>Home</a> > <a href ='appstore/'>Products</a> </h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Products/Services</h4>
                    </div>
                </div>
                <p style ="font-size: 14px">
                    We have many innovate digital products designed for promoting and learning Xitsonga.
                </P>
           
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Dictionaries</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/AppIcon.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    
                                    <a href ="service?sk=Xitsonga_Dictionary_Android">Dictionary for Android</a>
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
                                    <a href ="service?sk=Xitsonga_Dictionary_iOS">Dictionary for iOS</a>
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
                                    <img src ="assets/images/AppIcon.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href ="service?sk=Xitsonga_Online">Dictionary for Web</a>
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
                    <div class ='new_heading'>
                        <h4>Translator Bots</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/messenger.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href="service?sk=Xitsonga_Messenger">FB Messenger Bot</a>
                                     <p style ="font-size: 14px">
                                       Xitsonga FB Messenger Bot to help you learn and translate between Xitsonga and English.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/whatsapp.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href ="services?sk=Xitsonga_Whatsapp">WhatsAppBot</a>
                                     <p style ="font-size: 14px">
                                        Xitsonga WhatsApp Bot to help you learn and translate between Xitsonga and English.
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
                                    <img src ="assets/images/sms.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href="service?sk=Xitsonga_SMS">SMS Bot</a>
                                     <p style ="font-size: 14px">
                                       Xitsonga SMS Bot to help you learn and translate between Xitsonga and English.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Browser Adds-ons</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/firefox.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href ="service?sk=Xitsonga_Firefox_Plugin">Translator for Firefox</a>
                                     <p style ="font-size: 14px">
                                        A Firefox Browser plug-in that instantly translates highlighted words and phrases to Xitsonga.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/chrome.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href ="service?sk=Xitsonga_Chrome_Plugin">Translator for Chrome</a>
                                     <p style ="font-size: 14px">
                                        A Chrome Browser plug-in that instantly translates highlighted words and phrases to Xitsonga.
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
                                    <img src ="assets/images/opera.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href ="service?sk=Xitsonga_Opera_Plugin">Translator for Opera</a>
                                     <p style ="font-size: 14px">
                                        An Opera Browser plug-in that instantly translates highlighted words and phrases to Xitsonga.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
               <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Games</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/puzzle_new.png" width ="100" class="pull-left" style ='border:1px solid gray'/>
                                    <a href="service?sk=Xitsonga_WordSearch_Android">WordSearch for Android</a>
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
                                    <a href="service?sk=Xitsonga_WordSearch_iOS">WordSearch for iOS</a>
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
                    <div class ='new_heading'>
                        <h4>Kids</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/kidsapp.png" width ="100" class="pull-left"/>
                                    <a href="service?sk=Xitsonga_Kids_Android">Xitsonga For Kids</a>
                                     <p style ="font-size: 14px">
                                        A free Android application aimed at helping kids learn Xitsonga.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                 <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Developers</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                   
                    <div class ="col-sm-6">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/dev.png" width ="100" class="pull-left"/>
                                    <a href ="service?sk=Xitsonga_API">Dictionary API</a>
                                     <p style ="font-size: 14px">
                                        A simple API for Xitsonga word translations.
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
                    Give use feedback or report issues <a>info@xitsonga.org</a>
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