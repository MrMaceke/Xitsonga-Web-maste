<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"users");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../error403/");
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
        
        <title>System Users &HorizontalLine; ClientZone</title>
        <script src="assets/js/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src="jquery/jquery.noty.packaged.js"></script>
        <script src="jquery/themes/relax.js"></script>
        <script src="assets/js/bootstrap-select.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.dataTables.min.js"></script>
        <script src="jquery/angular.min.js"></script>
        <script src="assets/js/Chart.min.js"></script>
        <script src="assets/js/fileinput.js"></script>
        <script src="assets/js/chartData.js"></script>
        <script src="assets/js/main.js"></script>
        <script src="assets/nprogress/nprogress.js"></script>
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
                                require_once './components/menu/client_side_menu.php';
                            }else {
                                echo AccessValidatorBean::USER_TYPE_ERROR_MESSAGE; 
                            }
                        }else {
                            echo AccessValidatorBean::DEFAULT_ERROR_MESSAGE;
                        }
                    ?>
                    <div class="span9">
			<div class="content">
                            <div class="module" ng-controller="systemRolesController">
                                <div class ="module-head"><h3>Console</h3></div>
                                <div class="module-body" ng-controller="systemPropertiesController">
                                    <?php require_once './components/menu/users_top_menu.php';?>
                                    <?php require_once './components/dialogs/addNewSystemUser.php';?>
                                    <?php require_once './components/dialogs/updateNewSystemUser.php';?>
                                </div>
                            </div>
                            <div ng-controller="systemUsersController" ng-init="init()">
                                <div class ="module">
                                    <div class ="module-head"><h3>Reports</h3></div>
                                    <div class ="module-body" style="min-height: 285px">
                                        <div ng-show="showLoader">
                                            <div style="height: 220px"></div>
                                        </div>
                                        <div class ="row-fluid">
                                            <ul class="widget widget-usage unstyled span6">
                                                <li ng-repeat="userRolesPercetage in userRolesPecentages">
                                                    <p>
                                                        <strong>{{userRolesPercetage.roleName}}</strong> <span class="pull-right small muted">{{userRolesPercetage.percentage}}%</span>
                                                    </p>
                                                    <div class="progress tight">
                                                        <div class="bar bar-{{userRolesPercetage.color}}" style="width: {{userRolesPercetage.percentage}}%;"></div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="module"> 
                                    <div class ="module-head"><h3>Users</h3></div>
                                    <div class="module-body">
                                        <div ng-show="showLoader">
                                            <div style="height: 400px"></div>
                                        </div>
                                        <br/>
                                        <table id = 'usersTable' class="table table-bordered display" style ="width: 100%">
                                            <tbody></tbody>
                                        </table>
                                        <br/>
                                        <div ng-if="systemUsers.status === -999" ng-cloak>
                                            <div class="alert alert-dismissible alert-danger">
                                                {{ systemUsers.message }}
                                            </div>
                                            <p>Please contact <a target ="_tab" href ="mailto:webmaster@waxbill.co.za">webmaster@waxbill.co.za</a> if the error persists.</p>
                                        </div>
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
    <script src="scripts/clientZoneBean.js"></script>
    <script src="scripts/menuController.js"></script>

        <script>  
            $(".addUserDialog").hide();
            $(".updateUserDialog").hide();

            function addNewSystemUser(ev) {
                ev.preventDefault();
                var userDetails  = {};
                var vCount = 0;
                $(".addUserDialog .userDetails").each(function(e){
                    userDetails[vCount] = {
                        "propertyId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });

                var aJson = {
                    "email": $(".addUserDialog #emaillAddress").val(),
                    "systemRole": $(".addUserDialog #systemRole").val(),
                    "entityType"    : $(".addUserDialog #entityType").val(),
                    "entityTypeName": $(".addUserDialog #entityType").attr('alt'),
                    "userDetails"   : userDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.addNewSystemUser,aJson);
            };

            function updateSystemUser(ev) {
                ev.preventDefault();

                var userDetails  = {};
                var vCount = 0;
                $(".updateUserDialog .userDetails").each(function(e){
                    userDetails[vCount] = {
                        "entityDetailId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });

                var aJson = {
                    "userId": $(".updateUserDialog #userId").val(),
                    "email": $(".updateUserDialog #emaillAddress").val(),
                    "systemRole": $(".updateUserDialog #systemRole").val(),
                    "systemRoleName": $(".updateUserDialog #systemRole").find("option:selected").text(),
                    "userDetails"   : userDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.updateSystemUser,aJson);
            };

            function openAddUserDialog(ev) {
                ev.preventDefault();
                $(".addUserDialog").css('visibility',"visible");
                $(".addUserDialog").slideDown('0.5');

                $(".updateUserDialog").hide();
            }

            function openUpdateUserDialog(ev) {
                ev.preventDefault();

                sucess = UTILITY.addSelectUserToForm();

                if(sucess) {
                    var aJson = {
                        "clientId": UTILITY.retrieveSystemUserSelectRowClientId()
                    };

                    PROCESSOR.backend_call(CLIENTZONE.function.retrieveSystemUser,aJson);
                }else {
                    var n = noty({
                        id          : "warningId",
                        text        : '<div class="activity-item"> <i class="fa fa-exclamation"></i><div class="activity" style ="">You must select row first</div> </div>',
                        type        : 'warning',
                        dismissQueue: true,
                        theme       : 'relax',
                        layout      : 'center',
                        closeWith   : ['click', 'backdrop'],
                        modal       : true,
                        maxVisible  : 10,
                        animation   : {
                            open  : 'animated bounceInLeft',
                            easing: 'swing',
                            speed : 500
                        }
                    });
                }

                $(".addUserDialog").hide();
            }

            function openResetPasswordDialog(ev) {
                ev.preventDefault();

               sucess = UTILITY.addSelectUserToForm();
               if(sucess) {
                    aMessage = "Are you sure you want to reset user password?";
                    var n = noty({
                        text        : '<div class="activity-item text-dark"> <i class="fa '+ CLIENTZONE.icon.fa_wrench +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
                        type        : 'notice',
                        dismissQueue: true,
                        theme       : 'relax',
                        layout      : 'center',
                        closeWith   : ['click'],
                        modal       : true,
                        maxVisible  : 10,
                        animation   : {
                            open  : 'animated bounceInLeft',
                            close  : 'animated bounceOutLeft',
                            easing: 'swing',
                            speed : 500
                        },
                       buttons: [
                            {
                                addClass: 'btn btn-primary', 
                                text: 'Reset Password', 
                                onClick: function ($noty) {
                                    var aJson = {
                                        "clientId": UTILITY.retrieveSystemUserSelectRowClientId()
                                    };

                                    PROCESSOR.backend_call(CLIENTZONE.function.resetUserPassword,aJson);
                                }
                            },
                            {
                               addClass: 'btn btn-danger', 
                               text: 'Cancel', 
                               onClick: function ($noty) {
                                    $noty.close();
                               }
                            }
                        ]
                    });
                }else {
                    var n = noty({
                        id          : "warningId",
                        text        : '<div class="activity-item"> <i class="fa fa-exclamation"></i><div class="activity" style ="">You must select row first</div> </div>',
                        type        : 'warning',
                        dismissQueue: true,
                        theme       : 'relax',
                        layout      : 'center',
                        closeWith   : ['click', 'backdrop'],
                        modal       : true,
                        maxVisible  : 10,
                        animation   : {
                            open  : 'animated bounceInLeft',
                            easing: 'swing',
                            speed : 500
                        }
                    });
                }

                $(".addUserDialog").hide();
                $(".updateUserDialog").hide();
            }

            function reloadPage(ev) {
                ev.preventDefault();

                window.location = "users/";
            }

            function redirectToHelp(ev) {
                window.location = "help/";
            }

            function openDeleteUserDialog(ev){
                ev.preventDefault();
                alert("Function not supported");
            }
        </script>
</html>

