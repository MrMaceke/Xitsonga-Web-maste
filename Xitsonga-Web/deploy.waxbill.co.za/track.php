<?php
    require_once __DIR__.'/../zone.waxbill.co.za/php/AccessValidatorBean.php';
    
    $aAccessBean = new AccessValidatorBean();
    
    $root = array("pageName"=>"internal");
    $aAccess = $aAccessBean->hasAccess($root);
    if($aAccess['status'] == false){
        header("Location:https://zone.waxbill.co.za/login/");
        exit();
    }
?>
<!DOCTYPE html>
<html ng-app>
    <head>
        <title>Progress - Deployment</title>
        <?php require_once './components/css_loader/main.php';?>
        <script src="jquery/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src = "jquery/angular.min.js"></script>
        <script src = "jquery/jquery.noty.js"></script>
        <script src = "jquery/jquery.noty.packaged.js"></script>
        <script src = "beans/clientZoneBean.js"></script>
    </head>
    <body>
        <div id = "wrapper">
            <ul id="menu">
                <li><a href="home/"><img src ="assets/images/deploynator.png"/></a></li>
                <li><a class = 'linkAnchor' href="home/">Home</a></li>
                <li><a class = 'linkAnchor' href="push/">Push Deployment</a></li>
                <li><a class = 'linkAnchor active' href="progress/">Track Deployment</a></li>
            </ul>
            <img src ="assets/images/maple_home.png" width="100%"/>
            <div id ="content" ng-controller="retrieveDeployments" ng-cloak>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width:20%">Deployment ID</th>
                            <th style="width:20%">Project ID</th>
                            <th style="width:20%">Project Name</th>
                            <th style="width:20%">Environment</th>
                            <th style="width:20%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat = "deployment in deployments | orderBy:'-dateCreated'" ng-if="deployments.status !== -999">
                            <td>{{ deployment.deploymentId }}</td>
                            <td>{{ deployment.projectId }}</td>
                            <td ng-repeat = "detail in deployment.details" ng-if="detail.typeName === 'Project Name'">{{ detail.entityDetailContent }}</td>
                            <td>{{ deployment.environment }}</td>
                            <td>{{ deployment.dateCreated }}</td>                        
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="footerDiv">
                <div id="foot">
                    <p>&copy; 2016 <a href ="https://zone.waxbill.co.za">Waxbill</a>. All Rights Reserved.</p>
                </div>
	    </div>
        </div>
   </body>
</html>