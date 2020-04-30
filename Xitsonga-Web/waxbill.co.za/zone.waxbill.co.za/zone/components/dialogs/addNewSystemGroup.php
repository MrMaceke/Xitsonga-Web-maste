<?php

?>
<div class ='addGroupDialog' style ="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add System Group</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <div class="control-group">
                    <label class="control-label">Group Name</label>
                    <div class="controls">
                        <input  id = "groupName" type="text" class="form-control span8" placeholder="Enter Group Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Group Priority</label>
                    <div class="controls">
                        <input  id = "groupValue" type="number" min="1" value="1" class="form-control span8" placeholder="Enter Group Priority">
                        <span class="help-block m-b-none">Priority is used to mandate which group appears first on the system</span> 
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Group Description</label>
                    <div class="controls">
                        <textarea  id = "groupDescription" class="form-control span8" placeholder="Enter Group Description"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controlso">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="addNewSystemGroup(event)">Add System Group</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>