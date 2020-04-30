<?php
$pageName = 'status';
require_once 'webBackend.php';

$aWebbackend = new WebBackend();

if (!$aWebbackend->hasAccess($pageName)) {
    
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
                            <h4 id ="vision">Web</h4>
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
                                <td>Email address</td>
                                <td style="font-weight: bold;color:green">Available</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">Web Dictionary</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("xitsonga", "english", "web")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:green">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>


                            <tr>
                                <td style="width: 30%">Web Translator</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("Translate", "Translate", "web")) {
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
                                    if ($aWebbackend->GetSystemAPICallsStatus("xitsonga", "english", "web")) {
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
                                    if ($aWebbackend->GetSystemAPICallsStatus("Number", "Time", "web")) {
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
                                    if ($aWebbackend->GetSystemAPICallsStatus("api", "api", "web")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">Web Vision API</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("vision", "vision", "web")) {
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
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Bots</h4>
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
                                <td style="width: 30%">WhatsApp Bot</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("WhatsAppBot", "WhatsAppBot", "WhatsApp")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>


                            <tr>
                                <td style="width: 30%">WhatsApp Vision API</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("vision", "vision", "WhatsApp")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">WhatsApp Speech to Text API</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("speech-to-text", "speech-to-text", "WhatsApp")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>                        
                            <tr>
                                <td style="width: 30%">FB Messenger Bot</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("MessengerBot", "MessengerBot", "Messenger")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 30%">FB Messenger Vision API</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("vision", "vision", "Messenger")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 30%">SMS Bot</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("SMSBot", "SMSBot", "SMS")) {
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
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Android</h4>
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
                                <td style="width: 30%">Android Dictionary</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("xitsonga", "english", "android")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 30%">Android Translator</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("Translate", "Translate", "android")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">Android Vision API</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("vision", "vision", "android")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">Android Word Descriptions</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("xitsonga", "english", "android")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">Android Numbers & Time</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("Number", "Time", "android")) {
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
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">iOS</h4>
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
                                <td style="width: 30%">iOS Dictionary</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("xitsonga", "english", "IOS")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>

                            <tr>
                                <td style="width: 30%">iOS Translator</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("Translate", "Translate", "iOS")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">iOS Word Descriptions</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("xitsonga", "english", "iOS")) {
                                        echo '<span style ="color:green">Available</span>';
                                    } else {
                                        echo '<span style ="color:orange">Available</span>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td style="width: 30%">iOS Numbers & Time</td>
                                <td style="font-weight: bold">
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("Number", "Time", "iOS")) {
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
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Browsers</h4>
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
                                <td style="width: 30%">Chrome, Firefox and Opera Add Ons</td>
                                <td style="font-weight: bold"> 
                                    <?php
                                    if ($aWebbackend->GetSystemAPICallsStatus("translate", "Translate", "Plugin")) {
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
                    <div class="rating_div" style ="margin-bottom: -15px">   
                        <div class ='desc_heading'>
                            <h4 id ="vision">Gaming</h4>
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
                                    if ($aWebbackend->GetSystemAPICallsStatus("Results", "Complete_Lesson", "android")) {
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