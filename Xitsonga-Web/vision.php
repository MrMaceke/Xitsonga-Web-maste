<?php
$pageName = 'vision';
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
        <script type="text/javascript">
            $(document).ready(function (e) {
                $("#my-form").on('change', function (e) { // if change form value
                    $("#processingOnDrag").html('Processing');
                    $("#translation").html(""); 
                    $("#my-form").prop('disabled', true);
                    var eventType = $(this).attr("method"); // get method type for #my-form
                    var eventLink = $(this).attr("action"); // get action link for #my-form

                    $.ajax({
                        type: eventType,
                        url: eventLink,
                        data: new FormData(this), // IMPORTANT!
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (getResult) {
                            var response = JSON.parse(getResult);
                            $('#my-form')[0].reset();
                            var message = "";
                            if(response.status === 999) {
                                message = response.infoMessage;
                            } else {
                                message = response.errorMessage;
                            }
                            var link = message.split("**")[0];
                            var tags = message.split("**")[1];
                            var image = "<div style='float:left;width:100px;height:100px;border-radius:5px;background-image: url(" + link + ");background-size: auto 99px; background-repeat: no-repeat;background-color:orange'></div>";
                            $("#translation").html(image + "<div style ='margin:0px;padding:5px'><span style='margin:5px'>" + tags +"</span></div>");
                            $("#processingOnDrag").html('<span style="color:#386895">Drag and drop</span> or <span style="color:#FF6103">Select image</span>');
                        },
                        error: function (getResult) {
                            $('#my-form')[0].reset(); // reset form

                            $("#translation").html(getResult); // display the result in #result element
                            $("#processingOnDrag").html('<span style="color:#386895">Drag and drop</span> or <span style="color:#FF6103">Select image</span>');
                        }

                    });
                    e.preventDefault();
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
                                echo "<a href ='kaya'>Home</a> > Vision";
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class ="newBody">
                        <p>
                            Xitsonga Vision is a free service which instantly identifies pictures in Xitsonga.
                        </p>
                    </div>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>
                                <?php
                                echo "Xitsonga Vision <span style ='color:#386895;font-weight:bold'><i>Alpha</i></span>";
                                ?>
                            </h4>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 5px">
                            <a  href="translator"><img src="assets/images/icons8-chat_room.png" width="20px" id = "translatedImage"/></a>&nbsp;&nbsp;<a href="translator" class='btn' id = "translatedImage" style="margin-bottom: 5px;background: #E9EAED;color: #6E6E6E; border:1px solid #B0B0B0;"><span>Translate text</span></a> <!--<sup style="color:red;font-weight: bold;">* New</sup>-->
                        </div>
                        <div class="col-lg-6">
                            <form id="my-form" method="POST" action="webBackend.php?method=processVision&caller=web" enctype="multipart/form-data">
                                <div id="file-wrap">
                                    <p id="processingOnDrag"><span style="color:#386895">Drag and drop</span> or <span style="color:#FF6103">Select image</span></p>
                                    <input id="my-file" type="file" name="image" draggable="true" accept=".jpg, .png, .gif">
                                </div>
                                <input name="token" type="hidden" value="902418422124">
                            </form>  
                        </div>
                        
                        <div class="col-lg-6">
                            <div id = "translation" class="col-lg-12" style="padding: 0px">

                            </div>
                        </div>
                    </div>

                    <br/>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>We have some tips for using the service</h4>
                        </div>
                    </div>
                    <br/>
                    <div>
                        <ul class ='dictionary_list2'>
                            <li>Avoid using low quality images</li>
                            <li>Avoid using large images</li>
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