<?php

?>
<div class ='updateUserDialog' style ="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Update System User</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <input type="hidden" id ="userId"/>
                <div class="control-group">
                    <label class="control-label">Client ID</label>
                    <div class="controls">
                        <span id = "clientID"  class ='form-control span8' style ="background: none;border:none;"></span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Email Address</label>
                    <div class="controls">
                        <input  id = "emaillAddress" type="text" class="form-control span8" placeholder="Enter Email Address">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">User Role</label>
                    <div class="controls">
                        <select  id = "systemRole" class="form-control span8">
                            <option ng-repeat="systemRole in systemRoles" value="{{systemRole.roleId}}">{{systemRole.roleName}}</option>
                        </select>
                    </div>
                </div>
                <div class="updateUserDialogAjaxDiv" style ="margin-top: 10px"></div>
                <div class="control-group">
                     <label class="control-label"></label>
                    <div class="controls">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="updateSystemUser(event)">Update System User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>