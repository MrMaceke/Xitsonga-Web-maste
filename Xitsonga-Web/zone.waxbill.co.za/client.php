<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"client");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../error403");
        exit();
    }
    
    $client = array("param"=>$_REQUEST['sk'],"pageName"=>"client");
    $aResourceAccess = $aAccessBean->hasAccessToResource($client);
    if($aResourceAccess['status'] == false){
        header("Location:../404");
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
        
        <title>Client &HorizontalLine; ClientZone</title>
        <script src="assets/js/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
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
        <script src="scripts/documentCreatorBean.js"></script>
        <script src="scripts/financeBean.js"></script>
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
                                require_once './components/info_snippets/client_top_bar_info.php';
                                require_once './components/menu/main_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::BUSINESS) {
                                require_once './components/info_snippets/client_top_bar_info.php';
                                require_once './components/menu/consultant_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::DEVELOPER) {
                                require_once './components/info_snippets/client_top_bar_info.php';
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
                    <div class="span9" ng-controller="accessController">
			<div class="content" ng-controller="currentClientController" data-ng-init="init()">
                            <div class="module" ng-cloak>
                                <div class ="module-head"><h3> <span ng-repeat="detail in client.client.details" ng-if ="detail.typeName === 'First Name' || detail.typeName === 'Last Name'" ng-cloak>{{detail.entityDetailContent}}&nbsp;</span></h3></div>
                                <div class="module-body" ng-cloak style="min-height: 30px">
                                    <div ng-if="loggedUser.credentials.roleName ==='Consultant' || loggedUser.credentials.roleName ==='Administrator'" ng-cloak>
                                        <?php require_once './components/menu/client_top_menu.php';?>
                                        <br/>
                                    </div>
                                    
                                    <?php require_once './components/dialogs/updateClientBasic.php';?>
                                    <?php require_once './components/dialogs/updateClientAddress.php';?>
                                </div>
                            </div>
                            
                            <div class="module" ng-cloak>
                                <div class="module-body" ng-cloak style="min-height: 30px">
                                    <div ng-if="loggedUser.credentials.roleName ==='Consultant' || loggedUser.credentials.roleName ==='Administrator'" ng-cloak>
                                        <button title="Add Project" onclick="openAddProjectDialog(event)" type="button" class="btn btn-primary small">Add Project</button>
                                        <button title="Add Ticket" onclick="openAddTicketDialog(event)" type="button" class="btn btn-primary small">Add Ticket</button>
                                        <button title="Add Invoice" onclick="openAddPaymentDialog(event)" type="button" class="btn btn-primary small">Add Payment</button>
                                        <br/>
                                    </div>
                                    <div ng-if="loggedUser.credentials.roleName ==='Client'" ng-cloak>
                                        <button title="Print Profile" onclick="printProfile(event)" type="button" class="btn btn-success small">Print Profile</button>
                                        <button title="Print Credentials" onclick="printCredentials(event)" type="button" class="btn btn-success small">Print Credentials</button>
                                        <br/>
                                    </div>
                                    <?php require_once './components/dialogs/addNewProject.php';?>
                                    <?php require_once './components/dialogs/addSystemTicket.php';?>
                                    <?php require_once './components/dialogs/addPayment.php';?>
                                </div>
                            </div>

                            <?php require_once './components/info_snippets/client_basic_info.php';?>
  
                           <div class="module" ng-if="client.status === -999">    
                                <div class="module-body">
                                    <div>
                                        <div class="alert alert-dismissible alert-warning">
                                            {{ client.message }}
                                        </div>
                                        <p>Please contact <a target ="_tab" href ="mailto:webmaster@waxbill.co.za">webmaster@waxbill.co.za</a> if the error persists.</p>
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
            $(".addTicketDialog").hide();
            $(".addPaymentDialog").hide();
            
            $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
             
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
            
            function printCredentials(ev) {
                var aJson = {
                    "clientId": $(".addProjectDialog #clientID").text()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadClientCredentialsPack,aJson);
            }
            
            function printProfile(ev){
                var aJson = {
                    "clientId": $(".addProjectDialog #clientID").text()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadClientBasicInformation,aJson);
            }
            
            
            function addNewTicket(ev) {
                var aJson = {
                    "clientId"          : $(".addTicketDialog #clientId").val(),
                    "projectId"         : $(".addTicketDialog #projectId").val(),
                    "ticketDescription" : $(".addTicketDialog #ticketDescription").val(),
                    "dueDate"           : $(".addTicketDialog #dueDate").val()
                };
                
                PROCESSOR.backend_call(CLIENTZONE.function.addNewTicket,aJson);
            }
            
            function addNewPayment(ev) {
                var aJson = {
                    "clientId"          : $(".addPaymentDialog #clientId").val(),
                    "projectId"         : $(".addPaymentDialog #projectId").val(),
                    "paymentReference"  : $(".addPaymentDialog #paymentReference").val(),
                    "paymentAmount"     : $(".addPaymentDialog #paymentAmount").val(),
                    "paymentDate"       : $(".addPaymentDialog #paymentDate").val()
                };
                
                FINANCE_PROCESSOR.backend_call(FINANCE_BEAN.function.addNewPayment,aJson);
            }
            
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
                $(".addTicketDialog").slideUp('0.5');
                $(".addPaymentDialog").slideUp('0.5');
                
                $(".addProjectDialog").css('visibility',"visible");
                $(".addProjectDialog").slideDown('0.5');
            }
            
            function openAddTicketDialog(ev){
                $(".updateClientBasicDialog").slideUp('0.5');
                $(".updateClientAddressDialog").slideUp('0.5');
                $(".addProjectDialog").slideUp('0.5');
                $(".addPaymentDialog").slideUp('0.5');
                
                $(".addTicketDialog").css('visibility',"visible");
                $(".addTicketDialog").slideDown('0.5');
            }
            
            function openAddPaymentDialog(ev){
                $(".updateClientBasicDialog").slideUp('0.5');
                $(".updateClientAddressDialog").slideUp('0.5');
                $(".addProjectDialog").slideUp('0.5');
                $(".addTicketDialog").slideUp('0.5');
                
                $(".addPaymentDialog").css('visibility',"visible");
                $(".addPaymentDialog").slideDown('0.5');
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
                            text: 'Personal', 
                            onClick: function ($noty) {
                                $(".updateClientAddressDialog").slideUp('0.5');
                                $(".addProjectDialog").slideUp('0.5');
                                $(".addTicketDialog").slideUp('0.5');
                                $(".addPaymentDialog").slideUp('0.5');
                                 
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
                                $(".addTicketDialog").slideUp('0.5');
                               
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

