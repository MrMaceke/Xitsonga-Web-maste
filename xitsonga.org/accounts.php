<?php
    $pageName = 'accounts';
    require_once 'webBackend.php';
    
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"password";
    
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
                var id = $(this).attr('id');
                var vUserData = new Array();

                vUserData["firstName"] = $("#firstName").val();
                vUserData["email"] = $("#email").val();
                if(id === "activation"){
                    USER_PROCESSOR.backend_call(USER_CONSTANTS.function.resend_activation,USER_DATA.account_json(vUserData))
                }else if(id === "password"){
                    USER_PROCESSOR.backend_call(USER_CONSTANTS.function.reset_password,USER_DATA.account_json(vUserData))
                }
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
                           <h4><a href ='kaya'>Home</a> > Recover</h4>
                       </div>
                   </div>
                    <div class="rating_div" style ="margin-bottom: -15px">   
                       <div class ='desc_heading'>
                           <h4>
                            <?php
                                if($sk == "password"){
                                    echo "Reset my password";
                                }elseif($sk =="activation"){
                                    echo "Resend activation code";
                                }
                               
                            ?>
                           </h4>
                       </div>
                    </div>
                <div class="">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            
                            
                            <form class ='basic_form'>
                                <div class="top-margin">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input id ="firstName" type="text" class="form-control">
                                </div>
                                <div class="top-margin">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input id = "email" type="text" class="form-control">
                                </div>
                                <div class ="error"></div>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-8">
                                        <div style ='float:left'  class ="loading_image"></div>
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <button id = "<?php echo $sk; ?>"class="btn btn-action" type="submit">Process Request</button>
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
                            <p><a href ="accounts/password">I want to reset my password</a></p>
                            <p><a href ="accounts/activation">I want to resend the activation code</a></p>
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