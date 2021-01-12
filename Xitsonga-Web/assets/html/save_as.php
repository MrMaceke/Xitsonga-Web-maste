<?php
$array = array("numbers", "time", "terminology", "calendar", "days", "months", "seasons", "measurements");

if (!in_array(strtolower($sk), $array) AND $item == NULL) {
    ?>
    <div class ='save_as_div'>
        <div class ="loading_image"></div>
        <div rel ='pdf' class ='link_group' id ="<?php echo $sk; ?>">
            <a><img src ="assets/images/pdf.png"/> Save as PDF  </a>
        </div>
        <div rel='csv' class ='link_group' id ="<?php echo $sk; ?>">
            <a><img src ="assets/images/export_csv.png"/> Save as CSV  </a>
        </div>
        <div rel='text' class ='link_group' id ="<?php echo $sk; ?>">
            <a><img src ="assets/images/txt.png"/> Save as TXT  </a>
        </div>
        <?php if ($aWebbackend->hasAccess("manage") && false) {
            ?>

            <div rel='tsv' class ='link_group' id ="<?php echo $sk; ?>">
                <a><img src ="assets/images/export_csv.png"/> Save as TSV </a>
            </div>
        <?php } ?>
    </div>
    <?php }
?>
