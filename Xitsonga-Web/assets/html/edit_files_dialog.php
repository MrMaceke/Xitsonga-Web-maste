<div id="openFileUpdateModal" class="modalbg modalbg_3">
  <div class="dialog" style ="margin-bottom: -15px">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <div class ='dialog_header'>
        <span style ="font-size: 15px;margin-left: -5px;">Update file</span>
    </div>
    <form class ='edit_file_form'>
        
        <div class ='row' style="padding: 10px">
            <div class ='row'>
               <div class = 'col-lg-3'>File name</div>
               <div class = 'col-lg-9'>
                   <input type ="text" id ='name' placeholder="Main description..." class="form-control" disabled/>
               </div>
            </div>
            
            <div class ='row'>
               <div class = 'col-lg-3'>File content</div>
               <div class = 'col-lg-9'>
                   <textarea id ='content' placeholder="File content" class="form-control richText" rows="4" cols="50"></textarea>
               </div>
            </div> 
     
            <div class="rating_div" style ="margin-bottom: -20px">   
                <div class ='desc_heading'></div>
            </div>
           <div class ='row'>
               <div class = 'col-lg-3'><div class ='loading_image'></div></div>
               <div class = 'col-lg-7'>
                    <div class ="error"></div>
                    <input style = "margin:5px" id ='send_edit_file_form' class="btn main_action col-lg-5" type="submit" value="Update">
                </div>
            </div>
           
            <br/>
            <div class ='row'>
                <div class="rating_div" style ="margin-bottom: -20px">   
                    <div class ='desc_heading'></div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>