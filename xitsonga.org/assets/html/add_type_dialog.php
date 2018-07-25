<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="openModal" class="modalbg">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Types</span>
    <hr style ="color:black">
    <form class ='basic_form add_type_form'>
        <table width ="100%" class ='boarder_row'>
            <tr>
                <td style="width:40%">
                    <span style ="font-size: 15px;text-transform:uppercase">Description</span><br/>
                    <span style ="font-size: 11px;">A description of the type.</span>
                </td>
                <td><input class="form-control" id ='description' placeholder="Type a description..." type ='text' size ='30'/></td>
            </tr>
             <tr>
                 <td>
                    <span style ="font-size: 15px;text-transform:uppercase">Type</span><br/>
                    <span style ="font-size: 11px;">A type of the type.</span>
                 </td>
                <td>
                    <select id ='type' class="form-control">
                        <option value ="1">Main</option>
                        <option value ="2">Content</option>
                        <option value ="3">Type</option>
                        <option value ="4">Tag</option>
                        <option value ="5">Description</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input id ='add_type' class="btn btn-action" type="submit" value="Add Type">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
        
        </form>
  </div>
</div>


<div id="openEditModal" class="modalbg" >
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">Types</span>
    <hr style ="color:black">
    <form class ='basic_form edit_type_form'>
       <table width ="100%" class ='boarder_row'>
             <tr>
                <td style="width:40%">
                    <span style ="font-size: 15px;text-transform:uppercase">Delete</span><br/>
                    <span style ="font-size: 11px;">For authorised personnel only</span>
                </td>
                <td>
                    <input id ='delete_type' style ="width:20px;height: 20px" type="checkbox">
                </td>
            </tr>
            <tr>
                <td style="width:40%">
                    <span style ="font-size: 15px;text-transform:uppercase">Description</span><br/>
                    <span style ="font-size: 11px;">A description of the type.</span>
                </td>
                <td>
                    <input class="form-control" placeholder="Type a description..." id ='description' type ='text' size ='30'/>
                    <input type ="hidden" id ="type_id"/>
                </td>
            </tr>
             <tr>
                <td>
                    <span style ="font-size: 15px;text-transform:uppercase">Type</span><br/>
                    <span style ="font-size: 11px;">A type of the type.</span>
                </td>
                <td>
                    <select id ='type' class="form-control">
                        <option value ="1">Main</option>
                        <option value ="2">Content</option>
                        <option value ="3">Type</option>
                        <option value ="4">Tag</option>
                        <option value ="5">Description</option>
                    </select>
                </td>
            </tr>
            <tr>
               <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input id ='edit_type' class="btn btn-action" type="submit" value="Update Type">
                     <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
    </form>
  </div>
</div>