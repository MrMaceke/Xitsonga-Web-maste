<?php
    $pageName = 'access';
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
        require_once './assets/html/nav.php';
    ?>
    <br/>
    <div class="container">
        
        <div class="row">
         
            <!-- Article main content -->
            <article class="col-md-6 maincontent right marginTablet" style ="margin-left: 0px">
                    <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > 403</h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Access has been denied</h4>
                    </div>
                </div>
                <p>
                    Oops, this is rather embarrassing. You have been denied access to the resource you are trying open.
                    Don't panic, this happens often due to the below listed.
                </p>
                <br/>
                <p>
                <b><h5>Possible reasons:</h5></b>
                <ul>
                    <li>Your session may have expired</li>
                    <li>Your access may have been revoked</li>
                </ul>
                </p>
                <br/>
                 <p>
                <b><h5>Solutions:</h5></b>
                <ul>
                    <li>Refresh the page</li>
                    <li>Re-login if session has expired.</li>
                </ul>
                </p>
            </article>
        </div>
    </div>    
    <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>