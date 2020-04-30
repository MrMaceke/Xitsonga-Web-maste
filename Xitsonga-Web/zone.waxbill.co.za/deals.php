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
        
        <title>Deals &HorizontalLine; ClientZone</title>
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
                                <div class="module-head"><h3>Waxbill Deals</h3></div>
                                <div class="module-body" style="min-height:25px">
                                    <div>
                                        <button title="Add Deal" onclick="openAddNewDealDialog(event)" type="button" class="btn btn-primary btn-small">Add Deal</button>
                                        <button title="Update Deal" onclick="openUpdateDealDialog(event)" type="button" class="btn btn-small btn-primary">Update Selected Deal</button>
                                        <button title="Remove Deal" onclick="openDeleteDealDialog(event)" type="button" class="btn btn-small btn-danger">Remove Selected Deal</button>
                                    </div>
                                    <?php require_once './components/dialogs/addDeal.php';?>
                                    <?php require_once './components/dialogs/updateDeal.php';?>
                                </div>
                            </div>
                            <div class="module">
                                <div class="module-head"><h3>Deals</h3></div>
                                <div class="module-body" style="min-height: 600px">
                                    <div ng-controller="dealsController">
                                        <div ng-show="showLoader">
                                            <div style="height: 400px"></div>
                                        </div>
                                        <br/>
                                        <table id = 'dealsTable' class="table table-bordered" cellspacing="0" width="100%">
                                            <tbody></tbody>
                                        </table>
                                        <div ng-if="deals.status === -999" ng-cloak>
                                                {{ deals.message }}
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
        $(".addNewDealDialog").hide();
        $(".updateDealDialog").hide();
        
        $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
        
        function addNewDeal(ev) {
            var aJson = {
                "dealName"          : $(".addNewDealDialog #dealName").val(),
                "dealPrice"         : $(".addNewDealDialog #dealPrice").val(),
                "description"        : $(".addNewDealDialog #description").val(),
                "startDate"           : $(".addNewDealDialog #startDate").val(),
                "dueDate"           : $(".addNewDealDialog #dueDate").val()
            };

            PROCESSOR.backend_call(CLIENTZONE.function.addNewDeal,aJson);
        }
        
        function updateDeal(ev) {
            var aJson = {
                "dealCode"          : $(".updateDealDialog #dealCode").val(),
                "dealName"          : $(".updateDealDialog #dealName").val(),
                "dealPrice"         : $(".updateDealDialog #dealPrice").val(),
                "description"        : $(".updateDealDialog #description").val(),
                "startDate"           : $(".updateDealDialog #updateStartDate").val(),
                "dueDate"           : $(".updateDealDialog #updateEndDate").val()
            };

            PROCESSOR.backend_call(CLIENTZONE.function.updateDeal,aJson);
        }
        
        function openUpdateDealDialog(ev) {
            ev.preventDefault();

            sucess = UTILITY.addSelectDealForm();

            if(sucess) {
                var aJson = {
                    "dealCode": UTILITY.retrieveDealSelectRowDealCode()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.retrieveDeal,aJson);
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

            $(".addNewDealDialog").slideUp('0.5');
        }
        
        function openDeleteDealDialog(ev) {
            ev.preventDefault();

           sucess = UTILITY.addSelectDealForm();
           if(sucess) {
                aMessage = "Are you sure you want to remove deal from system?";
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
                            text: 'Remove Deal', 
                            onClick: function ($noty) {
                               var aJson = {
                                    "dealCode": UTILITY.retrieveDealSelectRowDealCode()
                                };

                                PROCESSOR.backend_call(CLIENTZONE.function.removeDeal,aJson);
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

            $(".addNewDealDialog").hide();
            $(".updateDealDialog").hide();
        }

        
        function addDatePicker(){
            $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
            $('.datePicker').datepicker('show');
        }
        function openAddNewDealDialog(ev){
            $(".addNewDealDialog").css('visibility',"visible");
            $(".updateDealDialog").slideUp('0.5');
            
            $(".addNewDealDialog").slideDown('0.5');
        }
        
        function reloadPage(ev) {
            window.location = window.location;
        }
    </script>
</html>

