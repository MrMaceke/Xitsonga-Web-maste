<?php
    $pageName = 'activate';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
    
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:""; 
    
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
        require_once './assets/html/script_2.php';
    ?>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/user.js"></script>
    <script>
        $(document).ready(function(e){
           
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
    <br/>
    <div class="container">
        <div class="row">
            <article class="col-md-8 maincontent right marginTablet" style ="margin-left: 0px">
       		<br/>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 class="thin text-center">Activate your account</h3>
                            <hr>
                            <?php
                                echo $aWebbackend->activateAccount($sk)
                            ?>
                        </div>
                </div>
                <br/>
            </article>
            <aside class="col-md-4 sidebar sidebar-right" style ="margin-right:none;margin-left: 5px">
                  <div class="row widget">
                    <div class="col-xs-12">
                            <h4>Experiencing problems?</h4>
                            <hr>
                            <p>If you are having problems with activation of your account please <a href ="contact">contact us</a> or visit the <a href="accounts">account management</a> page for more options.</p>
                            <hr>
                    </div>
                      
                </div>
            </aside>
             <aside class="col-md-4 sidebar sidebar-right" style ="margin-right:none;margin-left: 5px;margin-top:5px">
                  <div class="row widget">
                    <div class="col-xs-12">
                            <h4>Security</h4>
                            <hr>
                            <p>We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure. We will be using <a href ='https://en.wikipedia.org/wiki/HTTPS'>HTTPS</a> in a short while.</p>
                            <hr>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>