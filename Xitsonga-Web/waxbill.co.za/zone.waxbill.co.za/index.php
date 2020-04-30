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
        <meta name="keywords" content="innovation, africa, software, mobile, apps" />

        <?php require_once './components/css_loader/main.php'; ?>

        <script src="assets/js/modernizr-2.6.2.min.js"></script>

        <title>Waxbill - Home of creative solutions</title>
    </head>
    <body>
        <div class="fh5co-loader"></div>
        <div id="fh5co-page">
            <?php require_once './components/menu/home_main.php'; ?>
            
            <section id="fh5co-hero" class="js-fullheight"  style="background:#3E5265" data-next="yes">
                <div class="fh5co-overlay"></div>
                <div class="container">
                    <div class="fh5co-intro js-fullheight">
                        <div class="fh5co-intro-text">
                            <div class="fh5co-left-position">
                                <h3 class="animate-box">Home of creative solutions</h3>
                                <p class="animate-box"><a href="business/" class="btn btn-outline"><i class="icon-bar-chart"></i> Business Offerings</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fh5co-learn-more animate-box">
                    <a href="#" class="scroll-btn">
                        <span class="text">Explore Waxbill</span>
                        <span class="arrow"><i class="icon-chevron-down"></i></span>
                    </a>
                </div>
            </section>
            
            <section id="fh5co-features">
                <div class="container">
                    <div class="row text-center row-bottom-padded-md">
                        <div class="col-md-8 col-md-offset-2">
                            <h2 class="fh5co">What is Waxbill?</h2>
                            <p class="fh5co-sub-lead">
                                Waxbill Africa, trading as Waxbill, is a Johannesburg based startup company which provides software solutions to service people in Africa.
                            </p>
                        </div>
                    </div>
                     <div class="row text-center row-bottom-padded-md">
                        <div class="col-md-8 col-md-offset-2">
                            <h2 class="fh5co">Explore Solutions</h2>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="fh5co-person">	
                                <figure class="">
                                    <img src="assets/images/main/b_solutions.png" alt="Waxbill Business Solutions" class="img-responsive">
                                </figure>
                                <h3 class="fh5co-name">Corporate Solutions</h3><br/>
                                <p><a href ='business/'>Waxbill Business</a>, a division of Waxbill which develops beautiful custom software and applications for emerging and well-established companies across the African continent.</p>
                            </div>
                        </div>
                        
                         <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="fh5co-person">	
                                <figure class="">
                                    <img src="assets/images/main/c_solutions.png" alt="Waxbill Community Solutions" class="img-responsive">
                                </figure>
                                <h3 class="fh5co-name">Community Solutions</h3><br/>
                                <p><a href ='nest/'>Waxbill Nest</a>, a division of Waxbill which creates software and mobile applications to service the technology needs of small and large communities across the African continent.</p>
                            </div>
                        </div>
                        
                         <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="fh5co-person">	
                                <figure class="animatebox">
                                    <img src="assets/images/main/l_solutions.png" alt="Waxbill Language Solutions" class="img-responsive">
                                </figure>
                                <h3 class="fh5co-name animatebox">Education and Language</h3><br/>
                                <p><a href ='education/'>Waxbill Edu</a>, a division of Waxbill which develops and maintains educational systems and applications, and provides solutions to teach people African languages.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>	
            <section id="fh5co-testimonials">
                <div class="container">
                    <div class="row row-bottom-padded-sm">
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <h2 class="fh5co">Core Values</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center">
                            <div class="flexslider">
                                <ul class="slides">
                                   <li>
                                      <blockquote>
                                        <p>Transforming the education landscape</p>
                                        <p><cite>&mdash; Transformation</cite></p>
                                      </blockquote>
                                   </li>
                                   <li>
                                        <blockquote>
                                        <p>
                                            Clear agreements and transparency
                                        <p><cite>&mdash; Honesty</cite></p>
                                      </blockquote>
                                   </li>
                                   <li>
                                        <blockquote>
                                        <p>
                                            Delivering to customer expectations
                                        <p><cite>&mdash; Integrity</cite></p>
                                      </blockquote>
                                   </li>
                                </ul>
                            </div>
                            <div class="flexslider-controls">
                               <ol class="flex-control-nav">
                                   <li class="animatebox"><img src="assets/images/icons/innovation-icon.png" alt="Transformation"></li>
                                   <li class="animatebox"><img src="assets/images/icons/collaboration.png" alt="Honesty"></li>
                                   <li class="animatebox"><img src="assets/images/icons/icon-customer-experience.png" alt="Integrity"></li>
                               </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php require_once './components/footer/main.php'; ?>
        </div>
        <?php require_once './components/js_loader/main.php'; ?>
    </body>
</html>