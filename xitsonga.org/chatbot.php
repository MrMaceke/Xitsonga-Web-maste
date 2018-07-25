<?php
    $pageName = 'chatbot';
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
            $(".btn-action").click(function(e){
                e.preventDefault();
                window.location = "search?sk=" + $("#word").val();
            });
            
            $("#fromTranslate").keypress(function( event ) {
                if ( event.which === 13 ) {
                   event.preventDefault();
                   
                    //$("#toTranslate").val("");
                    var text = $.trim($(this).val());

                    if(text.length > 1) {
                        var vData = {
                            "message":text
                        };

                        DICT_PROCESSOR.backend_call(DICT_CONSTANTS.function.elizaChat, vData);
                    }
                }
               
            });
            
            $("#toTranslate").keypress(function(e){
                e.preventDefault();
                
            });
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>

    <div class="container">

    	<br/>
        <div class="row" style ="">
            <article class="col-md-9 maincontent">
                <div class="row">
                    <div class ='new_heading'>
                        <h4>
                            <?php
                                echo "<a href ='kaya'>Home</a> > ChatBot";
                            ?>
                        </h4>
                    </div>
                </div>
                <div class ="newBody">
                    <p>
                        Rivoningo is a free live Xitsonga ChatBox based Eliza.
                    </p>
                </div>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>
                            <?php
                                echo "Rivoningo <span style ='color:red'>ChatBot</span>";
                            ?>
                        </h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-12">
                        <textarea id = "toTranslate" class="form-control" rows="5" placeholder="" style="padding-top: 20px;padding-bottom: 20px;font-size: 20px;font-weight: bold;color:#CD0000" disabled="disabled"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <textarea id = "fromTranslate" class="form-control" rows="1" placeholder="Type Message" style="padding-top: 20px;padding-bottom: 20px;font-size: 20px;font-weight: bold;color:#00688B"></textarea>
                    </div>
                    
                </div>
                
                <div class="row">
                    <div class="text-center" id ="didYouMean">
                        
                    </div>
                </div>
                
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>What is this?</h4>
                    </div>
                </div>
                <br/>
                <div>
                    Rivoningo the ChatBox is still under development and this is a beta version to allow for data collection. 
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