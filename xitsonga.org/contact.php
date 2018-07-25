<?php
    $pageName = 'contact';
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
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/user.js"></script>
    <script>
        $(document).ready(function(e){
            $(".btn-action").click(function(e){
                e.preventDefault();

                var vUserData = new Array();

                vUserData["names"] = $("#names").val();
                vUserData["email"] = $("#email").val();
                vUserData["phone"] = $("#phone").val();
                vUserData["message"] = $("#message").val();
                if(USER_VALIDATOR.validate_mail_input(vUserData)){
                    USER_PROCESSOR.backend_call(USER_CONSTANTS.function.send_email,USER_DATA.mail_json(vUserData));
                }
            }) 
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
   
    <!-- container -->
	<div class="container">
                <br/>
		<div class="row">
			<?php
                        //$aDTOUser = new DTOUser();
                        $aFirstName = ucfirst(strtolower($aDTOUser->getFirstName()));
                        $aLastname = ucfirst(strtolower($aDTOUser->getLastName()));
                        $aEmail = ucfirst(strtolower($aDTOUser->getEmail()));
                        $aRegistration = ucfirst(strtolower(""));
                        $access = "Website User or Learner";
                        
                        if($aDTOUser->isAdmin()){
                            $access = "Administrator";  
                        }
                    ?>
                                            <!-- Article main content -->
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 0px">
                    <div class="row">    
                        <div class="new_heading">
                           <h4><a href ='kaya'>Home</a> > Contact</h4>
                       </div>
                   </div>
                   <div class="rating_div" style ="margin-bottom: -15px">   
                       <div class ='desc_heading'>
                           <h4 id ="vision">Get in Touch</h4>
                       </div>
                    </div>
				
				<p>
					We’d love to hear from you. Interested in working together? Fill out the form below with some info about your project and I will get back to you as soon as I can. Please allow a couple days for us to respond.
				</p>
				<br>
					<form class ='basic_form'>
						<div class="row">
							<div class="col-sm-4">
                                                            <input class="form-control" id ='names' value="<?php echo $aFirstName?>" type="text" placeholder="Name">
							</div>
							<div class="col-sm-4">
								<input class="form-control" id ='email' type="text" value ="<?php echo $aEmail;?>" placeholder="Email">
							</div>
							<div class="col-sm-4">
								<input class="form-control" id ='phone' type="text" placeholder="Phone">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-sm-12">
								<textarea id ='message' placeholder="Type your message here..." class="form-control" rows="9"></textarea>
							</div>
						</div>
                                                <div class ="error"></div>
						<br>
						<div class="row">
							<div class="col-sm-6">
								<!--<label class="checkbox"><input type="checkbox"> Sign up for newsletter</label>-->
							</div>
							<div class="col-sm-6 text-right">
								<input id ='send_email' class="btn btn-action" type="submit" value="Send message">
							</div>
						</div>
					</form>
                                <hr>
			</article>
			<!-- /Article -->
			
			<aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
                <?php
                    require_once './assets/html/side_nav_right.php';
                ?>
            </aside>

		</div>
	</div>	<!-- /container -->
        <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>