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
<html>
    <head>
        <title>Deploynator</title>
        <?php require_once './components/css_loader/main.php';?>
        <script src="jquery/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $(".go_to").click(function (e){
                    e.preventDefault();
                    window.location = $("#action").val() + "/";
                });
            });
        </script>
    </head>
    <body>
        <div id = "wrapper" style="background:white">
            <ul id="menu">
                <li><a href="home/"><img src ="assets/images/deploynator.png"/></a></li>
                <li><a class = 'linkAnchor active' href="home/">Home</a></li>
                <li><a class = 'linkAnchor' href="push/">Push Deployment</a></li>
                <li><a class = 'linkAnchor' href="progress/">Track Progress</a></li>
            </ul>
            <img src ="assets/images/home_image.png" width="100%"/>
            <div id ="content">
                <div id="contentleft">
                    <img src="assets/images/ftp.jpg" width="500" height="300" alt="Home" title="Home"/>
                    <label for="action" style="color:#FC860C">System Status</label>: Healthy<br>
                </div>
                <div id="sidebar">
                    <div class="sidebar-element">
                        <h3>About Deploynator</h3><br>
                        <p>Allows developers to upload a project to QA environments for testing purposes. Waxbill uses the following QA Environments.</p><br/>
                        <h4>Maple QA environment</h4><br>
                        <p>
                             Maple is intended for developers to test server related functionality and integration for their solutions. 
                        </p><br>
                        <P><h4>Cedar QA Environment(UAT)</h4></P><br>
                    <p>
                         Cedar is intended for clients and business consultants to do the final testing of the requirement. 
                    </p><br>
                        <P><h4>Olive QA Environment(P/E)</h4></P><br>
                        <p>
                            Olive is intended for clients and business consultants to mimic what is on the live environment. 
                        </p>
                    </div>
                 </div>
            </div>
           <div id="footerDiv">
                <div id="foot">
                    <p>&copy; 2016 <a href ="https://zone.waxbill.co.za">Waxbill</a>. All Rights Reserved.</p>
                </div>
	    </div>
        </div>
   </body>
</html>