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
	<meta name="author" content="Waxbill">
        
        <?php require_once 'components/css_loader/main_css.php' ?>
        
        <title>Groups &HorizontalLine; Client Zone</title>
        <?php require_once './components/script_loader/main.php'; ?>
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
                                <div class ="module-head"><h3>Group Console</h3></div>
                                <div class ="module-body">
                                    <?php require_once './components/menu/group_top_menu.php';?>
                                    <?php require_once './components/dialogs/addNewSystemGroup.php';?>
                                    <?php require_once './components/dialogs/updateNewSystemGroup.php';?>
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
                                                    <strong>Mandatory groups</strong> <span class="pull-right small muted">95%</span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar bar-success" style="width: 95%;"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="module">
                                <div class ="module-head"><h3>System Groups</h3></div>
                                <div ng-controller="systemGroupsController" ng-init="init()" class ="module-body">
                                    <div ng-show="showLoader">
                                        <div style="height: 400px"></div>
                                    </div>
                                    <br/>
                                    <table id = 'groupsTable' class="display table table table-bordered" cellspacing="0" width="100%">
                                        <tbody></tbody>
                                    </table>
                                    
                                    <div ng-if="systemGroups.status === -999">
                                        <div class="alert alert-dismissible alert-danger" ng-cloak>
                                            {{ systemGroups.message }}
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
        <?php require_once './components/footer/main.php'; ?>
        </body>
       
        <script>  
            $(".addGroupDialog").hide();
            $(".updateGroupDialog").hide();
            
            function addNewSystemGroup(ev) {
                ev.preventDefault();
                var aJson = {
                    "groupName": $(".addGroupDialog #groupName").val(),
                    "groupValue": $(".addGroupDialog #groupValue").val(),
                    "groupDescription": $(".addGroupDialog #groupDescription").val()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.addNewSystemGroup,aJson);
            };
            
            function updateSystemGroup(ev) {
                ev.preventDefault();
                var aJson = {
                    "groupId": $(".updateGroupDialog #groupId").val(),
                    "groupName": $(".updateGroupDialog #groupName").val(),
                    "groupValue": $(".updateGroupDialog #groupValue").val(),
                    "groupDescription": $(".updateGroupDialog #groupDescription").val()
                };

                PROCESSOR.backend_call(CLIENTZONE.function.updateSystemGroup,aJson);
            };

            function reloadPage(ev) {
                ev.preventDefault();
                
                window.location = "groups/";
            }
            
            function openDeleteGroupDialog (ev) {
                ev.preventDefault();
                 success = UTILITY.systemGroupHasSelectedRow();
                
                if(success) {
                    aMessage = "Are you sure you want to delete group?";
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
                            close : 'animated bounceOutLeft',
                            easing: 'swing',
                            speed : 500
                        },
                       buttons: [
                            {
                                addClass: 'btn btn-primary', 
                                text: 'Delete Group', 
                                onClick: function ($noty) {
                                   var aJson = {
                                        "groupId": UTILITY.retrieveSystemGroupSelectRowId()
                                    };

                                    PROCESSOR.backend_call(CLIENTZONE.function.deleteSystemGroup,aJson);
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
                            close : 'animated bounceOutLeft',
                            easing: 'swing',
                            speed : 500
                        }
                    });
                }
                $(".addGroupDialog").hide();
                $(".updateGroupDialog").hide();
            };
            
            function openAddGroupDialog(ev) {
                ev.preventDefault();
                $(".addGroupDialog").css('visibility',"visible");
                $(".addGroupDialog").slideDown('0.5');
                
                $(".updateGroupDialog").hide();
            }
            
            function redirectToHelp(ev) {
                window.location = "help/";
            }
            
            function openUpdateGroupDialog(ev) {
                ev.preventDefault();
                
                sucess = UTILITY.addSelectGroupToForm(null);
                
                if(sucess) {
                    $(".updateGroupDialog").css('visibility',"visible");
                    $(".updateGroupDialog").slideDown('0.5');
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
                            close : 'animated bounceOutLeft',
                            easing: 'swing',
                            speed : 500
                        }
                    });
                }
                $(".addGroupDialog").hide();
            }
        </script>
   
</html>

