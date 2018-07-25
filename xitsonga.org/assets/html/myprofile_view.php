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
<header class="page-header">
    <h4 class ='heading_deco redColor'>
        <?php
            echo "Info";
        ?>  
     </h4>
</header>
<p class ="profile_bio">
    <b>Bio</b> - Add your biography here.
</p>
<hr>
<table>
    <tr>
        <td><img src ="assets/images/email_1.png" alt="" width ='25' style ="margin-right: 10px;"/></td>
        <td><?php echo strtolower($aEmail); ?></td>
    </tr>
    <tr>
        <td><img src ="assets/images/tick.png" alt="" width ='25' style ="margin-right: 10px;"/></td>
        <td><?php echo strtolower($access); ?></td>
    </tr>
</table>
<hr>