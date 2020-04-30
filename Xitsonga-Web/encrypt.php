<?php
    $pageName = 'encrypt';
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
                vUserData["password"] = $("#password").val();

                USER_PROCESSOR.backend_call(USER_CONSTANTS.function.login_user,USER_DATA.login_json(vUserData))
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
            <?php if($aWebbackend->activateAccount($sk) != "0") {?>
            <article class="col-md-8 maincontent right marginTablet" style ="margin-left: 0px">
       		<br/>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 class="thin text-center">Reset your password</h3>
                            <hr><br/>
                            <h5>Password code has expired.</h5>
                        </div>
                </div>
                <br/>
            </article>
            <?php } else { ?>
            <article class="col-md-6 maincontent right marginTablet" style ="margin-left: 0px">
                    <div class="row">    
                        <div class="new_heading">
                           <h4><a href ='kaya'>Home</a> > Reset</h4>
                       </div>
                   </div>
                   <div class="rating_div" style ="margin-bottom: -15px">   
                       <div class ='desc_heading'>
                           <h4 id ="vision">Reset your password</h4>
                       </div>
                    </div>
                <div class="">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <hr>
                            
                            <form class ='basic_form'>
                                <div class="top-margin">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <span id ="email" type="text" class="form-control"></span>
                                </div>
                                <div class="top-margin">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input id = "password" type="password" class="form-control">
                                </div>
                                 <div class="top-margin">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <input id = "password" type="confirm_password" class="form-control">
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-8">
                                        
                                    </div>
                                    <div class="col-lg-4 text-right">
                                         <div style ='float:left'  class ="loading_image"></div><button style ='float:right' class="btn btn-action" type="submit">Reset Password</button>
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                    </div>
                </div>
            </article>
            <?php } ?>
            <aside class="col-md-4 sidebar sidebar-right marginRightTablet">
                  <div class="row widget">
                    <div class="col-xs-12">
                            <div class ='new_heading' style ="margin-left: -15px;margin-top:-10px;margin-right: -15px;margin-bottom: 10px">
                                <h4><a>Recover</a> > Account</h4>    
                            </div>  
                            <p><a href ="register/">I want a new account</a></p>
                            <p><a href ="accounts/password">I want to reset my password</a></p>
                            <p><a href ="accounts/activation">I want to resend the activation code</a></p>
                            <p><a href ="contact/">I am struggling to login</a></p>
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