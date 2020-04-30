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
        
        <title>403 &HorizontalLine; ClientZone</title>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner" style ="border: none">
                <div class="container">
                    <a class="brand" href="dashboard/"><img src ="assets/images/icons/clientzone.png"/></a>
                </div>
            </div>
	</div>

        <div class="ts-main-content">
            <div class="wrapper" style ="background: #293A4A">
                <div class="container">
                    <div class="row">
                        <h1 style ="text-align: center;color:#F2F2F2">403</h1>
                        <h4 style ="text-align: center;color:#DBDBDB">ACCESS TO COMPONENT DENIED</h4>
                        <br/><br/>
                        <p style ="text-align: center;color:#F2F2F2">Looks like you don't have access to the page you just tried to access.</p>
                        <p style ="text-align: center;color:#F2F2F2">Please check the URL and try your luck again.</p>
                        <br/><br/>
                        <h4 style ="text-align: center;color:#DBDBDB"><a href = "dashboard/" class="btn btn-warning">Back Home</a></h4>
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
    </body>
</html>

