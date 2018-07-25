<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $page = 0;
    $item_per_page = $item_per_page == ""?8:$item_per_page;
    if(isset($_REQUEST['page'])){
        $page = $_REQUEST['page'];
        if($page <= 0){
            $page = 0;
        }
    }
    
    $total_items = ceil($aWebbackend->getPublishedExerciseCount($data) / $item_per_page);
        
    $start = ($page - 1) * $item_per_page;
    if($start < 0){
        $start = 0;
    }
    $end = $item_per_page;
?>