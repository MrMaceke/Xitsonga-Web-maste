<?php
    $pageName = 'search';
    require_once 'webBackend.php';
    require_once './php/TsongaTime.php';
    require_once './php/TsongaNumbers.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"search"; 
    
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
    <link href="assets/css/jqueryui.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
    <script>
        $(document).ready(function(e){
            $(".btn-action").click(function(e){
                e.preventDefault();
                window.location = "search?sk=" + $("#word").val();
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
            <article class="col-md-9 maincontent">
                <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > Search</h4>
                    </div>
                </div>
                 
                <?php
                    $aTitle = str_replace("_"," ",$sk);
                ?>  
       
                <div class ='row'>
                    <form class ='basic_form'>
                        <div class="top-margin col-md-8">
                            <input id ="word" type="text" class="form-control" placeholder="Search">
                            
                            <button class="btn btn-action margin_class search" type="submit">Search</button>
                        </div>
                   </form>
                </div>
                <hr>
                <?php
                    /*
                    echo "<div class ='heading_div'>";
                    echo ucfirst($aTitle)." - results from thousands of words.";
                    echo "</div>";
                    echo "<hr>";
                    **/
                    $data[name] = $aTitle;
                    $data[page] = "dictionary";
                    $data[sk] = $sk;
                    $data[like] = "0";
                    echo "<div style ='margin:15px' class = 'row'>".$aWebbackend->searchEntityByName($data)."</div>";

                ?>
               <script>
                (function() {
                  var cx = '015422035827386943578:nl1wakx_m00';
                  var gcse = document.createElement('script');
                  gcse.type = 'text/javascript';
                  gcse.async = true;
                  gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                      '//cse.google.com/cse.js?cx=' + cx;
                  var s = document.getElementsByTagName('script')[0];
                  s.parentNode.insertBefore(gcse, s);
                })();
              </script>
              <div class ="searchresults"><gcse:searchresults-only linktarget="_parent"></gcse:searchresults-only></div>
            </article>
             <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
                <?php
                   require_once './assets/html/side_nav_right.php';
               ?>
            </aside>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>