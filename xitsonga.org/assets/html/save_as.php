<?php
    $array = array("numbers","time","terminology","calendar","days","months","seasons", "measurements");
    
    if(!in_array(strtolower($sk), $array) AND $item == NULL) {
?>
<div class ='save_as_div'>
    <div class ="loading_image"></div>
    <div rel ='pdf' class ='link_group' id ="<?php echo $sk;?>">
        <a><img src ="assets/images/pdf.png"/> Save as PDF  </a>
    </div>
    <div rel='text' class ='link_group' id ="<?php echo $sk;?>">
        <a><img src ="assets/images/txt.png"/> Save as TXT  </a>
    </div>
</div>
<?php
    }
?>