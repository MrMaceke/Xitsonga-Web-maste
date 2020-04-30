<?php
   require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"invoices");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../error403");
        exit();
    }
    
    $client = array("param"=>$_REQUEST['sk'],"pageName"=>"invoice");
    $aResourceAccess = $aAccessBean->hasAccessToInvoice($client);
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
        
        <title>Invoice &HorizontalLine; ClientZone</title>
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
			<div class="content" ng-controller="invoiceController">
                            <div class="module" style ="z-index: 1000" ng-cloak>
                                <div class="module-head"><h3>Payment Invoice</h3></div>
                                <div class="module-body" style="min-height:25px">
                                    <button title="Print Invoice" onclick="printInvoice(event)" type="button" class="btn btn-primary small">Print Invoice</button>
                                </div>
                            </div>
                            <div class="module" style ="z-index: 1000">
                                <div class="module-head"><h3>Summary</h3></div>
                                <div class="module-body" style="min-height: 500px" >
                                    <input type="hidden" value = "{{invoice.paymentId}}" id = "invoiceId"/>
                                    <div ng-show="showLoader">
                                        <div style="height: 500px"></div>
                                    </div>
                                    <div ng-cloak ng-if="invoice.status !== -999">
                                        <div class ='control-group'>
                                            <label class= "control-label"><strong>Client ID</strong></label>
                                            <div class="controls">
                                                <a href ="client/{{invoice.clientId}}">{{invoice.clientId}}</a>
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Client details</strong></label>
                                            <div class="controls">
                                                {{invoice.fullnames}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Email address</strong></label>
                                            <div class="controls">
                                                {{invoice.emailAddress}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class= "control-label"><strong>Project ID</strong></label>
                                            <div class="controls">
                                                <a href ="project/{{invoice.projectId}}">{{invoice.projectId}}</a>
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Invoice</strong></label>
                                            <div class="controls">
                                                {{invoice.paymentId}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Payment Reference</strong></label>
                                            <div class="controls">
                                                {{invoice.reference}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Payment Amount</strong></label>
                                            <div class="controls">
                                                {{invoice.amount}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Payment Description</strong></label>
                                            <div class="controls">
                                                {{invoice.description}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Payment Date</strong></label>
                                            <div class="controls">
                                                {{invoice.paymentDate}}
                                            </div>
                                        </div>
                                        <div class ='control-group'>
                                            <label class="control-label"><strong>Payment Created</strong></label>
                                            <div class="controls">
                                                {{invoice.dateCreated}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body col-md-12" ng-if="invoice.status === -999" ng-cloak>
                                        {{ invoice.message}}<br/><br/>
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
                        </div>
                    </div>
                </div>
            </div>  
        </div>

        <?php require_once './components/footer/main.php'; ?>
        </body>
        <script> 
            function printInvoice(ev){                
                var aJson = {
                    "invoiceId" : $("#invoiceId").val()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadInvoice,aJson);
            }
        </script>
</html>

