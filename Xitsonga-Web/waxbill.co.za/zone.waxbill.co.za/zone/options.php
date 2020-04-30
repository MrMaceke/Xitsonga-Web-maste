<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"options");
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
        
        <title>User Options &HorizontalLine; ClientZone</title>
        <script src="assets/js/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src="jquery/jquery.noty.packaged.js"></script>
        <script src="jquery/themes/relax.js"></script>
        <script src="assets/js/bootstrap-select.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.min.js"></script>
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
			<div class="content" ng-controller="loggedUserController" data-ng-init="init()">
                            <div class="module">
                                <div class ="module-head"><h3> User Options</h3></div>
                            </div>
                            <div ng-cloak>
                                <?php require_once './components/info_snippets/user_basic_info.php';?>
                            </div>
                           <div class="module" ng-if="client.status === -999">    
                                <div class="module-body" ng-cloak>
                                    <div>
                                        <div class="alert alert-dismissible alert-warning">
                                            {{ client.message }}
                                        </div>
                                        <p>Please contact <a target ="_tab" href ="mailto:info@waxbill.co.za">info@waxbill.co.za</a> if the error persists.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once './components/dialogs/clientProfileView.php';?>
            
        </div>
        
        <?php require_once './components/footer/main.php'; ?>
        </body>
                <script> 
            $(".updateClientBasicDialog").hide();
            $(".updateClientAddressDialog").hide();
            $(".addProjectDialog").hide();
            
            function addDatePicker(){
                $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
                $('.datePicker').datepicker('show');
            }
            /*
            var ctx = document.getElementById("projectsGraph").getContext("2d");
            window.myBar = new Chart(ctx).Bar(barChartData, {
                    responsive : true
            });
            */
            
            function addNewProject(ev) {
                ev.preventDefault();
                 
                var projectDetails  = {};
                var vCount = 0;
                $(".addProjectDialog .userDetails").each(function(e){
                    projectDetails[vCount] = {
                        "propertyId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                
                var aJson = {
                    "clientId"      : $(".addProjectDialog #clientID").text(),
                    "entityType"    : $(".addProjectDialog #entityType").val(),
                    "projectStage"  : $(".addProjectDialog #projectStage").val(),
                    "projectDetails"   : projectDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.addNewProject,aJson);
            }
            
            function updateClient(ev) {
                ev.preventDefault();
                
                var userDetails  = {};
                var vCount = 0;
                $(".updateClientBasicDialog .userDetails").each(function(e){
                    userDetails[vCount] = {
                        "entityDetailId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                var aJson = {
                    "email"         : $(".updateClientBasicDialog #emaillAddress").val(),
                    "clientId"      : $(".updateClientBasicDialog #clientID").text(),
                    "userDetails"   : userDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.updateClient,aJson);
            };
            
            function updateAddress(ev) {
                 ev.preventDefault();
                 
                var userDetails  = {};
                var vCount = 0;
                $(".updateClientAddressDialog .userDetails").each(function(e){
                    userDetails[vCount] = {
                        "propertyId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                var aJson = {
                    "clientId"    : $(".updateClientAddressDialog #clientID").text(),
                    "userDetails" : userDetails
                };
                
                PROCESSOR.backend_call(CLIENTZONE.function.updateClientDetails,aJson);
            }
            
            function openAddProjectDialog(ev){
                $(".updateClientBasicDialog").slideUp('0.5');
                $(".updateClientAddressDialog").slideUp('0.5');
                
                $(".addProjectDialog").css('visibility',"visible");
                $(".addProjectDialog").slideDown('0.5');
            }
            
            function openEditClientDialog(ev) {
                ev.preventDefault();
                aMessage = "What would you like to edit?";
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
                            text: 'Basic', 
                            onClick: function ($noty) {
                                $(".updateClientAddressDialog").slideUp('0.5');
                                $(".addProjectDialog").slideUp('0.5');
                                 
                                $(".updateClientBasicDialog").css('visibility',"visible");
                                $(".updateClientBasicDialog").slideDown('0.5');
                                $noty.close();
                            }
                        },
                        {
                           addClass: 'btn btn-primary', 
                           text: 'Address', 
                           onClick: function ($noty) {
                                $(".updateClientBasicDialog").slideUp('0.5');
                                $(".addProjectDialog").slideUp('0.5');
                               
                                $(".updateClientAddressDialog").css('visibility',"visible");
                                $(".updateClientAddressDialog").slideDown('0.5');
                                
                                $noty.close();
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
            }

            function reloadPage(ev) {
                window.location = window.location;
            }
        </script>
</html>

