<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"resources");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../login/");
        exit();
    }
?>

<!doctype html>
<html ng-app>
    <head>
        <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="ClientZone">
	<meta name="author" content="Waxbill">
        
         <?php require_once 'components/css_loader/main_css.php' ?>
        
        <script src="jquery/angular.min.js"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src="jquery/jquery.noty.packaged.js"></script>
        <script src="jquery/themes/relax.js"></script>
        <script src="assets/js/bootstrap-select.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/Chart.min.js"></script>
	<script src="assets/js/fileinput.js"></script>
	<script src="assets/js/chartData.js"></script>
	<script src="assets/js/main.js"></script>
        <script src="assets/nprogress/nprogress.js"></script>
        
        <script src="scripts/clientZoneBean.js"></script>
  
        <title>Home &HorizontalLine; ClientZone</title>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner" style ="border: none">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse"><i class="icon-reorder shaded"></i></a>
                    <a class="brand" href="resources/"><img src ="assets/images/icons/clientzone.png"/></a>
                    <div class="nav-collapse collapse navbar-inverse-collapse">
                        <ul class="nav pull-right">
                            <li><a href="http://www.waxbill.co.za/">Home</a></li>
                            <li><a href="support/">Support</a></li>
                            <li><a href="logout/">Log-out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
	</div>

        <div class="wrapper" style ="background: #293A4A">
            <div class="container">
                <div class="row">
                    <br/>
                    <div class="module span12" style="background: none;border-color: #293A4A">
                        <div class="module-body"  ng-controller="loggedUserController" data-ng-init="init()">
                            <i style="color:white">Welcome back, <span ng-repeat="detail in client.client.details" ng-if ="detail.typeName === 'First Name'">{{detail.entityDetailContent}}</span></i>
                        </div>
                    </div>
                    <?php
                        $aUserRole = $aAccessBean->retrieveUserRole();
                        if($aUserRole[status]) {
                            if($aUserRole[message] == AccessValidatorBean::ADMIN) {
                                require_once './components/menu/internal_resource_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::BUSINESS) {
                                require_once './components/menu/internal_resource_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::DEVELOPER) {
                                require_once './components/menu/internal_resource_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::CLIENT) {
                                require_once './components/menu/client_resource_menu.php';
                            }else {
                                echo AccessValidatorBean::USER_TYPE_ERROR_MESSAGE; 
                            }
                        }else {
                            echo AccessValidatorBean::DEFAULT_ERROR_MESSAGE;
                        }
                    ?>
                </div>
            </div>
             <br/><br/><br/>
	</div>

	<div class="footer">
            <div class="container" style="padding: 10px">
                <span class="copyright">&copy; <?php echo date("Y");?> <a href ="http://www.waxbill.co.za/">Waxbill</a></span>. All Rights Reserved.
            </div>
	</div>
    </body>
</html>