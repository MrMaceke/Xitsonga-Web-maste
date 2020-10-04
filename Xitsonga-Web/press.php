<?php
$pageName = 'press';
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
    </head>

    <body class="home">
        <?php
        require_once './assets/html/nav.php';
        ?>

        <!-- container -->
        <div class="container">
            <br/>
            <div class ="row">
                <article class="col-md-9 maincontent right app_div marginTablet"  style ="margin-top:5px;margin-right: 5px;margin-left: 0px">
                    <div class="row">    
                        <div class="new_heading">
                            <h4><a href ='kaya'>Home</a> > Press</h4>
                        </div>
                    </div>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Press</h4>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>Interviews</h4>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class ="col-sm-6">
                            <table width ="100%">
                                <tr>
                                    <td>
                                        <img src ="assets/images/sabc2.png" class="pull-left featured-large-news"/>

                                        <a target="_tab" href ="https://www.youtube.com/watch?v=4vgXlbSqs7w&t=542s">Ngula ya Vutivi: Ririmi ra Xitsonga</a>
                                        <p style ="font-size: 14px">
                                            Xitsonga.org was featured in a conversation about Xitsonga.
                                        </p>
                                        <span style= "color:gray;font-size:14px">September 30, 2020</span><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class ="col-sm-6">
                            <table width ="100%">
                                <tr>
                                    <td>
                                        <img src ="assets/images/metrofm.png" class="pull-left featured-large-news"/>

                                        <a target="_tab" href ="https://iono.fm/e/925027">Metro FM: Morning Flavour</a>
                                        <p style ="font-size: 14px">
                                            Xitsonga Dictionary for Android hitting a 100K downloads on Android.
                                        </p>
                                        <span style= "color:gray;font-size:14px">September 14, 2020</span><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <hr/>
                        <div class ="col-sm-6">
                            <table width ="100%">
                                <tr>
                                    <td>
                                        <img src ="assets/images/702.png" class="pull-left featured-large-news"/>

                                        <a target="_tab" href ="https://www.702.co.za/podcasts/415/afternoon-drive-with-john-perlman/359587/tsonga-dictionary-app?fbclid=IwAR0ybXMC7UQYxnfBKa-mKOx7-8_EItfRI2B_cGXjlAMc3zkFrBU0FZv8tYM">Radio 702: Afternoon drive</a>
                                        <p style ="font-size: 14px">
                                            Xitsonga Dictionary for Android hitting a 100K downloads on Android.
                                        </p>
                                        <span style= "color:gray;font-size:14px">September 10, 2020</span><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class ="col-sm-6">
                            <table width ="100%">
                                <tr>
                                    <td>
                                        <img src ="assets/images/powerfm.png" class="pull-left featured-large-news"/>

                                        <a target="_tab" href ="https://twitter.com/Powerfm987/status/1231110651046699008">Power FM: WeekendBreakfast</a>
                                        <p style ="font-size: 14px">
                                            Hlawuleka and Hlulani unpacking the history and different dialects of Xitsonga.
                                        </p>
                                        <span style= "color:gray;font-size:14px">February 22, 2020</span><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>Articles</h4>
                        </div>
                    </div>
                    <br/>
                     <div class="row">
                        <div class ="col-sm-6">
                            <table width ="100%">
                                <tr>
                                    <td>
                                        <img src ="assets/images/twinkl.png" class="pull-left featured-large-news"/>

                                        <a target="_tab" href ="https://www.twinkl.co.za/blog/10-ways-to-start-learning-south-african-languages">Twinkl.co.za</a>
                                        <p style ="font-size: 14px">
                                            Xitsonga.org is featured on <i><a target="_tab" href ="https://www.twinkl.co.za/blog/10-ways-to-start-learning-south-african-languages">10 Ways to Start Learning South African Languages.</a></i>
                                        </p>
                                        <span style= "color:gray;font-size:14px">September 24, 2020</span><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                         <div class ="col-sm-6">
                            <table width ="100%">
                                <tr>
                                    <td>
                                        <img src ="assets/images/rekord.png" class="pull-left featured-large-news"/>

                                        <a target="_tab" href ="https://rekordnorth.co.za/81578/former-up-student-creates-dictionary/">Pretoria Rekord North</a>
                                        <p style ="font-size: 14px">
                                            Xitsonga.org is featured on <i><a target="_tab" href ="https://rekordnorth.co.za/81578/former-up-student-creates-dictionary/">Former UP student creates dictionary.</a></i>
                                        </p>
                                        <span style= "color:gray;font-size:14px">July 21, 2016</span><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                     </div>
                     <br/>
                    <div class="row">
                        <div class ='new_heading'>
                            <h4>Get in touch</h4>
                        </div>
                    </div>
                    <br/>
                     <div style ="font-size: 14px">
                        Speak to us <a href="mailto:info@xitsonga.org">info@xitsonga.org</a>
                    </div>
                    <br/>
                </article>
                <aside class="col-md-4 sidebar sidebar-right marginRightTablet fillWebsite">
                    <?php
                    require_once './assets/html/side_nav_right.php';
                    ?>
                </aside>
            </div>
        </div>
        <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
        ?>
    </body>
</html>