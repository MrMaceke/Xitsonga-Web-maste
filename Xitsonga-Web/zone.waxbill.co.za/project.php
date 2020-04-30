<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    $project = array("param"=>$_REQUEST['sk'],"pageName"=>"project");
    $root = array("pageName"=>"project");
    
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../offline/".$_REQUEST['sk']);
        exit();
    }
    
    $aResourceAccess = $aAccessBean->hasAccessToResource($project);
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
        
        <title>Project &HorizontalLine; ClientZone</title>
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
                                require_once './components/info_snippets/project_top_bar_info.php';
                                require_once './components/menu/main_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::BUSINESS) {
                                require_once './components/info_snippets/project_top_bar_info.php';
                                require_once './components/menu/consultant_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::DEVELOPER) {
                                require_once './components/info_snippets/project_top_bar_info.php';
                                require_once './components/menu/developer_side_menu.php';
                            }else if($aUserRole[message] == AccessValidatorBean::CLIENT) {
                                require_once './components/info_snippets/project_top_bar_info.php';
                                require_once './components/menu/client_side_menu.php';
                            }else {
                                echo AccessValidatorBean::USER_TYPE_ERROR_MESSAGE; 
                            }
                        }else {
                            echo AccessValidatorBean::DEFAULT_ERROR_MESSAGE;
                        }
                    ?>
                    <div class="span9" ng-controller="systemPropertiesController" >
			<div class="content" ng-controller="projectController">
                            <div class="module">
                                <div class="module-head"><h3> <span ng-repeat="detail in project.details" ng-if ="detail.typeName === 'Project Name'">{{detail.entityDetailContent}}&nbsp;</span></h3></div>
                                <div class="module-body" style="min-height: 30px">
                                    <div class="col-md-12">
                                        <div ng-if="loggedUser.credentials.roleName ==='Developer' || loggedUser.credentials.roleName ==='Consultant' || loggedUser.credentials.roleName ==='Administrator'">
                                            <div ng-if ="showMainMenu === true">
                                                <?php require_once './components/menu/project_top_menu.php';?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php require_once './components/dialogs/updateProjectBasic.php';?>
                                    <?php require_once './components/dialogs/addNewItem.php';?>
                                    <?php require_once './components/dialogs/addUploadItemDoc.php';?>
                                    <?php require_once './components/dialogs/addUploadContract.php';?>
                                    <?php require_once './components/dialogs/addNewQuote.php';?>
                                </div>
                            </div>
                           
                            <div class="btn-box-row row-fluid">
                                <div class="span12" ng-cloak>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <a href ="" onclick="printContract()" class="btn-box big span3"><i class="icon-file"></i><p class="text-muted">Contract</p></a>
                                            <a href ="" onclick="retrieveInvoices()" class="btn-box big span3"><i class="icon-money"></i><br/><p class="text-muted">Invoices</p></a>
                                            <a href ="" onclick="printQuote()" class="btn-box big span3"><i class="icon-credit-card"></i><p class="text-muted">Quote</p></a>
                                            <a href ="" onclick="retrieveProjectDocuments()" class="btn-box big span3"><i class="icon-copy"></i><p class="text-muted">Documents</p></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php require_once './components/info_snippets/project_basic_info.php';?>
                            <div class="module" ng-cloak style="min-height: 500px">
                                <div ng-show="showLoader">
                                    <div style="height: 599px"></div>
                                </div>
                                <div id ="stages">
                                </div>
                                <br/>
                            </div>

                            <div class="panel-body col-md-12" ng-if="project.status === -999" ng-cloak>
                                <div>
                                    <div class="alert alert-dismissible alert-warning">
                                        {{ project.message }}
                                    </div>
                                    <p>Please contact <a target ="_tab" href ="mailto:webmaster@waxbill.co.za">webmaster@waxbill.co.za</a> if the error persists.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <?php require_once './components/dialogs/stageItemView.php';?>
            <?php require_once './components/dialogs/stageDocumentsView.php';?>
            <?php require_once './components/dialogs/projectInvoicesView.php';?>
            <?php require_once './components/dialogs/updateStageItemView.php';?>
        </div>

        <?php require_once './components/footer/main.php'; ?>
        </body>
        <script> 
            $(".updateProjectBasicDialog").hide();
            $(".addNewQuoteDialog").hide();

            function addDatePicker(){
                $(".datePicker").datepicker({ dateFormat: "dd-mm-yy" });
                $('.datePicker').datepicker('show');
            }
            
            function printContract(ev){
                var aJson = {
                    "projectId": $(".addUploadItemDocDialog #projectId").val()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadProjectContract,aJson);
            }
            
            function printQuote(ev){
                var aJson = {
                    "projectId": $(".addUploadItemDocDialog #projectId").val()
                };

                DOC_CREATOR_PROCESSOR.backend_call(DOCUMENT_CREATOR.function.downloadQuote,aJson);
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
                    "projectId": $(".addNewQuoteDialog #projectId").text(),
                    "dealCodes" : aDetails
                };

                FINANCE_PROCESSOR.backend_call(FINANCE_BEAN.function.generateQuoteAndDownload,aJson);
            }
            
            function retrieveInvoices(ev) {
                var aJson = {
                    "projectId": $(".addNewQuoteDialog #projectId").text()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.retrievePaymentsByProjectId,aJson);
            }
            
            function retrieveProjectDocuments(ev){
                var aJson = {
                    "projectId": $(".addNewQuoteDialog #projectId").text()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.retrieveProjectDocuments,aJson);
            }
            
            function updateStageItem(ev) {
                ev.preventDefault();
                
                var aDetails  = {};
                var vCount = 0;
                $(".updateStageItemView .itemDetails").each(function(e){
                    aDetails[vCount] = {
                        "entityDetailId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                var aJson = {
                    "itemId"     : $(".updateStageItemView #itemId").text(),
                    "itemDetails" : aDetails
                };
                
                PROCESSOR.backend_call(CLIENTZONE.function.updateStageItem,aJson);
            }
            
            function addNewStageItemDocument(ev) {
                ev.preventDefault();
                
                var itemDetails  = {};
                var vCount = 0;
                $(".addUploadItemDocDialog .itemDetails").each(function(e){
                    itemDetails[vCount] = {
                        "propertyId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                
                var aJson = {
                    "clientId"      : $(".addUploadItemDocDialog #clientId").val(),
                    "projectId"     : $(".addUploadItemDocDialog #projectId").val(),
                    "entityType"    : $(".addUploadItemDocDialog #itemType").val(),
                    "projectStage"  : $(".addUploadItemDocDialog #projectStage").val(),
                    "itemDetails"   : itemDetails
                };
                var formData = new FormData(document.getElementById("fileinfo"));
                FILE_PROCESSOR.backend_call(CLIENTZONE.function.uploadStageItemDocument,aJson,formData);
            }
            
            function uploadProjectContractDocument(ev) {
                ev.preventDefault();
                
                var aJson = {
                    "clientId"      : $(".addUploadItemDocDialog #clientId").val(),
                    "projectId"     : $(".addUploadItemDocDialog #projectId").val()
                };
                var formData = new FormData(document.getElementById("contractfileinfo"));
                FILE_PROCESSOR.backend_call(CLIENTZONE.function.uploadProjectContractDocument,aJson,formData);
            }
            
            function addNewStageItem(ev) {
                ev.preventDefault();
                
                var itemDetails  = {};
                var vCount = 0;
                $(".addNewItemDialog .itemDetails").each(function(e){
                    itemDetails[vCount] = {
                        "propertyId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                
                var aJson = {
                    "clientId"      : $(".addNewItemDialog #clientId").val(),
                    "projectId"     : $(".addNewItemDialog #projectId").val(),
                    "entityType"    : $(".addNewItemDialog #itemType").val(),
                    "projectStage"  : $(".addNewItemDialog #projectStage").val(),
                    "itemDetails"   : itemDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.addNewStageItem,aJson);
            }
            
            function progressProjectStage(ev) {
                closeAllDialogs();
                
                aMessage = "Are you sure you want to progress project?";
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
                            text: 'Progress Project', 
                            onClick: function ($noty) {
                                var aJson = {
                                   "projectId"     : UTILITY.scope.project.projectId
                                };
                                PROCESSOR.backend_call(CLIENTZONE.function.progressProjectToNextStage,aJson);
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
            
            function openUpdateStageItemDialog(ev){
                ev.preventDefault();
                 
                $(".updateProjectBasicDialog").slideUp('0.5');
                
                sucess = UTILITY.stageItemHasSelectedRow();
                if(sucess) {
                    var aJson = {
                        "itemId": UTILITY.retrieveStageItemSelectRowId()
                    };
                    UTILITY.ajaxResponseHolder = true;
                    PROCESSOR.backend_call(CLIENTZONE.function.retrieveProjectStageItem,aJson);
                } else {
                    noty({
                        id          : "warningId",
                        text        : '<div class="activity-item"> <i class="fa fa-exclamation"></i><div class="activity" style ="">You must selected a stage item first</div> </div>',
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
            
            function openStageItemDialog(ev) {
                ev.preventDefault();
                 
                closeAllDialogs();
                
                sucess = UTILITY.stageItemHasSelectedRow();
                if(sucess) {
                    var aJson = {
                        "itemId": UTILITY.retrieveStageItemSelectRowId()
                    };
                    UTILITY.ajaxResponseHolder = false;
                    PROCESSOR.backend_call(CLIENTZONE.function.retrieveProjectStageItem,aJson);
                } else {
                    noty({
                        id          : "warningId",
                        text        : '<div class="activity-item"> <i class="fa fa-exclamation"></i><div class="activity" style ="">You must selected a stage item first</div> </div>',
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
            
            function updateProject(ev) {
                ev.preventDefault();
                
                var projectDetails  = {};
                var vCount = 0;
                $(".updateProjectBasicDialog .userDetails").each(function(e){
                    projectDetails[vCount] = {
                        "entityDetailId": $(this).attr('id'),
                        "propertyName": $(this).attr('alt'),
                        "entityContent": $(this).val()
                    };
                    vCount ++;
                });
                
                var aJson = {
                    "projectId"      : $(".updateProjectBasicDialog #projectId").text(),
                    "projectDetails"   : projectDetails
                };

                PROCESSOR.backend_call(CLIENTZONE.function.updateProject,aJson);
            };
            
            function setupAddNewItem(ev){
               $('#addNewItem').modal('show');
               $(".addNewItemDialog #stageName").html($(".setupAddNewItem").attr("alt"));
               $(".addNewItemDialog #projectStage").val($(".setupAddNewItem").attr("alt"));
            }
            
            function setupUploadContractDialog(ev){
                closeAllDialogs();
               $('#addUploadContractDialog').modal('show');
            }
            
            function setupUploadItemDoc(ev){
               $('#addUploadItemDocDialog').modal('show');
               $(".addUploadItemDocDialog #stageName").html($(".setupAddNewItem").attr("alt"));
               $(".addUploadItemDocDialog #projectStage").val($(".setupAddNewItem").attr("alt"));
            }
            
            function openEditProjectDialog(ev){
                $(".updateProjectBasicDialog").css('visibility',"visible");
                closeAllDialogs();
                $(".updateProjectBasicDialog").slideDown('0.5');
            }
            
            function closeAllDialogs(){
                $(".updateProjectBasicDialog").slideUp('0.5');
                $(".addNewQuoteDialog").slideUp('0.5');
            }
            
            function openGenerateQuote(ev) {
                $(".addNewQuoteDialog").css('visibility',"visible");
                
                closeAllDialogs();
                
                $(".addNewQuoteDialog").slideDown('0.5');
            }
            
            function reloadPage(ev) {
                window.location = window.location;
            }
            
        </script>
</html>

