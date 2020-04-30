<?php

?>
<div class ='addPropertyDialog' style="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add System Property</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <div class="control-group">
                    <label class=" control-label">Property Name</label>
                    <div class="controls">
                        <input  id = "propertyName" type="text" class="form-control span8" placeholder="Enter Property Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Property Priority</label>
                    <div class="controls">
                        <input  id = "propertyPriority" type="number" min="1"class="form-control span8" placeholder="Enter Priority">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Property Group</label>
                    <div class="controls">
                        <select  id = "propertyGroup" class="form-control span8">
                            <option ng-repeat="systemGroup in systemGroups" value="{{systemGroup.groupId}}">{{systemGroup.groupName}}</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Property Description</label>
                    <div class="controls">
                        <textarea  id = "propertyDescription" class="form-control span8" placeholder="Enter Property Description"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="addNewSystemProperty(event)">Add System Property</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>