<?php

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Waxbill" />
        <meta name="description" content="Home of creative solutions" />
        <meta name="keywords" content="" />

        <?php require_once './components/css_loader/main.php'; ?>

        <script src="assets/js/modernizr-2.6.2.min.js"></script>

        <title>Waxbill Nest</title>
    </head>
    <body>
        <div class="fh5co-loader"></div>
        <div id="fh5co-page">
            <?php require_once './components/menu/home_main.php'; ?>
            
            <section id="fh5co-hero" class ='' style="background:#44AD7B;" data-next="yes">
                <div class="fh5co-overlay"></div>
                <div class="container">
                    <div class="fh5co-intro no-js-fullheight">
                        <div class="fh5co-intro-text">
                            <div class="fh5co-center-position">
                                <h3 class="animate-box">Waxbill Nest</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fh5co-learn-more animate-box">
                    <a href="#" class="scroll-btn">
                        <span class="text">Explore Waxbill Nest</span>
                        <span class="arrow"><i class="icon-chevron-down"></i></span>
                    </a>
                </div>
            </section>
            
            <div id="fh5co-about">
                <div class="container">
                    <div class="row text-center row-bottom-padded-md">
                        <div class="col-md-8 col-md-offset-2">
                            <h3 class="">What is Waxbill Nest?</h3>
                            <p class="">
                               Waxbill Nest, also called Waxbill Community is a division of Waxbill which is committed to enhancing the life of Africans in their respective communities through technology.
                               <br/><br/>
                               Waxbill Nest's purpose is to provide software and mobile application solutions to service the needs of various communities across the African continent.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="fh5co-about" style ="margin-top: -100px">
                <h3 class="text-center">What We Do</h3>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="fh5co-person">	
                                <figure class="">
                                    <img src="assets/images/portal.jpg" class="img-responsive" style ="border: 1px solid #EBEBEB">
                                </figure>
                                <h4 class="fh5co-name">Online Portals</h4><br/>
                            </div>
                        </div>
                        
                         <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="fh5co-person">	
                                <figure class="">
                                    <img src="assets/images/shopping.jpg" class="img-responsive" style ="border: 1px solid #EBEBEB">
                                </figure>
                                <h4 class="fh5co-name">Shopping Platforms</h4><br/>
                            </div>
                        </div>
                        
                         <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="fh5co-person">	
                                <figure class="animatebox">
                                    <img src="assets/images/utility.jpg" class="img-responsive">
                                </figure>
                                <h4 class="fh5co-name animatebox">Utility Products</h4><br/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php require_once './components/footer/main.php'; ?>
        </div>
        <?php require_once './components/js_loader/main.php'; ?>
    </body>
</html>