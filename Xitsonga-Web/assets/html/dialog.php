<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="openModal" class="modalbg">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"];?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;font-weight: bold"><?php
            $show = str_replace("_"," ",$item);
            echo ucfirst($show);
        ?>
    </span>
    <hr style ="color:black">
    <form class ='basic_form'>
        <table>
            <tr>
                <td> Select type</td>
                <td>
                    <select id ="itemType" class="form-control">
                        <?php
                            $content_type = 5;
                            $array = $aWebbackend->listItemTypesType($content_type);

                            foreach ($array as $key => $value) {
                                echo "<option value ='$value[description]'>$value[description]</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td>
                    <textarea id ='addDescText' placeholder="Type your message here..." class="form-control" rows="4" cols='30'></textarea>
                </td>
            </tr>
             <tr>
                <td></td>
                <td>
                    <div class ='error'></div>
                    <?php
                        $aWebBackend = new WebBackend();
                        $aDTOUser = $aWebBackend->getCurrentUser();
                        if($aDTOUser->isSignedIn()){

                     ?>
                    <input id ='addDescription' class="btn btn-action" type="submit" value="Add description">
                    <?php
                        }else{
                    ?>
                    <p class="text-right"><a class="btn btn-primary btn-large" href ='login'>You are not signed in</a></p>
                    <?php
                        }
                    ?>
                </td>
            </tr>
    </table>
    </form>
  </div>
</div>