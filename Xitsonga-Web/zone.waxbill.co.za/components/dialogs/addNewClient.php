<?php

?>
<div class ='addClientDialog' style ="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add Client</h3></div>
        <div class="module-body" ng-controller="systemPropertiesController">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid" ng-controller="systemPropertiesController">
                <input id = "entityType" type ="hidden" ng-repeat="systemProperty in systemProperties" value="{{systemProperty.propertyId}}" alt ="{{systemProperty.propertyName}}" ng-if ="systemProperty.groupName === 'System type' && systemProperty.propertyName === 'Client'"/>
                <div class="control-group">
                    <label class="control-label">Email Address</label>
                    <div class="controls" style ="margin-top: 2px">
                        <input  id = "emaillAddress" type="text" class="form-control span8" placeholder="Enter Email Address">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">User Role</label>
                    <div class="controls" style ="margin-top: 2px">
                        <select  id = "systemRole" class="form-control span8">
                            <option ng-repeat="systemRole in systemRoles" value="{{systemRole.roleId}}" ng-if ="systemRole.roleName === 'Client'">{{systemRole.roleName}}</option>
                        </select>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Client Basic'">
                    <div class="control-group">
                        <label class="control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 2px">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="form-control span8 userDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                        </div>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Client Address'">
                    <div class="control-group">
                        <label class="control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 2px">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="form-control span8 userDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls" style ="margin-top: 20px">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="addNewClient(event)">Add Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>