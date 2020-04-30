<?php
    $pageName = 'disclaimer';
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
                        <h4><a href ='kaya'>Home</a> > Disclaimer</h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Website disclaimer</h4>
                    </div>
                </div>
                    <p>
                        The information contained in this website is for general information purposes only. The information is provided by <a href ='about'>Xitsonga.org</a> and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.
                    </p>
                    <hr>
                    <p>
                        In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.
                    </p>
                    <hr>
                    <p>
                        Through this website you are able to link to other websites which are not under the control of <a href ='about'>Xitsonga.org</a>. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.
                    </p>
                    <hr>
                    <p>
                    Every effort is made to keep the website up and running smoothly. However, <a href ='about'>Xitsonga.org</a> takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.
                    </p>
                    <hr>
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