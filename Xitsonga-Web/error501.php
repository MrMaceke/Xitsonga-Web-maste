<?php
    $pageName = 'overloaded';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
        
    if(!$aWebbackend->hasAccess($pageName)){
        //header('Location: access');
        //exit();
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
        require_once './assets/html/nav_empty.php';
    ?>
    <br/>
    <div class="container">
        
        <div class="row">
         
            <!-- Article main content -->
            <article class="col-md-8 maincontent right marginTablet" style ="margin-left: 0px">
                    <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > 501</h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">System is overloaded.</h4>
                    </div>
                </div>
                <p>
                    It appears we are at maximum capacity and not able accommodate more users at the moment. This error usually goes away if you retry after a few moments. 
                </p>
                <br/>
                <p>
               <b> <h5>Possible solution:</h5></b>
                <ul>
                    <li>Click here to <a href="kaya/">try again.</a></li>
                    <li>Return to website after a few moments</li>
                </ul>
                </p>
                <br/>
            </article>
        </div>
    </div>    
    <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>