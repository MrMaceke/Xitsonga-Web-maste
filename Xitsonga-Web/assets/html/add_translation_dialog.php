<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="openTranslationModal" class="modalbg">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Translation Configurations</span>
    <hr style ="color:black">
    <form class ='basic_form add_translation_form'>
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
                        <option value ="xitsonga_spelling">Xitsonga Spelling</option>
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
                    <input type="checkbox" id="swapLeft"/> Swap left <br/>
                    <input type="checkbox" id="swapRight"/> Swap right <br/>
                    <input type="checkbox" id="pushFirst"/> Push first <br/>
                    <input type="checkbox" id="pushLast"/> Push last <br/><br/>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input id ='add_translation' class="btn btn-action" type="submit" value="Add Config">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
        
        </form>
  </div>
</div>