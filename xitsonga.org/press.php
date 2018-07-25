<?php
    $pageName = 'press';
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
		   <article class="col-md-9 maincontent right app_div marginTablet"  style ="margin-top:5px;margin-right: 5px;margin-left: 0px">
			   <div class="row">    
				<div class="new_heading">
					<h4><a href ='kaya'>Home</a> > Press</h4>
				</div>
				</div>
				 <div class="rating_div" style ="margin-bottom: -15px">   
				<div class ='desc_heading'>
					<h4 id ="vision">Press</h4>
				</div>
			</div>
			<div class ="row" style ="font-size:15px">
				<div class ='col-md-3'>
					<img style ="border:1px solid gray" src ="assets/images/reviewonline_logo.jpg" width ="220"/>
				</div>
				<div class ='col-md-9'>
					<p>
						<a target = '_tab' href ="https://reviewonline.co.za/111336/tsonga-website-a-huge-hit/">Tsonga website and app a huge hit</a><br/>
						<span style= "color:gray;font-size:14px">Newspaper article | October 15, 2015</span><br/>
						<b>Review Online</b>, Sneidon Dumela hopes to expand his audience in the next year. Dumela started the website Xitsonga.org and has also created a mobile app for the website. 
					</p>
				</div>
			</div>
			<hr>
			<div class ="row" style ="font-size:15px">
				<div class ='col-md-3'>
					<img style ="border:1px solid gray" src ="assets/images/capriconfm.jpg" width ="220"/>
				</div>
				<div class ='col-md-9'>
					<p>
						<a target = '_tab' href ="https://twitter.com/capricornfm/status/656361237412974592">#CelebratingOurOwn </a><br/>
						<span style= "color:gray;font-size:14px">Radio interview | October 20, 2015</span><br/>
						<b>Capricorn FM</b>, Sneidon Dumela is the creator of http://www.xitsonga.org/dictionary  #LearnYourTsonga @DjComplexion @MphoMM_ #CelebratingOurOwn
					</p>
				</div>
			</div>
			<hr>
			<div class ="row" style ="font-size:15px">
				<div class ='col-md-3'>
					<img style ="border:1px solid gray" src ="assets/images/masthead_ptanorth.png" width ="220"/>
				</div>
				<div class ='col-md-9'>
					<p>
						<a target = '_tab' href ="https://rekordnorth.co.za/81578/former-up-student-creates-dictionary/">Former UP student creates dictionary</a><br/>
						<span style= "color:gray;font-size:14px">Newspaper article | July 21, 2016</span><br/>
						<b>Pretoria Rekord North</b>, The former University of Pretoria student said he came up with the idea of creating the dictionary after he found it difficult to get Xitsonga words on Google and other search engines while doing a research project.
					</p>
				</div>
			</div>
			<hr>
			<div class ="row" style ="font-size:15px">
				<div class ='col-md-3'>
					<img style ="border:1px solid gray" src ="assets/images/sapositive.png" width ="220"/>
				</div>
				<div class ='col-md-9'>
					<p>
						<a target = '_tab' href ="http://www.sapositivenews.com/index.php/en/18-sa-news/144-lack-of-xitsonga-resources-lead-to-development-of-xitsonga-org">Lack of xitsonga resources lead to development of xitsonga.org </a><br/>
						<span style= "color:gray;font-size:14px">News article | July 04, 2016</span><br/>
						<b>Sa Positive News</b>,  It became crystal clear that viable ideas are formed out of frustration when Sneidon Dumela was unable to find resources on the internet for a Xitsonga names research.
					</p>
				</div>
			</div>
			<hr>
			<div class ="row" style ="font-size:15px">
				<div class ='col-md-3'>
					<img style ="border:1px solid gray" src ="assets/images/GK-Logo.png" width ="220"/>
				</div>
				<div class ='col-md-9'>
					<p>
						<a target = '_tab' href ="https://blog.geekulcha.com/top15younggeeks-meet-mukondli-dumela/">#Top15YoungGeeks - Meet Mukondli Dumela </a><br/>
						<span style= "color:gray;font-size:14px">Feature | June 02, 2018</span><br/>
						<b>Geekulcha</b>, Mukondli Dumela is also known as Sneidon. The Software Development and Multimedia graduate from the University of Pretoria student came up with the idea of creating the dictionary after he found it difficult to get Xitsonga words on Google and other search engines while doing a research project
					</p>
				</div>
			</div>
			<hr>
		   </article>
                     <aside class="col-md-4 sidebar sidebar-right marginRightTablet fillWebsite">
                        <?php
                            require_once './assets/html/side_nav_right.php';
                        ?>
                    </aside>
		</div>
    </div>
    <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>