<?php
    $pageName = 'index';
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
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/dict.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryM.js"></script>
    <script>
        $(document).ready(function(e){
            $(document).on('click',"#startEnglishTranslate",function( event ) {
                $("#xitsongaTranslate").val("");
                var text = $.trim($("#englishTranslate").val());
                var language = "english";

                if(text.length > 1) {
                    var vData = new Array();
                    vData["text"] = text;
                    vData["langauge"] = language;

                    DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.translate,DICT_DATA.translate_json(vData));
                }
            });
			
            $(document).on('click',"#startXitsongaTranslate",function( event ) {
                $("#xitsongaTranslate").val("");
                var text = $.trim($("#englishTranslate").val());
                var language = "xitsonga";

                if(text.length > 1) {
                    var vData = new Array();
                    vData["text"] = text;
                    vData["langauge"] = language;

                    DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.translate,DICT_DATA.translate_json(vData));
                }
            });

            $("#englishTranslate").keypress(function(event){
                if ( event.which === 13 ) {
                    event.preventDefault();
                } else {
                    $("#xitsongaTranslate").val("");
                }
            });
            
         
            $("#xitsongaTranslate").keypress(function(event){
                event.preventDefault();
            });
         });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>

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
                                echo "<a href ='kaya'>Home</a> > Welcome";
                            ?>
                        </h4>
                    </div>
                </div>
                <div class ="newBody">
                    <p>
                        <a href ='about/'>Xitsonga.org</a> is a platform aimed at teaching people the <a target ="_tab" href ='https://en.wikipedia.org/wiki/Tsonga_language'>Xitsonga language</a> and to provide a bridge between Xitsonga and other languages.
                   
                        People use our platforms to learn Xitsonga and Xitsonga language speakers use our platform to learn other languages.
                    </p>
                </div>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>
                            <?php
                                echo "Xitsonga Translator <span style ='color:red'>Development Edition</span>";
                            ?>
                        </h4>
                    </div>
                </div>
                <br/>
		<div class="row">
                    <div class="col-sm-6">
                        <p>
                        Please <span style ="font-weight:500">click in correct language button</span> for accurate translation
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <textarea id = "englishTranslate" class="form-control" rows="4" placeholder="Word or phrase" style="padding-top: 2px;padding-bottom: 2px;font-size: 15px;font-weight: 100;color:#00688B;"></textarea>
                    </div>
                    <div class="col-sm-6">
                        <textarea id = "xitsongaTranslate" class="form-control" rows="4" placeholder="Translation" style="padding-top: 2px;padding-bottom: 2px;font-size: 15px;font-weight: 100;color:#CD0000"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <button class='btn btn-action alternativeButton' id = "startEnglishTranslate" style="margin-bottom: 5px">English to Xitsonga</button>
   
                        <button class='btn btn-action alternativeButton' id = "startXitsongaTranslate" style="margin-bottom: 5px">Xitsonga to English</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="text-center" id ="didYouMean">
                        
                    </div>
                </div>
                <br/>
                <div>
                    You will notice the grammar on the translations on the translator is often wrong this is because Xitsonga translate is still under development and this is a alpha version to allow for data collection. 
                </div>
                <br/>
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
                <hr>
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=454713284683866";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-like" data-href="https://facebook.com/Xitsonga.org/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>            
            </aside>
        </div>
    </div>

    <?php
        require_once './assets/html/footer.php';
        
    ?>
</body>
</html>