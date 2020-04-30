<?php
    $pageName = 'privacy';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
        
    if(!$aWebbackend->hasAccess($pageName)){
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
            <!-- Article main content -->
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 0px">
                   <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > Privacy</h4>
                    </div>
                    </div>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Data Use</h4>
                        </div>
                    </div>
                    <p>
                        We will require your names and email address upon sign up. We require this information to understand your needs and provide you with a better service.                     
                    </p>
                    <p>
                         We may use the information to improve our products and services. And we may periodically send promotional emails about new products, special offers or other information which we think you may find interesting using the email address which you have provided. 
                    </p>
                     <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Cookie</h4>
                        </div>
                    </div>
                    <p>
                        <a target ="_tab" href = 'https://en.wikipedia.org/wiki/HTTP_cookie'>A cookie is a small file</a> which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences. 
                    </p>
                    
                    <p>
                         We use traffic log cookies to identify which pages are being used. This helps us analyse data about webpage traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. 
                    </p>
                   
                    <p>
                        
                         Overall, cookies help us provide you with a better website by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us. 
                    </p>
                    
                    <p>
                         You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website. 
                    </p>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">External Links</h4>
                        </div>
                    </div>
                    <p>
                        Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.                    
                    </p>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Security</h4>
                        </div>
                    </div>
                    <p>
                        We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure, we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.
                    </p>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Disclaimer</h4>
                        </div>
                    </div>
                    
                    <p>
                        We may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. <br/><br/>This policy is effective from 19 June 2018.
                    </p>
                    <hr>
                    <br/>
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