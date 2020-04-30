<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"groups");
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
	<meta name="author" content="Client Zone">
        
        <?php require_once 'components/css_loader/main_css.php' ?>
        
        <title>Properties &HorizontalLine; Client Zone</title>
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
        
        <script src="scripts/clientZoneBean.js"></script>
        <script src="scripts/menuController.js"></script>
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
                            <div class="module">
                                <div class ="module-head"><h3>Property Console</h3></div>
                                <div class ="module-body" ng-controller="systemGroupsController" ng-init="init()">
                                    <?php require_once './components/menu/properties_top_menu.php';?>
                                    <?php require_once './components/dialogs/addNewProperty.php';?>
                                    <?php require_once './components/dialogs/updateProperty.php';?>
                                </div>
                            </div>
                             <div class="module">
                                <div class="module-head">
                                    <h3>Health</h3>
                                </div>
                                <div class="module-body">
                                    <div class ="row-fluid">
                                        <ul class="widget widget-usage unstyled span12">
                                            <li>
                                                <p>
                                                    <strong>Mandatory properties</strong> <span class="pull-right small muted">80%</span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar bar-warning" style="width: 80%;"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="module">
                                <div class ="module-head"><h3>System Properties</h3></div>
                                <div class="module-body" ng-controller="systemPropertiesController" ng-init="init()">      
                                    <div ng-show="showLoader">
                                        <div style="height: 400px"></div>
                                    </div>
                                    <br/>
                                    <table id = 'propertiesTable' class="display table table-bordered" cellspacing="0" width="100%">
                                        <tbody></tbody>
                                    </table>
                                    <div ng-if="systemProperties.status === -999">
                                        <div class="alert alert-dismissible alert-danger" ng-cloak>
                                            {{ systemProperties.message }}
                                        </div>
                                        <p>Please contact <a target ="_tab" href ="mailto:webmaster@waxbill.co.za">webmaster@waxbill.co.za</a> if the error persists.</p>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                    <hr>
                    
                </div>
            </div>
        </div>
        <?php require_once './components/footer/main.php'; ?>
    </body>

        
        <script>  
            $(".addPropertyDialog").hide();
            $(".updatePropertyDialog").hide();
            
            function addNewSystemProperty(ev) {
                ev.preventDefault();
                var aJson = {
                    "propertyName": $(".addPropertyDialog #propertyName").val(),
                    "propertyValue": $(".addPropertyDialog #propertyPriority").val(),
                    "propertyGroup": $(".addPropertyDialog #propertyGroup").val(),
                    "propertyDescription": $(".addPropertyDialog #propertyDescription").val()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.addNewSystemProperty,aJson);
            };
            
            function updateSystemProperty(ev) {
                ev.preventDefault();
                var aJson = {
                    "propertyId": $(".updatePropertyDialog #propertyId").val(),
                    "propertyName": $(".updatePropertyDialog #propertyName").val(),
                    "propertyValue": $(".updatePropertyDialog #propertyPriority").val(),
                    "groupId": $(".updatePropertyDialog #propertyGroup").val(),
                    "propertyGroupDescription": $(".updatePropertyDialog #propertyGroup").find("option:selected").text(),
                    "propertyDescription": $(".updatePropertyDialog #propertyDescription").val()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.updateSystemProperty,aJson);
            };
            
            function openDeleteGroupDialog (ev) {
                ev.preventDefault();
                 success = UTILITY.systemPropertyHasSelectedRow();
                
                if(success) {
                    aMessage = "Are you sure you want to delete property?";
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
                            easing: 'swing',
                            speed : 500
                        },
                       buttons: [
                            {
                                addClass: 'btn btn-primary', 
                                text: 'Delete Property', 
                                onClick: function ($noty) {
                                   var aJson = {
                                        "propertyId": UTILITY.retrieveSystemProperySelectRowId()
                                    };

                                    PROCESSOR.backend_call(CLIENTZONE.function.deleteSystemProperty,aJson);
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
                $(".addPropertyDialog").hide();
                $(".updatePropertyDialog").hide();
            };
            
            function reloadPage(ev) {
                ev.preventDefault();
                
                window.location = "properties/";
            }
            
            function openAddPropertyDialog(ev) {
                ev.preventDefault();
                $(".addPropertyDialog").css('visibility',"visible");
                $(".addPropertyDialog").slideDown('0.5');
                
                $(".updatePropertyDialog").hide();
            }
            
            function redirectToHelp(ev) {
                window.location = "help/";
            }
            
            function openUpdatePropertyDialog(ev) {
                ev.preventDefault();
                
                sucess = UTILITY.addSelectPropertyToForm();
                
                if(sucess) {
                    $(".updatePropertyDialog").css('visibility',"visible");
                    $(".updatePropertyDialog").slideDown('0.5');
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
                $(".addPropertyDialog").hide();
            }
        </script>
    
</html>

