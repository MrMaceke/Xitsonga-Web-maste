<?php
    $pageName = 'references';
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
                        <h4><a href ='kaya'>Home</a> > References</h4>
                    </div>
                </div>
                     <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">References</h4>
                    </div>
                </div>
                    <table width ="100%">
                       <tr>
                           <td>
                               <a class="red" target = "_tab" href ="http://www.vivmag.co.za/">VIVMag</a>
                               <p style ="font-size: 14px">
                                   Teleti Khosa<br/>
                                   VIVMag is a creative writers' blog that is regarded as the home of emerging writers eager to express themselves in hard-hitting societal issues and as well as other creative writing material.                               </p>

                           </td>
                       </tr>
                   </table>
                    <hr>
                   <table width ="100%">
                       <tr>
                           <td>
                               <a  class="red" target = "_tab" href ="http://madyondza.blogspot.co.za/">Madyondza</a>
                               <p style ="font-size: 14px">
                                   Kurhula Baloyi<br/>
                                   Madyondza is the first Xitsonga online dictionary. Online Library and Museum of All Things Tsonga (Tswa-Ronga)
                               </p>

                           </td>
                       </tr>
                   </table>
                   <hr>

                    <table width ="100%">
                       <tr>
                           <td>
                               <a  class="red" target = "_tab" href ="https://en.wikipedia.org/wiki/Tsonga_language">Wikipedia</a>
                               <p style ="font-size: 14px">
                                   The Xitsonga  Page<br/>
                                   The free encyclopedia that anyone can edit.
                               </p>

                           </td>
                       </tr>
                   </table>
                    
                   <hr>
                   
                   <table width ="100%">
                       <tr>
                           <td>
                               <a  class="red" target = "_tab" href ="http://www.polokwane247.com/listings/sasavona-publishers-booksellers-pty-ltd/">Sasavona Pocket Dictionary</a>
                               <p style ="font-size: 14px">
                                   Sasavona Publishers<br/>
                                   They Publish Books In Xitsonga, Tshivenda, Sepedi, Setswana, Isizulu, Xitshwa, English. Stockists Of Bibles, Hymn Books, Textbooks Christian Literature, Motivational Books.
                               </p>

                           </td>
                       </tr>
                   </table>
                   <hr>
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
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>