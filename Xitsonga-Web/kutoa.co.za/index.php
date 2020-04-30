<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Kutoa App</title>
    <?php
        require_once './assets/css_loader/css.php';
    ?>
</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="home/">Kutoa</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#download" class="page-scroll">Download</a>
                    </li>
                    <li>
                        <a href="terms/">Terms</a>
                    </li>
                    <li>
                        <a href="privacy/">Privacy</a>
                    </li>
                    <li>
                        <a href="safety/">Safety</a>
                    </li>
                    <li>
                        <a href="contact/">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header>
        <div class="container">
            <div class="row">
                <div class="col-sm-7">
                    <div class="header-content">
                        <div class="header-content-inner">
                            <h1>Kutoa brings people together to share rides</h1>
                            <a href="#download" class="btn btn-outline btn-xl page-scroll">Download Now for Free!</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="device-container">
                        <div class="device-mockup galaxy_s3 portrait white">
                            <div class="device">
                                <div class="screen">
                                    <img src="img/screen.png" class="img-responsive" alt="">
                                </div>
                                <div class="button">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="download" class="download bg-primary text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="section-heading">Discover what all the buzz is about!</h2>
                    <p>Our app is available on Google Play! Download now to get started!</p>
                    <div class="badges">
                        <a class="badge-link" target = "tab" href="https://play.google.com/store/apps/details?id=za.co.waxbill.app.kutoa"><img src="img/google-play-badge.svg" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2017 <a target="tab" href ="http://www.facebook.com/mukondli">Sneidon</a>. All Rights Reserved.</p>
        </div>
    </footer>
    
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/new-age.min.js"></script>
</body>
</html>
