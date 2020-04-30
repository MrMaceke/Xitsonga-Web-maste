<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"account");
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
        
        <title>Account &HorizontalLine;  ClientZone</title>
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
    </head>
    <body>
        <div>
            <?php require_once './components/menu/main_top_menu.php';?>
	</div>
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <?php 
                        $aUserRole = $aAccessBean->retrieveUserRole();
                        // Retrieve side menu
                        if($aUserRole[status]) {
                            if($aUserRole[message] == AccessValidatorBean::ADMIN) {
                                require_once './components/menu/main_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::BUSINESS) {
                                require_once './components/menu/consultant_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::DEVELOPER) {
                                require_once './components/menu/developer_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::CLIENT) {
                                require_once './components/info_snippets/client_top_bar_info.php';
                                require_once './components/menu/client_side_menu.php';
                            }else {
                                echo AccessValidatorBean::USER_TYPE_ERROR_MESSAGE; 
                            }
                        }else {
                            echo AccessValidatorBean::DEFAULT_ERROR_MESSAGE;
                        }
                    ?>
                    <div class="span9">
			<div class="content" ng-controller="systemUserController">
                            <div class="module">
                                <div class="module-head"><h3>Account</h3></div>
                                <div class="module-body" style="min-height: 110px">
                                    <div ng-show="showLoader">
                                        <div style="height: 110px"></div>
                                    </div>
                                    <form method="get" ng-if="systemUser.status === 999">
                                        <div class="control-group" style ="border: none">
                                            <label class="col-lg-4 control-label"><b>Client ID</b></label>
                                            <div class="controls">
                                                <p class="form-control-static text-uppercase" ng-cloak>{{systemUser.credentials.clientID}}</p>
                                            </div>
                                        </div>
                                        <div class="control-group" style ="border: none">
                                            <label class="col-lg-4 control-label"><b>Email</b></label>
                                            <div class="controls">
                                                <p class="form-control-static text-lowercase" ng-cloak>{{systemUser.credentials.email}}</p>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="module">
                                <div class="module-head"><h3>Update credentials</h3></div>
                                <div class="module-body" style="min-height: 350px">
                                    <div ng-show="showLoader">
                                        <div style="height: 285px"></div>
                                    </div>
                                     <form method="get" ng-if="systemUser.status === 999" ng-cloak>
                                            <div class="control-group" style ="border: none">
                                                <label class="col-sm-4 control-label"><b>Current Password</b></label>
                                                <div class="controls">
                                                    <input  id = "currentPassword" type="password" class="form-control span6" placeholder="Enter current password">
                                                </div>
                                            </div>
                                            <div class="control-group" style ="border: none">
                                                <label class="col-sm-4 control-label"><b>New Password</b></label>
                                                <div class="controls">
                                                    <input  id = "newPassword" type="password" class="form-control span6" placeholder="Enter new password">
                                                </div>
                                            </div>
                                            <div class="control-group" style ="border: none">
                                                <label class="control-label"><b>Confirm Password</b></label>
                                                <div class="controls">
                                                    <input id = "confirmPassword" type="password" class="form-control span6" placeholder="Confirm new password">
                                                </div>
                                            </div>
                                            <div class="hr-dashed"></div>
                                            <div class="control-group">
                                                <br/>
                                                <div class="controls">
                                                    <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                                                    <button class="btn btn-primary" type="submit" onclick="updateCredentials(event)">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div ng-if="systemUser.status === -999" ng-cloak>
                                            <div class="alert alert-dismissible alert-danger" ng-cloak>
                                                {{ systemUser.message }}
                                            </div>
                                            <p ng-cloak>Please contact <a target ="_tab" href ="mailto:webmaster@waxbill.co.za">webmaster@waxbill.co.za</a> if the error persists.</p>
                                        </div>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once './components/footer/main.php'; ?>
       </body>
</html>

