<?php
    $pageName = 'team';
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
        <div class="container app">
            <br/>
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 0px">
                <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > <a href ='team/'>Team</a> </h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -15px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Meet the team</h4>
                    </div>
                </div>
                <p style ="font-size: 14px">
                    We are dedicated to publishing resources for easy access by all. 
                </P>
                <hr>
                <div class="row">
                    <div class ="col-sm-12">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/Hlulani.png" width ="150" class="pull-left" style ='border:1px solid gray'/>
                                    <a>Hlulani Baloyi</a> <br/>
                                    <b>Product Manager</b>
                                     <p style ="font-size: 14px">
                                        Hlulani is an African traveler who loves coding.
                                        She manages the Xitsonga.org project and all its products, tools and platforms. 
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-12">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/ntshuxeko.jpg" width ="150" class="pull-left" style ='border:1px solid gray'/>
                                    <a>Ntshuxeko Ndhlovu</a> <br/>
                                    <b>Editor In Chief</b>
                                     <p style ="font-size: 14px">
                                        Ntshuxeko is an introvert who likes creating magic behind the scenes. She's working in the finance field. 
                                        Ntshuxeko manages content quality for all Xitsonga.org language tools and products.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-12">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/Hlawu.png" width ="150" class="pull-left" style ='border:1px solid gray'/>
                                    <a>Hlawuleka Maswanganyi</a> <br/>
                                    <b>Lead Engineer</b>
                                     <p style ="font-size: 14px">
                                        Hlawuleka is a Frontend Engineer who enjoys reading and exploring new tech.
                                        He develops and maintains products, tools, and platforms for Xitsonga.org. 
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-12">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/mk.jpg" width ="150" class="pull-left" style ='border:1px solid gray'/>
                                    <a>Mukondli Dumela</a> <br/>
                                    <b>Editorial and Research</b>
                                     <p style ="font-size: 14px">
                                        Mukondli is a Software Developer and a technology enthusiast.
                                        He focuses on research for content and technology for Xitsonga.   
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-12">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/akani.jpg" width ="150" class="pull-left" style ='border:1px solid gray'/>
                                    <a>Akani Maluleke</a> <br/>
                                    <b>Social and Opportunities</b>
                                     <p style ="font-size: 14px">
                                        Akani is a Fuel Controller at Total.
                                        He focuses on marketing and engaging stake holders for new opportunities for Xitsonga.org.   
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4>Contact Us</h4>
                    </div>
                </div>
                <br/>
                <div>
                    Give us a shout <a>info@xitsonga.org</a>
                </div>
                <br/><br/>
            </article>
             <aside class="col-md-3 sidebar sidebar-right marginRightTablet fillWebsite">
                <?php
                    require_once './assets/html/side_nav_right.php';
                ?>
            </aside>
     </div>
    <?php
        require_once './assets/html/footer.php';
        require_once './assets/html/script_2.php';
    ?>
</body>
</html>