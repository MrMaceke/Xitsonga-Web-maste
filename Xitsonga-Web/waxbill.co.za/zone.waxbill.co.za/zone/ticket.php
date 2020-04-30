<?php
   require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"tickets");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../error403");
        exit();
    }
    
    $client = array("param"=>$_REQUEST['sk'],"pageName"=>"tickets");
    $aResourceAccess = $aAccessBean->hasAccessToTicket($client);
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
        
        <title>Ticket &HorizontalLine; ClientZone</title>
        <script src="assets/js/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src="jquery/jquery.noty.packaged.js"></script>
        <script src="jquery/themes/relax.js"></script>
        <script src="jquery/jquery.steps.js"></script>
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
        <div class="wrapper" ng-controller="accessController">
            <div class="container" ng-cloak>
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
			<div class="content" ng-controller="supportTicketController">
                            <div class="module" style ="z-index: 1000" ng-cloak>
                                <div class="module-head"><h3>Support Ticket</h3></div>
                                <div class="module-body" style="min-height:25px">
                                    <div ng-if="loggedUser.credentials.roleName ==='Developer' || loggedUser.credentials.roleName ==='Consultant' || loggedUser.credentials.roleName ==='Administrator'">
                                        <div ng-if ="supportTicket.status !== 'Completed'">
                                            <button title="Progress Ticket" onclick="progressSupportTicket(event)" type="button" class="btn btn-primary small">Progress Ticket</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="module" style ="z-index: 1000">
                                <div class="module-head"><h3>Summary</h3></div>
                                <div class="module-body" style="min-height: 500px">
                                    <input type="hidden" value = "{{supportTicket.supportId}}" id = "supportId"/>
                                    <div ng-show="showLoader">
                                        <div style="height: 500px"></div>
                                    </div>
                                    <div ng-cloak>
                                        <div class ='control-group'>
                                            <label class= "control-label"><strong>Client ID</strong></label>
                                            <div class="controls">
                                                <a href ="client/{{supportTicket.clientId}}">{{supportTicket.clientId}}</a>
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Client details</strong></label>
                                            <div class="controls">
                                                {{supportTicket.fullnames}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Email address</strong></label>
                                            <div class="controls">
                                                {{supportTicket.emailAddress}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Support Ticket ID</strong></label>
                                            <div class="controls">
                                                {{supportTicket.supportId}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Support Description</strong></label>
                                            <div class="controls">
                                                {{supportTicket.description}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Date Created</strong></label>
                                            <div class="controls">
                                                {{supportTicket.dateCreated}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Due Date</strong></label>
                                            <div class="controls">
                                                {{supportTicket.dueDate}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Status</strong></label>
                                            <div class="controls">
                                                {{supportTicket.status}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="module" style ="z-index: 1000">
                                <div class ="module-head"><h3>Online Support Center</h3></div>
                                <div class ="module-body">
                                    <p>
                                        Waxbill offers 24/7 support in a way that is convenient for you. 
                                        Waxbill Support Team will give you quick and professional support and answer all your questions. 
                                        We provide support via email and phone calls, contact us on <a  href="mailto:info@waxbill.co.za">info@waxbill.co.za</a> or (+27)71 011 2950                                        
                                    </p>
                                </div>
                            </div>
                            <div class="panel-body col-md-12" ng-if="supportTicket.status === -999" ng-cloak>
                                <div>
                                    <div class="alert alert-dismissible alert-warning">
                                        {{ supportTicket.message }}
                                    </div>
                                    <p>Please contact <a target ="_tab" href ="mailto:webmaster@waxbill.co.za">webmaster@waxbill.co.za</a> if the error persists.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>

        <?php require_once './components/footer/main.php'; ?>
        </body>
        <script> 
            function progressSupportTicket(ev){                
                aMessage = "Are you sure you want to progress ticket to next stage?";
                var n = noty({
                    text        : '<div class="activity-item text-dark"> <i class="fa '+ CLIENTZONE.icon.fa_info_circle +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
                    type        : 'notice',
                    dismissQueue: true,
                    theme       : 'relax',
                    layout      : 'center',
                    closeWith   : ['click'],
                    modal       : true,
                    maxVisible  : 10,
                    animation   : {
                        open  : 'animated bounceInLeft',
                        close : 'animated bounceOutLeft',
                        easing: 'swing',
                        speed : 500
                    },
                   buttons: [
                        {
                            addClass: 'btn btn-primary', 
                            text: 'Progress Ticket', 
                            onClick: function ($noty) {
                                var aJson = {
                                    "supportId" : $("#supportId").val()
                                 };

                                PROCESSOR.backend_call(CLIENTZONE.function.progressSupportTicket,aJson);
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
        </script>
</html>

