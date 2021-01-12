<?php
$pageName = 'services';
require_once 'webBackend.php';

$sk = isset($_REQUEST['sk']) ? $_REQUEST['sk'] : "xitsonga";
$aWebbackend = new WebBackend();

if (!$aWebbackend->hasAccess($pageName)) {
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
        ?>

        <div class="container">
            <br/>
            <div class="row">
                <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 5px;padding: 15px">
                    <?php
                    if (strtolower($sk) == "xitsonga_dictionary_android") {
                        require_once 'assets/html/xitsonga_dictionary_android.php';
                    } else if (strtolower($sk) == "xitsonga_dictionary_ios") {
                        require_once 'assets/html/xitsonga_dictionary_ios.php';
                    }
                    /*
                    else if (strtolower($sk) == "xitsonga_wordsearch_android") {
                        require_once 'assets/html/xitsonga_wordsearch_android.php';
                    } else if (strtolower($sk) == "xitsonga_wordsearch_ios") {
                        require_once 'assets/html/xitsonga_wordsearch_ios.php';
                    } else if (strtolower($sk) == "xitsonga_kids_android") {
                        require_once 'assets/html/xitsonga_kids_android.php';
                    } 
                    */
                    else if (strtolower($sk) == "xitsonga_whatsapp") {
                        require_once 'assets/html/xitsonga_whatsapp.php';
                    } else if (strtolower($sk) == "xitsonga_messenger") {
                        require_once 'assets/html/xitsonga_messenger.php';
                    } else if (strtolower($sk) == "xitsonga_chrome_plugin") {
                        require_once 'assets/html/xitsonga_chrome_plugin.php';
                    } else if (strtolower($sk) == "xitsonga_firefox_plugin") {
                        require_once 'assets/html/xitsonga_firefox_plugin.php';
                    } else if (strtolower($sk) == "xitsonga_opera_plugin") {
                        require_once 'assets/html/xitsonga_opera_plugin.php';
                    } else if (strtolower($sk) == "xitsonga_api") {
                        require_once 'assets/html/xitsonga_api.php';
                    } else if (strtolower($sk) == "xitsonga_online") {
                        require_once 'assets/html/xitsonga_online.php';
                    } else if (strtolower($sk) == "xitsonga_sms") {
                        require_once 'assets/html/xitsonga_sms.php';
                    }else {
                        ?>
                        <div class="row">    
                            <div class="new_heading">
                                <h4><a href ='kaya'>Home</a> > 404</h4>
                            </div>
                        </div>
                        <br/>
                        <p>
                            We are unable to find <b><?php echo $sk; ?></b> in our platform<ul><li>We may have removed or updated the content because it was incorrect.</li><li>We may have moved the page to a different sub domain.</li><li>We may have temporarily suspended the content.</li></ul>
                        </p>
                        <?php
                    }
                    ?>
                </article>
                <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
                    <div>
                        <div class ='new_heading' style ="margin-left: -15px;margin-top:-10px;margin-right: -15px;margin-bottom: 10px">
                            <h4>Products/Services</h4>    
                        </div>    
                        <ul class ='dictionary_list2'>
                            <li class =''><a href ='service?sk=Xitsonga_Online'>Dictionary for Web</a></li>
                            <li class =''><a href ='service?sk=Xitsonga_Dictionary_Android'>Dictionary for Android</a></li>
                           <!--
                            <li class =''><a href ='service?sk=Xitsonga_Dictionary_iOS'>Dictionary for iOS</a></li>
                            <li class =''><a href ='service?sk=Xitsonga_WordSearch_Android'>WordSearch for Android</a></li>
                            <li class =''><a href ='service?sk=Xitsonga_WordSearch_iOS'>WordSearch for iOS</a></li>
                            <li class =''><a href ='service?sk=Xitsonga_Kids_Android'>Kids for Android</a></li>
                            -->
                            <li class =''><a href ='service?sk=Xitsonga_WhatsApp'>WhatsApp Bot</a></li> 
                            <li class =''><a href ='service?sk=Xitsonga_Messenger'>FB Messenger Bot</a></li> 
                            <li class =''><a href ='service?sk=Xitsonga_SMS'>SMS Bot</a></li> 
                            <li class =''><a href ='service?sk=Xitsonga_Chrome_Plugin'>Chrome Plugin</a></li> 
                            <li class =''><a href ='service?sk=Xitsonga_Firefox_Plugin'>Firefox Plugin</a></li> 
                            <li class =''><a href ='service?sk=Xitsonga_Opera_Plugin'>Opera Plugin</a></li> 
                            <li class =''><a href ='service?sk=Xitsonga_API'>REST API</a></li> 
                        </ul>
                        <div class ='new_heading' style ="margin-left: -15px;margin-top:-10px;margin-right: -15px;margin-bottom: 10px">
                        </div>    
                        <p style ="font-size: 14px">Download > <a target = '_tab' href ='https://play.google.com/store/apps/details?id=com.sneidon.ts.dictionary'>Android</a> & <a target = '_tab' href = 'https://itunes.apple.com/app/id1361367210'>iOS</a></p>
                    </div>
                </aside>
            </div>
        </div>    
        <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
        ?>
    </body>
</html>