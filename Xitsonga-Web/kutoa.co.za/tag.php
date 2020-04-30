<?php 
	$token = $_REQUEST["code"];
		
	$instagramAppId = "194376511773935";
	$directURL = "https://www.kutoa.co.za/tag.php";
	$scope = "user_profile,user_media";
	$url = "https://api.instagram.com/oauth/authorize?client_id=$instagramAppId&redirect_uri=$directURL&scope=$scope&response_type=code";
	if($token != "") {
		$post = [
			'client_id' => $instagramAppId,
			'client_secret' => '82db47338030ac3557b5417fa8915287',
			'grant_type'   => "authorization_code",
			'redirect_uri' => $directURL,
			'code'   => $token,
		];

		$ch = curl_init('https://api.instagram.com/oauth/access_token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		// execute!
		$response = curl_exec($ch);
		$json = json_decode($response);
		
		echo "Test {TAG}".$json->access_token. "{TAG}".$json->user_id;
		// close the connection, release resources used
		curl_close($ch);
		exit();
	} 
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Instagram</title>

	<base href="https://www.kutoa.co.za/" />

	<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

	<link rel="stylesheet" href="lib/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="lib/simple-line-icons/css/simple-line-icons.css">
	<link rel="stylesheet" href="lib/device-mockups/device-mockups.min.css">

	<link href="css/new-age.css" rel="stylesheet">
</head>

<body id="page-top">
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top" style="border-bottom: 1px solid #E8E8E8">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a style="color:#333333" class="navbar-brand page-scroll" href="home/">Tag Prototype</a>
            </div>
        </div>
    </nav>

    <section>
        <div class="container">
            <div class="row">
				
                <div class="col-md-8 col-md-offset-2">
                    <h3>INSTAGRAM TOKEN</h3>
                    <p>
                    <p>Instagram misses you.<br/><br/>
					This authorization allows your Tag Prototype account to view Instagram trends and associate your image with the most profitable campaings. Authorization is required every 60 days and renewable every 24 hours.
					</p>
					<a href="<?php echo $url; ?>" class="btn btn-xl page-scroll" style="background:black !important;color: white !important">GET TOKEN</a>
                </div>
            </div>
        </div>
    </section>
    
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/new-age.min.js"></script>
</body>
</html>
