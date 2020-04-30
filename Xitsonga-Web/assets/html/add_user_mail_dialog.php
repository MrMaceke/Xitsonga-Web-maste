<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
    
    $aWebBackend  = new WebBackend();
    $data[name] = str_replace("_", " ", $_REQUEST['_']);
    $data[output] = "JSON";
    $aJSON = $aWebBackend->getEntityByURL($data,"JSON");
            
    $aJSON = json_decode($aJSON);
    
    $aTitle =  $aJSON->entity->description;
    $aID = $aJSON->entity->entity_id;
    $aDesc = "";
    $aType = $aJSON->entity->item_type;
    $aTypeValue = $aJSON->entity->typeDescription;
    if($aJSON->detail != null){
        foreach ($aJSON->detail->entityDetails as $aDetail) {
            if($aDetail->typeName == "English translation") {
                $aDesc = $aDetail->content;
                $aDetailTypeId = $aDetail->id;
                $aDetailTypeName = $aDetail->typeName;
            }
        }
    }
?>


<div id="openSendUserMailModal" class="modalbg modalbg_3">
  <div class="dialog" style ="margin-bottom: -15px">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <div class ='dialog_header'>
        <span style ="font-size: 15px;margin-left: -5px;">Help improve quality</span>
    </div>
    <form class ='user_suggestions_send_form'>
        
        <input type ="hidden" id ='aDetailTypeId' value="<?php echo ucfirst($aDetailTypeId); ?>"/>
        <input type ="hidden" id ='aDetailTypeName' value="<?php echo ucfirst($aDetailTypeName); ?>"/>
        <input type ="hidden" id ='aID' value="<?php echo ucfirst($aID); ?>"/>
        <input type ="hidden" id ='Type' value="<?php echo ucfirst($aType); ?>"/>
        <input type ="hidden" id ='TypeValue' value="<?php echo ucfirst($aTypeValue); ?>"/>
        
        <div class ='row' style="padding: 10px">
            <div class ='row'>
               <div class = 'col-lg-3'>Main description</div>
               <div class = 'col-lg-9'>
                   <input type ="text" id ='main' placeholder="Main description..." class="form-control" value="<?php echo ucfirst($aTitle); ?>"/>
               </div>
            </div>
            <div class ='row'>
               <div class = 'col-lg-3'>Translation</div>
               <div class = 'col-lg-9'>
                   <textarea id ='translation' placeholder="Translation..." class="form-control richText" rows="2" cols="50"><?php echo ucfirst($aDesc);?></textarea>
               </div>
            </div>
            <div class ='row'>
               <div class = 'col-lg-3'>Suggestion/s</div>
               <div class = 'col-lg-9'>
                   <textarea id ='suggestion' placeholder="Type suggestion..." class="form-control richText" rows="4" cols="50"></textarea>
               </div>
            </div>
            <div class ='row'>
                <div class = 'col-lg-3'>Email Address</div>
                <div class = 'col-lg-9'>
                    <?php
                        if($aWebBackend->getCurrentUser()->isSignedIn()){
                            $aEmail = strtolower($aWebBackend->getCurrentUser()->getEmail());
                        }
                    ?>
                    <input type ="text" id ='email' placeholder="Optional..." class="form-control" value="<?php echo $aEmail; ?>"/>
                </div>
            </div>
            <div class="rating_div" style ="margin-bottom: -20px">   
                <div class ='desc_heading'></div>
            </div>
           <div class ='row'>
               <div class = 'col-lg-3'><div class ='loading_image'></div></div>
               <div class = 'col-lg-7'>
                    <div class ="error"></div>
                    <input style = "margin:5px" id ='send_suggestion_email' class="btn main_action col-lg-5" type="submit" value="Update">
                    <a style = "margin:5px" href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel col-lg-5'>Cancel</a>
                </div>
            </div>
            
            <div class ='row'>
                <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'></div>
                </div>
            </div>
            <div class ='row'>
                <?php
                    if($aWebBackend->getCurrentUser()->isSignedIn()) {
                        $aEmail = strtolower($aWebBackend->getCurrentUser()->getEmail());
                    } else{
                        echo "<p style ='margin-left:20px;color:#EE2C2C'>Update will not reflect immediatly. We recommend you login</p>";
                    }
                ?>
            </div>
        </div>
    </form>
</div>
</div>