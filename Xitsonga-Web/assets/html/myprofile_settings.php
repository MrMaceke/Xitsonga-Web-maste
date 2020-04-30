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
            echo "Password";
        ?>  
     </h4>
</header>
<form class ='basic_form'>
<table class='display' cellspacing='0' style ="width:80%">
    <tr>
        <td style ="width: 30%">Current Password</td>
        <td><input class='form-control' type ="password" id = "current_password" value="<?php echo "";?>"/></td>
    </tr>
     <tr>
        <td>New Password</td>
        <td><input class='form-control' type ="password" id = "password" value="<?php echo "";?>"/></td>
    </tr>
    <tr>
        <td>Confirm Password</td>
        <td><input class='form-control' id ="cpassword" type ="password" value="<?php echo "";?>"/></td>
    </tr>
    <tr>
        <td><div class ='loading_image'></div></td>
        <td>
            <div class ="error"></div>
            <br/>
            <input id ='change_password' class="btn btn-action" type="submit" value="Change Password">
        </td>
    </tr>
</table>
</form>
<hr>