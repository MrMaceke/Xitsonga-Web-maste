<?php
    $pageName = 'register';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
        
    if(!$aWebbackend->hasAccess($pageName)){
        header('Location: access');
        exit();
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

                vUserData["email"] = $("#email").val();
                vUserData["cemail"] = $("#cemail").val();
                vUserData["firstName"] = $("#firstname").val();
                vUserData["lastName"] = $("#lastname").val();
                vUserData["password"] = $("#password").val();
                vUserData["cpassword"] = $("#cpassword").val();
                
                USER_PROCESSOR.backend_call(USER_CONSTANTS.function.register_user,USER_DATA.registration_json(vUserData))   
            }) 
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
    <br/>
    <div class="container">
	<div class="row">
            
            <article class="col-md-6 maincontent right marginTablet" style ="margin-left: 0px">
       		<div class="row">    
                        <div class="new_heading">
                           <h4><a href ='kaya'>Home</a> > Register</h4>
                       </div>
                   </div>
                   <div class="rating_div" style ="margin-bottom: -15px">   
                       <div class ='desc_heading'>
                           <h4 id ="vision">Register a new account</h4>
                       </div>
                    </div>
                <div class="">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="text-center text-muted">You can sign in here <a href="login">login</a></p>
                            <hr>
                           
                            <form class ='basic_form'>
                                <div class="top-margin">
                                    <label>First Name</label>
                                    <input id = 'firstname' type="text" class="form-control">
                                </div>
                                <div class="top-margin">
                                    <label>Last Name</label>
                                    <input id = 'lastname' type="text" class="form-control">
                                </div>
                                <div class="top-margin">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input id ='email' type="text" class="form-control">
                                </div>
                                <div class="top-margin">
                                    <label>Confirm Email Address <span class="text-danger">*</span></label>
                                    <input id ='cemail' type="text" class="form-control">
                                </div>
                                <div class="row top-margin">
                                    <div class="col-sm-6">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <input id ='password' type="password" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                            <label>Confirm Password <span class="text-danger">*</span></label>
                                            <input id ='cpassword' type="password" class="form-control">
                                    </div>
                                </div>
                                 <div class ="error"></div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="checkbox">
                                            <input type="checkbox"> 
                                            I've read the <a href="privacy">Terms and Conditions</a>
                                        </label>                        
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <div style ='float:left'  class ="loading_image"></div><button class="btn btn-action" type="submit">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
            </article>
           <aside class="col-md-4 sidebar sidebar-right marginRightTablet">
                  <div class="row widget">
                    <div class="col-xs-12">
                            <div class ='new_heading' style ="margin-left: -15px;margin-top:-10px;margin-right: -15px;margin-bottom: 10px">
                                <h4><a>Recover</a> > Account</h4>    
                            </div>  
                            <p><a href ="login/">I already have an account</a></p>
                            <p><a href ="accounts/password">I want to reset my password</a></p>
                            <p><a href ="accounts/activation">I want to resend the activation code</a></p>
                            <p><a href ="contact/">I am struggling to register</a></p>
                    </div>
                      
                </div>
            </aside>
	</div>
    </div>	
	
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>
