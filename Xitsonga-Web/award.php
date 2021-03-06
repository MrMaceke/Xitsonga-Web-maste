<?php
$pageName = 'index';
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
                            <h4><a href ='kaya'>Home</a> > Awards</h4>
                        </div>
                    </div>
                    <div class="rating_div" style ="margin-bottom: -15px">
                        <div class ='desc_heading'>
                            <h4 id ="vision">Best Educational Solution</h4>
                        </div>
                    </div>


                    <p style="text-align:center;"><img  src="assets/images/App.JPG"   height="150" ></p><br><br>
                    <p>

The MTN App of the Year Awards is a champion of app development in South Africa; celebrating local talent and out-of-the-box thinkers that drive disruption and change.</p>

                    <li><a href=""></a>More Details on <a href="https://www.appoftheyear.co.za/portfolio-item/xitsonga-dictionary"/>Mtn App of the year</a></li>
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
