<?php
$pageName = 'translate';
require_once 'webBackend.php';

$aWebbackend = new WebBackend();

if (!$aWebbackend->hasAccess($pageName)) {
    
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
        <script type="text/javascript" src ="assets/js/notify.js"></script>
        <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
        <script type="text/javascript" src ="assets/js/notify.js"></script>
        <script type="text/javascript" src ="assets/js/dict.js?n=1"></script>
        <script type="text/javascript" src ="assets/js/jqueryM.js"></script>
        <script>
            $(document).ready(function (e) {
                $(document).on('click', "#startEnglishTranslate", function (event) {
                    $("#xitsongaTranslate").val("");
                    var text = $.trim($("#englishTranslate").val());
                    var language = "english";

                    if (text.length > 1) {
                        var vData = new Array();
                        vData["text"] = text;
                        vData["langauge"] = language;

                        DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.translate, DICT_DATA.translate_json(vData));
                    }
                });

                $(document).on('click', "#startXitsongaTranslate", function (event) {
                    $("#xitsongaTranslate").val("");
                    var text = $.trim($("#englishTranslate").val());
                    var language = "xitsonga";

                    if (text.length > 1) {
                        var vData = new Array();
                        vData["text"] = text;
                        vData["langauge"] = language;

                        DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.translate, DICT_DATA.translate_json(vData));
                    }
                });

                $("#englishTranslate").keypress(function (event) {
                    if (event.which === 13) {
                        event.preventDefault();
                    } else {
                        $("#xitsongaTranslate").val("");
                    }
                });


                $("#xitsongaTranslate").keypress(function (event) {
                    event.preventDefault();
                });
            });
        </script>
    </head>

    <body class="home">

        <?php
        require_once './assets/html/nav.php';
        ?>
        <br/>
        <div class="container">
            <?php
            require_once './assets/html/google_ads.php';
            ?>
            <div class="row" style ="">
                <article class="col-md-9 maincontent">
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>
                                <?php
                                echo "<a href ='kaya'>Home</a> > Translator";
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class ="newBody">
                        <p>
                            Xitsonga Translator is a free service which instantly translates words and phrases between English and Xitsonga.
                        </p>
                    </div>
                    <?php require_once './assets/html/translator_frame.php'; ?>
                    <br/>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>We have some tips for using the service</h4>
                        </div>
                    </div>
                    <br/>
                    <div>
                        <ul class ='dictionary_list2'>
                            <li>Avoid using slang</li>
                            <li>Avoid long phrases</li>
                            <li>Avoid Emojis</li>
                            <li>Use correct spelling</li>
                            <li>Use correct grammar</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>Please give feedback</h4>
                        </div>
                    </div>
                    <br/>
                    <div>
                        We want as much feedback as possible <a>info@xitsonga.org</a>
                    </div>
                    <br/>
                    
                </article>
                <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
                    <?php
                    require_once './assets/html/side_nav_right.php';
                    ?>
                    <hr>
                    <div id="fb-root"></div>
                    <script>(function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=454713284683866";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                    <div class="fb-like" data-href="https://facebook.com/Xitsonga.org/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>            
                </aside>
            </div>
        </div>

        <?php
        require_once './assets/html/footer.php';
        ?>
    </body>
</html>