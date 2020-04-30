<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"index");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:resources/");
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
  
        <title>Login &HorizontalLine; ClientZone</title>
        <script>
            $(document).ready(function(e){
                $(".loginButton").click(function(e){
                    e.preventDefault();
                    
                    var aJson = {
                        "systemUserID": $("#clientID").val(),
                        "systemUserPassword": $("#password").val()
                    };
                
                    PROCESSOR.backend_call(CLIENTZONE.function.loginSystemUser,aJson);
                });
            });
        </script>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner" style ="border: none">
                <div class="container">
                    <a class="brand" href="login/"><img src ="assets/images/icons/clientzone.png"/></a>
                </div>
            </div>
	</div>

        <div class="wrapper" style ="background: #293A4A">
            <div class="container">
                <div class="row">
                    <div class="module module-login span4 offset4">
                        <form class="form-vertical">
                            <div class="module-head">
                                <h3>Sign In</h3>
                            </div>
                            <div class="module-body">
                                <div class="control-group">
                                    <div class="controls row-fluid">
                                        <input class="span12" type="text" id="clientID" placeholder="Client ID or Email Address">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls row-fluid">
                                        <input class="span12" type="password" id="password" placeholder="Password">
                                    </div>
                                </div>
                            </div>
                            <div class="module-foot">
                                <div class="control-group">
                                    <div class="controls clearfix">
                                        <button type="submit" class="btn btn-primary pull-right loginButton">Login</button>
                                        <label class="checkbox">
                                            <!--<input type="checkbox"> Remember me-->
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
	</div>

	<div class="footer">
            <div class="container" style="padding: 10px">
                <span class="copyright">&copy; <?php echo date("Y");?> <a href ="http://www.waxbill.co.za/">Waxbill</a></span>. All Rights Reserved.
            </div>
	</div>
    </body>
</html>