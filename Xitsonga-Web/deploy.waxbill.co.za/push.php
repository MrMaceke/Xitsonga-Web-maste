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
        <title>Push Deployment</title>
        <?php require_once './components/css_loader/main.php';?>
        <script src="jquery/jquery.min.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src = "jquery/angular.min.js"></script>
        <script src = "jquery/jquery.noty.js"></script>
        <script src = "jquery/jquery.noty.packaged.js"></script>
        <script src = "beans/clientZoneBean.js"></script>
        <script>
            function deployProject(){
                var aJson = {
                    "projectId"    : $("#projectID").val(),
                    "environment"  : $("#environment").val()
                };
                var formData = new FormData(document.getElementById("fileinfo"));
                FILE_PROCESSOR.backend_call(CLIENTZONE.function.deployProjectToQA,aJson,formData);
            };
        </script>
    </head>
    <body>
        <div id = "wrapper">
            <ul id="menu">
                <li><a href="home/"><img src ="assets/images/deploynator.png"/></a></li>
                <li><a class = 'linkAnchor' href="home/">Home</a></li>
                <li><a class = 'linkAnchor active' href="push/">Push Deployment</a></li>
                <li><a class = 'linkAnchor' href="progress/">Track Progress</a></li>
            </ul>
            <img src ="assets/images/maple_home.png" width="100%"/>
            <div id ="content" ng-controller="retrieveSystemProperties">
               <form class="form-validation" method="post" id="fileinfo" name="fileinfo" enctype="multipart/form-data">

                    <div class="form-title-row">
                        <h1>Deploy Project</h1>
                    </div>
                    <div class="form-row form-input-name-row">
                        <label>
                            <span>Project ID</span>
                            <input type="text" name="projectID" id ="projectID" placeholder="Project ID">
                        </label>
                        <span class="form-valid-data-sign"><i class="fa fa-check"></i></span>
                        <span class="form-invalid-data-sign"><i class="fa fa-close"></i></span>
                        <span class="form-invalid-data-info"></span>
                    </div>
                    <div class="form-row form-input-name-row">
                        <label>
                            <span>Environment</span>
                            <select  id = "environment">
                                <option ng-repeat = "property in systemProperties" value="{{ property.propertyId }}" ng-if ="property.groupName === 'QA Environments'" ng-cloak>{{ property.propertyName }}</option>
                            </select>
                        </label>
                        <span class="form-valid-data-sign"><i class="fa fa-check"></i></span>
                        <span class="form-invalid-data-sign"><i class="fa fa-close"></i></span>
                        <span class="form-invalid-data-info"></span>
                    </div>
                   <div class="form-row form-input-name-row">
                        <label>
                            <span>Project ZIP</span>
                            <input type="file" name="file" id ="file">
                        </label>
                        <span class="form-valid-data-sign"><i class="fa fa-check"></i></span>
                        <span class="form-invalid-data-sign"><i class="fa fa-close"></i></span>
                        <span class="form-invalid-data-info"></span>
                    </div>
                    <div class="form-row">
                        <button type="submit" onclick="deployProject()">Deploy Project</button>
                    </div>
                </form>
            </div>
            <div id="footerDiv">
                <div id="foot">
                    <p>&copy; 2016 <a href ="https://zone.waxbill.co.za">Waxbill</a>. All Rights Reserved.</p>
                </div>
	    </div>
        </div>
   </body>
</html>