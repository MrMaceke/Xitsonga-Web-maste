<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"clients");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../error403");
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
        
        <title>Clients &HorizontalLine; ClientZone</title>
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
        <script src="scripts/documentCreatorBean.js"></script>
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
                                <div class ="module-head"><h3>Client Console</h3></div>
                                <div class="module-body" ng-controller="systemRolesController">
                                    <?php require_once './components/menu/clients_top_menu.php';?>
                                    <?php require_once './components/dialogs/addNewClient.php';?>
                                    <br/>
                                </div>
                            </div>
                            <div class="module" style="min-height: 600px">
                                <div class ="module-head"><h3>Waxbill Clients</h3></div>
                                <div class="module-body" ng-controller="clientsController" ng-init="init()" ng-cloak>
                                    <div ng-show="showLoader">
                                        <div style="height: 400px"></div>
                                    </div>
                                    <br/>
                                    <table id = 'clientsTable' class="display table table-bordered" cellspacing="0" width="100%">
                                        <tbody></tbody>
                                    </table>
                                    <br/>
                                    <div ng-if="clients.status === -999" ng-cloak>
                                        <div class="alert alert-dismissible alert-warning">
                                            {{ clients.message }}
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
            $(".addClientDialog").hide();

            function addNewClient(ev) {
                ev.preventDefault();
                
                var userDetails  = {};
                var vCount = 0;
                $(".addClientDialog .userDetails").each(function(e){
                    userDetails[vCount] = {
                        "propertyId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                var aJson = {
                    "email"         : $(".addClientDialog #emaillAddress").val(),
                    "systemRole"    : $(".addClientDialog #systemRole").val(),
                    "entityType"    : $(".addClientDialog #entityType").val(),
                    "entityTypeName": $(".addClientDialog #entityType").attr('alt'),
                    "userDetails"   : userDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.addNewClient,aJson);
            };
            
            function openAddClientDialog(ev) {
                ev.preventDefault();
                $(".addClientDialog").css('visibility',"visible");
                $(".addClientDialog").slideDown('0.5');
            }
            
            function printProfile(ev){
                var aJson = {
                    "clientId": UTILITY.retrieveClientSelectRowId()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadClientBasicInformation,aJson);
            }
            
            function openProfile(ev) { 
                FEEDBACK.PutHTMLinProcessingState();
                window.location = "client/" + $("#clientId").text();
            }
            
            function openUpdateClientDialog(ev) {
                $(".addClientDialog").slideUp('0.5');
                
                sucess = UTILITY.clientHasSelectedRow();
                if(sucess) {
                    var aJson = {
                        "clientId": UTILITY.retrieveClientSelectRowId()
                    };

                    PROCESSOR.backend_call(CLIENTZONE.function.retrieveClient,aJson);
                } else {
                    noty({
                        id          : "warningId",
                        text        : '<div class="activity-item"> <i class="fa fa-exclamation"></i><div class="activity" style ="">You must selected a client first</div> </div>',
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
            }
            
            function reloadPage(ev) {
                ev.preventDefault();
                
                window.location = "clients/";
            }
            
            function redirectToHelp(ev) {
                window.location = "help/";
            }
            
        </script>

</html>

