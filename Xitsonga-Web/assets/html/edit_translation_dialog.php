<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="openEditTranslationModal" class="modalbg">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Translation Configurations</span>
    <hr style ="color:black">
    <form class ='basic_form edit_translation_form'>
        <input type="hidden" id ="editConfig"/>
        <table width ="100%" class ='boarder_row'>
            <tr>
                <td style="width:30%">
                    <span style ="font-size: 15px;text-transform:uppercase">Item</span><br/>
                    <span style ="font-size: 11px;">An item to be replaced.</span>
                </td>
                <td><input class="form-control" id ='item' placeholder="Type a item" type ='text' size ='30'/></td>
            </tr>
            <tr>
                <td style="width:30%">
                    <span style ="font-size: 15px;text-transform:uppercase">Replacement</span><br/>
                    <span style ="font-size: 11px;">A replace for item.</span>
                </td>
                <td><input class="form-control" id ='replacement' placeholder="Type a replacement" type ='text' size ='30'/></td>
            </tr>
            <tr>
                <td>
                    <span style ="font-size: 15px;text-transform:uppercase">Language</span><br/>
                 </td>
                <td>
                    <select id ='language' class="form-control">
                        <option value ="english">English</option>
                        <option value ="english_spelling">English Spelling</option>
                        <option value ="xitsonga">Xitsonga</option>
                        <option value ="xitsonga_xitsonga">Xitsonga Spelling</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="width:30%">
                    <span style ="font-size: 15px;text-transform:uppercase">Pattern</span><br/>
                    <span style ="font-size: 11px;">A pattern of replacement or replacement</span>
                </td>
                <td><input class="form-control" id ='pattern' placeholder="Type a pattern" type ='text' size ='30'/></td>
            </tr>
            <tr>
                <td style="width:30%">
                    <span style ="font-size: 15px;text-transform:uppercase">Configuration Flags</span><br/>
                    <span style ="font-size: 11px;">Flag for configuration</span>
                </td>
                <td>
                    <input type="checkbox" id="swap_left"/> Swap left <br/>
                    <input type="checkbox" id="swap_right"/> Swap right <br/>
                    <input type="checkbox" id="push_first"/> Push first <br/>
                    <input type="checkbox" id="push_last"/> Push last <br/><br/>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input id ='edit_translation' class="btn btn-action" type="submit" value="Update Config">
                    <input style="float:right" id ='remove_translation' class="btn btn-danger" type="submit" value="Remove Config">
                </td>
            </tr>
        </table>
        
        </form>
  </div>
</div>