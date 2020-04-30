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
            echo "Edit Bio";
        ?>  
     </h4>
</header>
<form class ='basic_form'>
<table class='display' cellspacing='0' style ="width:80%">
    <tr>
        <td style ="width: 30%">LastName</td>
        <td><input class='form-control' id = "lastname" value="<?php echo $aLastname;?>"/></td>
    </tr>
     <tr>
        <td>First Name</td>
        <td><input class='form-control' id = "firstname" value="<?php echo $aFirstName;?>"/></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type ="hidden" id = "email" value="<?php echo $aEmail;?>"/><i style ="padding-top: 5px;"><?php echo $aEmail; ?></i></td>
    </tr>
    <tr>
        <td><div class ='loading_image'></div></td>
        <td>
            <div class ="error"></div>
            <br/>
            <input id ='update_user' class="btn btn-action" type="submit" value="Update Profile">
        </td>
    </tr>
</table>
</form>
<hr>