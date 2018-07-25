<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="openSendMailModal" class="modalbg">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">SYSTEM EMAIL</span>
    <hr style ="color:black">
    <form class ='basic_form send_form'>
        <table class ='boarder_row' width ="100%">
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Group</span><br/>
                	<span style ="font-size: 11px;">User group for message</span>
               	</td>
                <td>
                    <select id ='user_type' class="form-control">
                        <option value ="0">All system users</option>
                        <option value ="1">Administrator users</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Subject</span><br/>
                	<span style ="font-size: 11px;">Email subject</span>
               	</td>
                <td><textarea id ='subject' placeholder="Subject of email" class="form-control" rows="2" cols="50"></textarea></td>
            </tr>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Content</span><br/>
                	<span style ="font-size: 11px;">The content of the email</span>
               	</td>
                <td><textarea id ='content' placeholder="Content of email" class="form-control richText" rows="4" cols="50"></textarea></td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input id ='send_system_email' class="btn btn-action" type="submit" value="Send Email">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
        
        </form>
  </div>
</div>

<div id="openEditUserModal" class="modalbg modalbg_2">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase" id ="username_title">USER ACCESS LEVEL</span>
    <hr>    
    <form class ='basic_form update_user_form'>
        <table class ='dialog_table'>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Access Level</span><br/>
                	<span style ="font-size: 11px;">Update access level for user</span>
               	</td>
                <td>
                    <input type ="hidden" value="" id ="user_id"/>
                    <select id ='right' class="form-control">
                        <option value ="0">Basic user</option>
                        <option value ="1">Administrator</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input style ="margin: 5px" id ='update_user' class="btn btn-action" type="submit" value="Update Access Level">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
    </form>
  </div>
</div>