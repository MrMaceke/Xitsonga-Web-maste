<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
   $en = Array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
   $aNumberCount = 0;
   echo "<br/>";
    while($aNumberCount < count($en)){
    	if($en[$aNumberCount] == $first){
         echo "<span class ='pagination current bigger_button'>".ucfirst($en[$aNumberCount])."</span>";
    	}else{
           echo "<a class ='pagination bigger_button' href ='manage/$sk?-=$en[$aNumberCount]'>".ucfirst($en[$aNumberCount])."</a>";
        }
        $aNumberCount ++;
    }
    
?>
