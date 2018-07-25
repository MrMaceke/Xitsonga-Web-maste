<?php
    $pageName = 'status';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
        
    if(!$aWebbackend->hasAccess($pageName)){
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        require_once './assets/html/metadata.php';
        require_once './assets/html/script.php';
        require_once './assets/html/script_2.php';
    ?>
</head>

<body class="home">
    <?php
        require_once './assets/html/nav.php';
    ?>
   
    <!-- container -->
    <div class="container">
        <br/>
        <div class ="row">
           <article class="col-md-12 maincontent right app_div marginTablet"  style ="margin-top:5px;margin-right: 5px;margin-left: 0px">
                <div class="row">    
                     <div class="new_heading">
                         <h4><a href ='kaya'>Home</a> > Status</h4>
                     </div>
                 </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">System Status</h4>
                    </div>
                </div>
               <table id="dictionary_data_table" class="display" cellspacing="0" style="font-size: 14px">
                    <thead>
                        <tr>
                            <th>System/function</th>
                            <th>Status/Last 24 hours</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 30%">Web Dictionary</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("xitsonga","english", "web")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:green">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Android Dictionary</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("xitsonga","english", "android")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td>Intermittent bad service request issue</td>
                        </tr>
                        <tr>
                            <td style="width: 30%">iOS Dictionary</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("xitsonga","english", "IOS")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Web Dictionary API</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("api","api", "web")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">iOS Word Search Game</td>
                            <td style="font-weight: bold;color:green">Available</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Android Word Search Game</td>
                            <td style="font-weight: bold;color:green">Available</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Android Kids App</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("Results","Complete_Lesson", "android")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Email address</td>
                            <td style="font-weight: bold;color:red">Unvailable</td>
                            <td>Please use sneidon.dumela@gmail.com instead.</td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Web Translator</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("Translate","Translate", "web")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Web Word Descriptions</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("xitsonga","english", "web")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:green">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Web Numbers & Time</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("Number","Time", "web")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">iOS and Android Translator</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("Translate","Translate", "android")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td>iOS translator will be available from 30th of July 2018</td>
                        </tr>
                        <tr>
                            <td style="width: 30%">iOS and Android Word Descriptions</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("xitsonga","english", "android")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">iOS and Android Numbers & Time</td>
                            <td style="font-weight: bold">
                                <?php
                                    if($aWebbackend->GetSystemAPICallsStatus("Number","Time", "android")){
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
               <br/><br/>
           </article>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>