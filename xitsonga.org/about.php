<?php
    $pageName = 'about';
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
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 5px;padding: 15px">
                <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > <a href ='about/'>About</a> > Who we are</h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Our vision</h4>
                    </div>
                </div>
                <p >
                    <a href ='about/'>Xitsonga.org</a>'s mission is to teach people the <a target ="_tab" href ='https://en.wikipedia.org/wiki/Tsonga_language'>Xitsonga language</a> and to provide a bridge between Xitsonga and other languages.
                    People use our platforms to learn Xitsonga and Xitsonga language speakers use our platforms to learn other languages.
                </p>
    
                <p>
                    We are committed to developing tools for people to learn Xitsonga and to publishing resources of quality to encourage and help people from around the world to learn <a target ="_tab" href ='https://en.wikipedia.org/wiki/Tsonga_language'>Xitsonga</a>.
                </p>

                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="founding">Founding</h4>
                    </div>
                </div>
                <p>
                    <a href ='kaya'>Xitsonga.org</a> was founded on the 10th of March 2012 by Mukondli Dumela</a>. 
                    The idea started when Mukondli was unable to find resources on the internet for a <a href ='people/names'>Xitsonga names</a> research project. Since then it became his passion to find resources and publish for millions of people to access freely. 
                </p>
                
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="services">Services</h4>
                    </div>
                </div>
                <div>
                    We are currently not offering any services. We are focusing on developing tools for learning.
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="contacts">Contact Us</h4>
                    </div>
                </div>
                <div>
                    <a>sneidon.dumela@gmail.com</a> / 0710112950
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