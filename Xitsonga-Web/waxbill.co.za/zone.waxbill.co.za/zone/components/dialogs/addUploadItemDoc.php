<?php

?>
<!-- Modal -->
<div class="modal hide addUploadItemDocDialog" id="addUploadItemDocDialog" tabindex="-5" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
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
                    <div class="controls">
                        <span id = "projectId"  class ='form-control text-left' style ="background: none;border:none;">{{project.projectId}}</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="col-sm-4 control-label text-right">Item Type</label>
                    <div class="controls" style ="margin-top: 5px">
                        <select  id = "itemType" class="form-control">
                            <option ng-repeat="systemProperty in systemProperties" value="{{systemProperty.propertyId}}" ng-if ="systemProperty.groupName === 'Item types' && systemProperty.propertyName === 'Document'">{{systemProperty.propertyName}}</option>
                        </select>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Item Basic' && systemProperty.propertyName !== 'Item Description' && systemProperty.propertyName !== 'Item Status'">
                    <div class="control-group">
                        <label class="col-sm-4 control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 5px">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="form-control itemDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                        </div>
                    </div>
                </div>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Item Basic' && systemProperty.propertyName === 'Item Description'">
                    <input  id = "{{systemProperty.propertyId}}" type="hidden" class="form-control itemDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}" value ="dummyText">
                </div>
                <div class="control-group">
                    <label class="col-sm-4 control-label"></label>
                    <form method="post" id="fileinfo" name="fileinfo" enctype="multipart/form-data" >
                       <div class="controls text-right" style ="margin-top: 5px">
                           <input type="file" name ="file"/>
                       </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addNewStageItemDocument(event)">Add Upload Document</button>
            </div>
        </div>
    </div>
</div>
