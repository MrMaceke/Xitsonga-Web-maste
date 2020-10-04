<?php
$pageName = 'about';
require_once 'webBackend.php';

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
                    <div class="row">    
                        <div class="new_heading">
                            <h4><a href ='kaya'>Home</a> > Xitsonga.org</h4>
                        </div>
                    </div>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">About</h4>
                        </div>
                    </div>
                    <p>
                        <a href ='about/'>Xitsonga.org</a> is non profit project aimed at teaching people and digitizing the <a target ="_tab" href ='https://en.wikipedia.org/wiki/Tsonga_language'>Xitsonga language</a>.
                        We collect Xitsonga content and develop innovative and exciting products to enable learning using digital tools. 
                    </p>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Vision</h4>
                        </div>
                    </div>
                    <p>
                        <a href ='about/'>Xitsonga.org</a>'s vision is to see the  <a target ="_tab" href ='https://en.wikipedia.org/wiki/Tsonga_language'>Xitsonga language</a> freely accessible on practicality all digital tools.
                        We work hard to digitally publish Xitsonga content and to teach people the <a target ="_tab" href ='https://en.wikipedia.org/wiki/Tsonga_language'>Xitsonga language</a>.
                    </p>

                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="founding">Founding</h4>
                        </div>
                    </div>
                    <p>
                        <a href ='kaya'>Xitsonga.org</a> was founded in 2012 by Mukondleteri Dumela</a> with the vision of digitalizing Xitsonga content for people to access freely.
                        We first went live under <b>http://www.gazankuluonline.co.za</b> domain which was renamed a few months later. 
                        We had less than a thousand words at the time but we are able to attract a few thousands of users to the web site within the first two months.
                    </p>
                    <p>
                        Later in 2012 the web site was changed to <b>http://www.tsongaonline.co.za</b> and the web site was given a fresh look. We also launched an installable Windows Version of the dictionary which was a bold step towards reaching more people.
                    </p>
                   
                    <p>
                        In 2015 the web site changed to <a target ="_tab" href = "https://www.xitsonga.org">https://www.xitsonga.org</a> and the web site was yet again given a fresh look. We also added mobile phone support to cater for the tens of thousands of users who were accessing our web site via mobile phones.
                    </p>
                    <p>Between 2016 and now we have build mobiles applications, browser add ons and automated bots to teach people Xitsonga. Our story is that of hard work and team work in the pursuit of a digitized Xitsonga language.</p>

                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="contacts">Contact Us</h4>
                        </div>
                    </div>
                    <div>
                        Speak to us <a href="mailto:info@xitsonga.org">info@xitsonga.org</a>
                    </div>
                </article>
                <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
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