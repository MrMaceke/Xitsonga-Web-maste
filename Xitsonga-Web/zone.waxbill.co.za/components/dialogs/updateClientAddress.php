<?php

?>
<div class ='updateClientAddressDialog' style ="visibility: hidden" ng-controller="systemPropertiesController">
    <br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Update Address</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <input type="hidden" id ="userId"/>
                <div class="control-group">
                    <label class="control-label">Client ID</label>
                    <div class="controls" style ="margin-top: 2px">
                        <span id = "clientID"  class ='form-control' style ="background: none;border:none;">{{client.client.clientID}}</span>
                    </div>
                </div>
                <div class="hr-dashed"></div>
                <data ng-init="detailActivate=1"/>
                <div ng-repeat="systemProperty in systemProperties" ng-if ="systemProperty.groupName === 'Client Address'">
                    <div class ='control-group' ng-repeat="detail in client.client.details" ng-if ="detail.GroupName === 'Client Address' && detail.typeName === systemProperty.propertyName">
                        <label class="control-label">{{detail.typeName}}</label>
                        <div class="controls" style ="margin-top: 2px">
                            <input id = "{{systemProperty.propertyId}}" type="text" class="form-control span8 userDetails" placeholder="{{detail.typeName}}" value ="{{detail.entityDetailContent}}" alt ="{{detail.typeName}}">
                        </div>
                        <data ng-init="$detailActivate=2"/>
                    </div>
                    
                    <div class="control-group" ng-if="">
                        <label class="control-label">{{systemProperty.propertyName}}</label>
                        <div class="controls" style ="margin-top: 2px">
                            <input  id = "{{systemProperty.propertyId}}" type="text" class="form-control span8 userDetails" placeholder="{{systemProperty.propertyName}}" alt ="{{systemProperty.propertyName}}">
                        </div>
                    </div>
                </div>
                 
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls" style ="margin-top: 40px">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="updateAddress(event)">Update Address</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>