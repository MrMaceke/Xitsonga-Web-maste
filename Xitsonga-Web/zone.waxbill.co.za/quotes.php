<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"internal");
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
        
        <title>External Quotes &HorizontalLine; ClientZone</title>
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
        <script src="scripts/financeBean.js"></script>
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
			<div class="content">
                            <div class="module" style ="z-index: 1000" ng-cloak>
                                <div class="module-head"><h3>External Quotes</h3></div>
                                <div class="module-body" style="min-height:25px">
                                    <div>
                                        <button title="Add Quote" onclick="openAddNewQuoteDialog(event)" type="button" class="btn btn-primary btn-small">Add Quote</button>
                                        <button title="Reassign Quote" onclick="openReassignQuoteDialog(event)" type="button" class="btn btn-primary btn-small">Reassign Selected Quote</button>
                                        <button title="Download Quote" onclick="downloadQuotePDF(event)" type="button" class="btn btn-success btn-small">Download Selected Quote</button>
                                    </div>
                                    <?php require_once './components/dialogs/addNewExternalQuote.php';?>
                                </div>
                            </div>
                            <div class="module">
                                <div class="module-head"><h3>Quotes</h3></div>
                                <div class="module-body" style="min-height: 600px">
                                    <div ng-controller="quotesController">
                                        <div ng-show="showLoader">
                                            <div style="height: 400px"></div>
                                        </div>
                                        <br/>
                                        <table id = 'quotesTable' class="table table-bordered" cellspacing="0" width="100%">
                                            <tbody></tbody>
                                        </table>
                                        <div ng-if="quotes.status === -999" ng-cloak>
                                                {{ quotes.message }}
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
    <script> 
        $(".addNewQuoteDialog").hide();
        $(".updateDealDialog").hide();
        
        $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
        
        function openReassignQuoteDialog(ev) {
            ev.preventDefault();

           sucess = UTILITY.addSelectQuoteForm();
           if(sucess) {
                aMessage = "<h4>Please note the following</h4><br/> - Action will ressign selected quote to Project<br/>- Action will replace existing quote in Project.<br/>- <span style ='color:red'>Action cannot be undone</span><hr/><b>Project ID </b> <input id ='reassignProjectId'/>";
                var n = noty({
                    text        : '<div class="activity" style ="text-align:left">' + aMessage +' </div>',
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
                            text: 'Reassign Quote', 
                            onClick: function ($noty) {
                               var aJson = {
                                    "quoteNumber": UTILITY.retrieveQuoteSelectRowQuoteNumber(),
                                    "projectId"  : $("#reassignProjectId").val()
                                };

                                PROCESSOR.backend_call(CLIENTZONE.function.reassignQuoteToProject,aJson);
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

            $(".addNewQuoteDialog").slideUp('0.5');
        }

        
        function downloadQuotePDF(ev) {
            ev.preventDefault();

            sucess = UTILITY.addSelectQuoteForm();

            if(sucess) {
                var aJson = {
                    "quoteNumber": UTILITY.retrieveQuoteSelectRowQuoteNumber()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadExternalQuotePDF,aJson);
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

            $(".addNewQuoteDialog").slideUp('0.5');
        }
        
        function generateQuote(ev){
            var aDetails  = {};
            var vCount = 0;
            $(".addNewQuoteDialog .deals_details").each(function(e){
                aDetails[vCount] = {
                    "dealCode": $(this).val()
                };
                vCount ++;
            });
            
            var aJson = {
                "firstName": $(".addNewQuoteDialog #firstName").val(),
                "lastName": $(".addNewQuoteDialog #lastName").val(),
                "email": $(".addNewQuoteDialog #emailAddress").val(),
                "phoneNumber": $(".addNewQuoteDialog #phoneNumber").val(),
                "dealCodes" : aDetails
            };

            FINANCE_PROCESSOR.backend_call(FINANCE_BEAN.function.generateExternalQuoteAndDownload,aJson);
        }

        
        function addDatePicker(){
            $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
            $('.datePicker').datepicker('show');
        }
        function openAddNewQuoteDialog(ev){
            $(".addNewQuoteDialog").css('visibility',"visible");
            $(".updateQuoteDialog").slideUp('0.5');
            
            $(".addNewQuoteDialog").slideDown('0.5');
        }
        
        function reloadPage(ev) {
            window.location = window.location;
        }
    </script>
</html>

