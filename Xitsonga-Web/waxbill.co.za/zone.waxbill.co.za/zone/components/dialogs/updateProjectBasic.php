<?php

?>
<div class ='updateProjectBasicDialog' style ="visibility: hidden">
    <br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Update Project</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <input type="hidden" id ="userId"/>
                <div class="control-group">
                    <label class="control-label">Project ID</label>
                    <div class="controls" style ="margin-top:5px">
                        <span id = "projectId"  class ='form-control span8' style ="background: none;border:none;">{{project.projectId}}</span>
                    </div>
                </div>
                <div class="hr-dashed"></div>
                 <div ng-repeat="detail in  project.details">
                    <div class ='control-group'>
                        <label class="control-label" ng-if ="detail.typeName !=='Project Status'">{{detail.typeName}}</label>
                        <div class="controls" style ="margin-top:5px" ng-if ="detail.typeName ==='Release Forecast'">
                            <input id = "{{detail.entityDetailId}}" type="text" class="span8 datePicker form-control userDetails" placeholder="{{detail.entityDetailContent}}" value ="{{detail.entityDetailContent}}" alt ="{{detail.typeName}}" onfocus="addDatePicker()">
                        </div>
                        <div class="controls" style ="margin-top:5px" ng-if ="detail.typeName ==='Project Description'">
                            <textarea id = "{{detail.entityDetailId}}" type="text" class="span8 form-control userDetails" placeholder="{{detail.entityDetailContent}}" alt ="{{detail.typeName}}">{{detail.entityDetailContent}}</textarea>
                        </div>
                        <div class="controls" style ="margin-top:5px" ng-if ="detail.typeName !=='Release Forecast' && detail.typeName !=='Project Status' && detail.typeName !=='Project Description'">
                            <input id = "{{detail.entityDetailId}}" type="text" class="span8 form-control userDetails" placeholder="{{detail.entityDetailContent}}" value ="{{detail.entityDetailContent}}" alt ="{{detail.typeName}}">
                        </div>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls" style ="margin-top:40px">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="updateProject(event)">Update Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
</div>