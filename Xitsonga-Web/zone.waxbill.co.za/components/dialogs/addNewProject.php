<?php

?>

<div class ='addProjectDialog' style ="visibility: hidden">
    <br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add Project</h3></div>
        <div class="module-body" ng-controller="systemPropertiesController">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <input id = "entityType" type ="hidden" ng-repeat="systemProperty in systemProperties" value="{{systemProperty.propertyId}}" alt ="{{systemProperty.propertyName}}" ng-if ="systemProperty.groupName === 'System type' && systemProperty.propertyName === 'Project'"/>
                <div class="control-group">
                    <label class="col-sm-2 control-label">Client ID</label>
                    <div style ="margin-top: -15px" class="controls">
                        <span id = "clientID"  class ='form-control span8' style ="background: none;border:none;">{{client.client.clientID}}</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="col-sm-2 control-label">Project Stage</label>
                    <div style ="margin-top: 2px" class="controls">
                        <select  id = "projectStage" class="form-control span8">
                            <option ng-repeat="systemProperty in systemProperties" value="{{systemProperty.propertyId}}" ng-if ="systemProperty.groupName === 'Project Stages' && systemProperty.propertyName ==='Initiation'">{{systemProperty.propertyName}}</option>
                        </select>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Project Basic'">
                    <div class="control-group">
                        <label class="col-sm-2 control-label">{{systemProperty.propertyName}}</label>
                        <div style ="margin-top: 2px" class="controls" ng-if ="systemProperty.propertyName ==='Release Forecast'">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="datePicker span8 form-control userDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}" onfocus="addDatePicker()">
                        </div>
                        <div style ="margin-top: 2px" class="controls" ng-if ="systemProperty.propertyName ==='Project Description'">
                            <textarea  id = "{{systemProperty.propertyId}}" class="span8 form-control userDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}"></textarea>
                        </div>
                        <div style ="margin-top: 2px" class="controls" ng-if ="systemProperty.propertyName !=='Release Forecast' && systemProperty.propertyName !=='Project Description'">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="form-control span8 userDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div style ="margin-top: 40px" class="controls">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="addNewProject(event)">Add Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>