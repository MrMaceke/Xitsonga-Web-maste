<?php
    $pageName = 'translate';
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
    <script type="text/javascript" src ="assets/js/dict.js?n=1"></script>
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
                                echo "<a href ='kaya'>Home</a> > Translator";
                            ?>
                        </h4>
                    </div>
                </div>
                <div class ="newBody">
                    <p>
                        Xitsonga Translator is a free service which instantly translates words, and phrases between English and Xitsonga.
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
                        <textarea id = "englishTranslate" class="form-control" rows="1" placeholder="Word or phrase" style="padding-top: 20px;padding-bottom: 20px;font-size: 15px;font-weight: 100;color:#00688B;"></textarea>
                    </div>
                    <div class="col-sm-6">
                        <textarea id = "xitsongaTranslate" class="form-control" rows="1" placeholder="Translation" style="padding-top: 20px;padding-bottom: 20px;font-size: 15px;font-weight: 100;color:#CD0000"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <button class='btn btn-action alternativeButton' id = "startEnglishTranslate">English to Xitsonga</button>
   
                        <button class='btn btn-action alternativeButton' id = "startXitsongaTranslate">Xitsonga to English</button>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center" id ="didYouMean">
                        
                    </div>
                </div>
                
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Why are translations grammatically incorrect?</h4>
                    </div>
                </div>
                <br/>
                <div>
                    You will notice the grammar on the translations is often wrong, this is because Xitsonga translate is still under development and this is an alpha version to allow for data collection. 
                </div>
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Please give feedback</h4>
                    </div>
                </div>
                <br/>
                <div>
                    <a>info@xitsonga.org</a>
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