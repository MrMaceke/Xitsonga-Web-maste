<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    if($total_items > 1){
        echo "<div class ='main_content_sub_heading' style ='margin-top:5px'>";
        echo "<div class ='pages' style ='overflow:hidden;z-index:1000'>";
            echo "<br/>";

            $directory = $data['page'];
            $item = $sk;
            
            $start_number = ($page + 1) <= 6? 1: $page - 4; 
            $end_number = ($page + 1) <= 6? 10: $page + 4; 
            
            $print = $end_number > $total_items? $total_items:$end_number; 
            echo "<span class ='pagination'>Pages $start_number - $print </span>";
            
            if($page == 0){
                $page = 1;
            }
            if($end_number > 10){
                if($data["sk"] == "tenses"){
                    $tense = $data["item"];
                    echo " <a class ='pagination' href ='$directory/$item?_=$tense&page=1'>First</a>"; 
                }else{
                    echo " <a class ='pagination' href ='$directory/$item&page=1'>First</a>"; 
                }
            }
            for($val = $start_number; $val <= $end_number && $val <= $total_items; $val ++){
               if(($page) == $val){
                    echo "<span class ='pagination current'>$val</span>";
               }else{
                    if($data["sk"] == "tenses"){
                        $tense = $data["item"];
                        echo " <a class ='pagination' href ='$directory/$item?_=$tense&page=$val'>$val</a> ";
                    }
                    else{
                        echo " <a class ='pagination' href ='$directory/$item&page=$val'>$val</a> ";
                    }
               }
        
            }
            if($total_items > 11 && ($val -1) != $total_items ){
               if($data["sk"] == "tenses"){
                   $tense = $data["item"];
                   echo " <span style ='float:left'></span><a class ='pagination' href ='$directory/$item?_=$tense&page=$total_items'>Last</a>"; 
               }else{
                    echo " <span style ='float:left'></span><a class ='pagination' href ='$directory/$item&page=$total_items'>Last</a>"; 
               }
            }
        echo "</div>";
        echo "</div>";
    }
?>