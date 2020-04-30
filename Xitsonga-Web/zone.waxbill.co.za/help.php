<?php session_start(); ?>

<!doctype html>
<html ng-app>
    <head>
        <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="ClientZone">
	<meta name="author" content="Waxbill">
        
        <?php require_once 'components/css_loader/main_css.php' ?>
        
        <title>Support &HorizontalLine; ClientZone</title>
    </head>
    <body>
        <div>
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="dashboard/"><img src ="assets/images/icons/clientzone.png"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="ts-main-content">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="span3">
                            <div class="sidebar">
                                <ul class="widget widget-menu unstyled">
                                    <li><a href="dashboard/"><i class="menu-icon icon-dashboard"></i>Back to Dashboard </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="span9">
                            <div class="content">
                                <div class="module">
                                    <div class ="module-head"><h3>What is ClientZone?</h3></div>
                                    <div class ="module-body">
                                        <p>
                                            ClientZone is a platform for Waxbill clients to track progress to their projects. It is owned and operated by <a target="_tab" href="http://www.waxbill.co.za">Waxbill</a>, a Johannesburg based startup company which provides software solutions to service people in Africa.
                                        </p>
                                    </div>
                                </div>
                                <div class="module">
                                    <div class ="module-head"><h3>Online Help Center</h3></div>
                                    <div class ="module-body">
                                        <p>
                                            Waxbill offers 24/7 support in a way that is convenient for you. 
                                            Waxbill Support Team will give you quick and professional support and answer all your questions. 
                                            We provide support via email and phone calls, contact us on <a  href="mailto:info@waxbill.co.za">info@waxbill.co.za</a> or (+27)71 011 2950                                        
                                        </p>
                                    </div>
                                </div>
                                <div class="module">
                                    <div class ="module-head"><h3>Privacy Policy</h3></div>
                                    <div class ="module-body">
                                        <p>
                                            Waxbill operates <a  href="http://zone.waxbill.co.za">http://zone.waxbill.co.za</a>. This <a target = "_tab" href ="http://www.waxbill.co.za/data-policy/">page link</a> will inform you of our policies regarding the collection, use and disclosure of Personal Information we receive from users of the Site. We use your Personal Information only for providing and improving the Site.                                    
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php require_once './components/footer/main.php'; ?>
        
        <script src="jquery/angular.min.js"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="jquery/jquery.noty.packaged.js"></script>
        <script src="jquery/themes/relax.js"></script>
        <script src="assets/js/bootstrap-select.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.min.js"></script>
	<script src="assets/js/Chart.min.js"></script>
	<script src="assets/js/fileinput.js"></script>
	<script src="assets/js/chartData.js"></script>
	<script src="assets/js/main.js"></script>
        <script src="assets/nprogress/nprogress.js"></script>
        
        <script src="scripts/clientZoneBean.js"></script>
        <script src="scripts/menuController.js"></script>
        <script>  
            function updateCredentials(ev) {
                ev.preventDefault();
                var aJson = {
                    "currentPassword": $("#currentPassword").val(),
                    "newPassword": $("#newPassword").val(),
                    "confirmPassword": $("#confirmPassword").val()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.updateSystemUserCredentials,aJson);
            };
            
            function reloadPage(ev) {
                window.location = window.location;
            }
        </script>
    </body>
</html>

