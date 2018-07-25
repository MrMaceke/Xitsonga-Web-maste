<?php
    $pageName = 'literature';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
     
    $sk = isset($_REQUEST['sk'])? $_REQUEST['sk']:"publications";
    
    if($aWebbackend->hasAccess($pageName)){
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
    <link href="assets/css/jqueryui.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src ="assets/js/notify.js"></script>
    <script type="text/javascript" src ="assets/js/jqueryui.js"></script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav.php';
    ?>
    <br/>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="kaya">Home</a></li>
            <li class="active">Literature</li>
        </ol>
        <hr>
        <div class="row">
            <aside class="col-md-4 sidebar sidebar-left">
                <div class="row widget">
                    <div class="col-xs-12">
                        <h4>Xitsonga literature</h4>
                         <p>
                        </p>
                    </div>
                </div>
                <div class="widget">
                    <ul class="list-unstyled list-spaces">
                        <li><a href="literature?sk=publications">Xitsonga books & publications</a></span></li>
                    </ul>
                </div>
                 
            </aside>
            <article class="col-md-8 maincontent">
                <header class="page-header">
                    <h1 class="page-title" style ="font-size: 28px">
                        <?php
                            $aTitle = str_replace("_"," ",$sk);
                            echo ucwords($aTitle);
                        ?>  
                    </h1>
                </header>
                <?php
                    $item_per_page = 10;
                    $data["entity_type"] = $aTitle;
                    $data["page"] = "sayings";
                   
                    require_once 'assets/html/pages_setup_1.php';

                    $data["start"] = $start;
                    $data["end"] = $end;
                    echo "<div style ='margin:0px'>".$aWebbackend->listEntityByType($data)."</div>";

                    //require_once 'assets/html/pages.php'; 
                ?>
            </article>
        </div>
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>