<?php
$pageName = 'team';
require_once 'webBackend.php';

$aWebbackend = new WebBackend();

if (!$aWebbackend->hasAccess($pageName)) {
    
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
                        <h4 id ="vision">Xitsonga.org team</h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ='new_heading'>
                        <h4></h4>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class ="col-sm-12">
                        <table width ="100%">
                            <tr>
                                <td>
                                    <img src ="assets/images/mk.jpg" class="pull-left featured-large-news" style ='border:1px solid gray'/>
                                    <a>Mukondleteri Dumela</a> <br/>
                                    <b>Founder and Developer</b>
                                    <p style ="font-size: 14px">
                                        A Software Developer and a technology enthusiast with keen focus on Africa culture and languages.
                                        Mukondleteri focuses on research for content and technology for Xitsonga. 
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
                                    <img src ="assets/images/Hlulani.png"class="pull-left featured-large-news" style ='border:1px solid gray'/>
                                    <a>Hlulani Baloyi</a> <br/>
                                    <b>Product Manager</b>
                                    <p style ="font-size: 14px">
                                        An African traveler who loves coding.
                                        Hlulani manages the Xitsonga.org project and all its products, tools and platforms. 
                                        <br/>
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
                                    <img src ="assets/images/Ntshuxi.jpeg" width ="150" class="pull-left featured-large-news" style ='border:1px solid gray'/>
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
                                    <img src ="assets/images/hlawuleka_pro.jpeg" class="pull-left featured-large-news" style ='border:1px solid gray'/>
                                    <a>Hlawuleka Maswanganyi</a> <br/>
                                    <b>Lead Engineer</b>
                                    <p style ="font-size: 14px">
                                        A Frontend Engineer who enjoys reading and exploring new technologies.
                                        Hlawuleka manages development teams and develops and maintains products, tools, and platforms for Xitsonga.org. 
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
                                    <img src ="assets/images/Irvin.png" class="pull-left featured-large-news" style ='border:1px solid gray'/>
                                    <a>Irvin Maceke</a> <br/>
                                    <b>Software Developer</b>
                                    <p style ="font-size: 14px">
                                        A Graduate in NDip Business information technology.
                                        Irvin develops and maintains products, tools, and platforms for Xitsonga.org. 
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
                                    <img src ="assets/images/akani.jpg" width ="150" class="pull-left featured-large-news" style ='border:1px solid gray'/>
                                    <a>Akani Maluleke</a> <br/>
                                    <b>Social and Opportunities</b>
                                    <p style ="font-size: 14px">
                                        A Fuel Controller at Total.
                                        Akani focuses on marketing and engaging stake holders for new opportunities for Xitsonga.org.   
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