<?php
    $pageName = 'terms';
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
    ?>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
   
    <div class="container">
    <br/>
       
        <div class="row">
            <!-- Article main content -->
            <article class="col-md-9 maincontent right marginTablet" style ="margin-left: 0px">
                <div class="row">    
                    <div class="new_heading">
                        <h4><a href ='kaya'>Home</a> > Legal</h4>
                    </div>
                </div>
                <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">CC license</h4>
                    </div>
                </div>
                    <p>
                        The <a href ='about'>Xitsonga.org</a> logos and website content are a copyright of <a>Xisonga Language Foundation</a> &COPY; 2019. Feel free to copy and redistribute the web site content in any medium or format. Xitsonga.org cannot revoke these freedoms as long as you follow the license terms.
                    </p>
                    <ul>
                        <li><b>Attribution</b> — You must give appropriate credit, provide a link to this website, and indicate if changes were made. You may do so in any reasonable manner, but not in any way that suggests <a>Xisonga Language Foundation</a> your usage of the content obtained from our platforms </li>
                        <li><b>NonCommercial</b> — You may not use the material for <a target ="_tab" href ='https://creativecommons.org/licenses/by-nc-nd/2.0/#'>commercial purposes</a> without approval.</li>
                        <li><b>Derivatives</b> — If you  <a target ="_tab" href ='https://creativecommons.org/licenses/by-nc-nd/2.0/#'>remix, transform, or build upon</a> the material, you may distribute the modified material provided you send a formal requested to Xitsonga.org.</li>
                    </ul>
                    <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">App Licenses</h4>
                    </div>
                   </div>
                    <p>
                        Applications below and the license herein granted shall not be copied, shared, distributed, re-sold, offered for re-sale, transferred or sub-licensed in whole or in part except that you may make one copy for archive purposes only.
                    </p>
                    <ul>
                   <li>
                        <a target="_tab" href ="https://play.google.com/store/apps/details?id=com.sneidon.ts.dictionary">Xitsonga Dictionary Android App</a>
                     </li>
                   <li>
                        <a target="_tab" href ="https://itunes.apple.com/app/id1361367210">Xitsonga Dictionary iOS App</a> 
                     </li>
                   <li>
                        <a target="_tab" href ="https://play.google.com/store/apps/details?id=com.sneidon.ts.wordsearch">Xitsonga WordSearch Android Game</a>
                    </li>
                    <li>
                        <a target="_tab" href ="https://itunes.apple.com/app/id1406013381">Xitsonga WordSearch iOS Game</a>
                    </li>
                    <li>
                        <a target="_tab" href ="https://play.google.com/store/apps/details?id=com.sneidon.ts.kids">Xitsonga For Kids Android App</a>
                    </li>
                    </ul>
                     <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'>
                        <h4 id ="vision">Disclaimer</h4>
                    </div>
                   </div>
                    <p>
                        We may change this policy from time to time by updating this page.
                        You should check this page from time to time to ensure that you are up to date with the changes.
                        <br/><br/>This policy is effective from 25 June 2019. 
                    </p>
                    <hr>
                    <br/>
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