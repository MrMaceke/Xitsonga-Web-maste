<?php

?>
<!-- Modal -->
<div class="modal hide addNewItemDialog" id="addNewItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="stageName"></h4>
            </div>
            <div class="modal-body form-horizontal row-fluid">
                <input id = "projectId" type ="hidden" value="{{project.projectId}}"/>
                <input id = "clientId" type ="hidden" value="{{project.clientId}}"/>
                <input id = "projectStage" type ="hidden"/>
                <div class="control-group">
                    <label class="col-sm-4 control-label text-right">Project ID</label>
                    <div class="controls" style ="margin-top: 5px">
                        <span id = "projectId"  class ='span8 form-control text-left' style ="background: none;border:none;">{{project.projectId}}</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="col-sm-4 control-label text-right">Item Type</label>
                    <div class="controls" style ="margin-top: 5px">
                        <select  id = "itemType" class="span8 form-control">
                            <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" ng-repeat="systemProperty in systemProperties" value="{{systemProperty.propertyId}}" ng-if ="systemProperty.groupName === 'Item types' && systemProperty.propertyName !== 'Document'">{{systemProperty.propertyName}}</option>
                        </select>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Item Basic' && systemProperty.propertyName !=='Item Status' && systemProperty.propertyName !=='Item Description'">
                    <div class="control-group">
                        <label class="col-sm-4 control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 5px">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="span8 form-control itemDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                        </div>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.propertyName ==='Item Description'">
                    <div class="control-group">
                        <label class="col-sm-4 control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 5px">
                            <textarea cols="3" id = "{{systemProperty.propertyId}}" type="text" class="span8 form-control itemDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}"></textarea>
                        </div>
                    </div>
                </div>
                <div ng-controller="systemUsersController">
                    <div class="control-group" ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'User Groups' && systemProperty.propertyName === 'Assigned Person'">
                        <label class="col-sm-4 control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 5px">
                            <select class="form-control span8 itemDetails" id = "{{systemProperty.propertyId}}" alt ="{{systemProperty.propertyName}}">
                                <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" ng-repeat = "systemUser in systemUsers" value="{{ systemUser.userKey }}" ng-if ="systemUser.roleName === 'Administrator' && systemUser.userKey !== 'W1234567'">
                                    Administrator - {{systemUser.firstName}} {{systemUser.lastName}}
                                </option>
                                <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" ng-repeat = "systemUser in systemUsers" value="{{ systemUser.userKey }}" ng-if ="systemUser.roleName === 'Consultant'">
                                    Consultant - {{systemUser.firstName}} {{systemUser.lastName}}
                                </option>
                                <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" ng-repeat = "systemUser in systemUsers" value="{{ systemUser.userKey }}" ng-if ="systemUser.roleName === 'Developer'">
                                    Developer - {{systemUser.firstName}} {{systemUser.lastName}}
                                </option>
                                <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" value ="">Not Assigned</option>
                                <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" value ="{{project.clientId}}">Client</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Item Basic' && systemProperty.propertyName ==='Item Status'">
                    <div class="control-group">
                        <label class="col-sm-4 control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 5px">
                            <select  id = "{{systemProperty.propertyId}}" class="form-control span8 itemDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                                <option style ="padding-left: 15px;border-left:none;border-right:none;border-radius: 0px;" ng-repeat = "property in systemProperties" value="{{ property.propertyId }}" ng-if ="property.groupName === 'Task Status' && property.propertyName ==='Task Pending'">{{ property.propertyName }}</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addNewStageItem(event)">Add Item</button>
            </div>
        </div>
    </div>
</div>
