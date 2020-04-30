<?php
    require_once './php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"dashboard");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:../login/");
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
        
        <title>Dashboard &HorizontalLine; ClientZone</title>
        <?php require_once './components/script_loader/main.php'; ?>
        <script>
            
            function clearLogs(ev) {
                aMessage = "Are you sure you want to clear log file";
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
                            text: 'Clear Logs', 
                            onClick: function ($noty) {
                                var aJson = {
                                   "clear"     : true
                                };
                                PROCESSOR.backend_call(CLIENTZONE.function.clearLogs,aJson);
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
            function changeLogsStatus(ev) {
                id = $(".logstatus").attr('id');
                aMessage = "Are you sure you want turn " + id + " logs?";
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
                            text: 'Turn ' + id, 
                            onClick: function ($noty) {
                                var aJson = {
                                   "flag"     : id
                                };
                                PROCESSOR.backend_call(CLIENTZONE.function.updateLogFlagStatus,aJson);
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
                            <?php
                                // Retrieve dashboard
                                if($aUserRole[status]) {
                                    if($aUserRole[message] == AccessValidatorBean::ADMIN) {
                                        require_once './components/dashboard/administrator_panel.php';
                                        require_once './components/dashboard/administrator_graphs.php';
                                    }else if($aUserRole[message] == AccessValidatorBean::BUSINESS) {
                                        require_once './components/dashboard/consultant_panel.php';
                                        require_once './components/dashboard/consultant_graphs.php';
                                    }else if($aUserRole[message] == AccessValidatorBean::DEVELOPER) {
                                        require_once './components/dashboard/developer_panel.php';
                                        require_once './components/dashboard/developer_graphs.php';
                                    }else if($aUserRole[message] == AccessValidatorBean::CLIENT) {
                                        require_once './components/dashboard/client_panel.php';
                                        require_once './components/dashboard/client_graphs.php';
                                    }else {
                                        echo AccessValidatorBean::DEFAULT_ERROR_MESSAGE;
                                    }
                                }else {
                                    echo AccessValidatorBean::DEFAULT_ERROR_MESSAGE;
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php require_once './components/footer/main.php'; ?>
    </body>
</html>

