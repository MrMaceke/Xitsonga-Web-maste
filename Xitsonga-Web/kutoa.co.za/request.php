<?php
    require_once __DIR__.'/server/php/WebBean.php';
    $aWebBean = new WebBean();
    
    $aRideResults = $aWebBean->retrieveRideRequest($_REQUEST[id]);
    if($aRideResults[status]) {
        $aRecord = $aRideResults[record];
        if($aRecord[request_type] == 1) {
            $aDescription =  "I am asking for a ride. I am willing to pay R".$aRecord[price];
        }else  if($aRecord[request_type] == 2) {
            $aDescription =  "I am offering a ride for R".$aRecord[price];
        }
    } else {
        $aDescription = "I am looking for someone to share a ride with.";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" 
          content="<?php echo $aDescription; ?>">
    <meta name="author" content="">

    <title>Kutoa Request</title>

    <?php
        require_once './assets/css_loader/css.php';
    ?>
</head>

<body id="page-top">
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top" style="border-bottom: 1px solid #E8E8E8">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a style="color:#333333" class="navbar-brand page-scroll" href="home/">Kutoa</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a style="color:#333333" href="home/">Download</a>
                    </li>
                    <li>
                        <a style="color:#333333" href="terms/">Terms</a>
                    </li>
                    <li>
                        <a style="color:#333333" href="privacy/">Privacy</a>
                    </li>
                     <li>
                        <a style="color:#333333" href="safety/">Safety</a>
                    </li>
                    <li>
                        <a style="color:#333333" href="contact/">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3>KUTOA REQUEST</h3>
                    <p><?php echo $aDescription; ?></p>    
                    <p>You can open the desired request in two ways: only available on Android</p>
                    <ul>
                        <li><a href="<?php echo "http://www.kutoa.co.za/request/$_REQUEST[id]";?>">I have App Installed and I am using my mobile phone now</a></li>
                        <li><a href="https://play.google.com/store/apps/details?id=za.co.waxbill.app.kutoa">I want to download Kutoa for Android App first</a></li>
                    </ul>
                    <HR>
                    <h3>ABOUT KUTOA</h3>
                    <img width="200" src="https://lh3.googleusercontent.com/yd9THKxTSvoVNSHFZJh45euGB8-bbbwbAdLCXmeAqxn5IWOwkrY8ngFg0JBKZ2Qg9Q=w300"/>
                    <p>Kutoa brings people together to share rides. The community is made possible by friends, family, colleagues and people meeting for the first time, who are willing to commute together.</p>
                </div>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <p>&copy; 2017 <a target="tab" href ="http://www.facebook.com/mukondli">Sneidon</a>. All Rights Reserved.</p>
        </div>
    </footer>
    
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/new-age.min.js"></script>
</body>
</html>
