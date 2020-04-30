<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
    //$aDTOUser = new DTOUser();
    $aFirstName = ucfirst(strtolower($aDTOUser->getFirstName()));
    $aLastname = ucfirst(strtolower($aDTOUser->getLastName()));
    $aEmail = ucfirst(strtolower($aDTOUser->getEmail()));
    $aRegistration = ucfirst(strtolower(""));
    $access = "Website User or Learner";
    
    if($aDTOUser->isAdmin()){
        $access = "Administrator";  
    }
?>
<table id ='dictionary_data_table' class='display' cellspacing='0'>
    <tr>
        <td style ="width: 40%">Notify me about new features </td>
        <td><?php echo "No";?></td>
    </tr>
    <tr>
        <td>Notify me about new products</td>
        <td><?php echo "No";?></td>
    </tr>
</table>
<hr>
<h1 class="page-title" style ="font-size: 20px">Subscriptions</h1>
<hr>
<table id ='dictionary_data_table' class='display' cellspacing='0'>
    <tr>
        <td style ="width: 40%">Educational phrases</td>
        <td><?php echo "No";?></td>
    </tr>
</table>
<hr>