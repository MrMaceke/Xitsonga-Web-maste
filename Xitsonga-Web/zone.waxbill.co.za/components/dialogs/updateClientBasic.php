<?php

?>

<div class ='updateClientBasicDialog' style ="visibility: hidden">
    <br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Update Client</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <input type="hidden" id ="userId"/>
                <div class="control-group">
                    <label class="control-label">Client ID</label>
                    <div class="controls" style ="margin-top: 2px">
                        <span id = "clientID"  class ='form-control span8' style ="background: none;border:none;">{{client.client.clientID}}</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Email Address</label>
                    <div class="controls" style ="margin-top: 2px">
                        <input  id = "emaillAddress" type="text" class="form-control span8" placeholder="Enter Email Address" value ="{{client.client.emailAddress}}">
                    </div>
                </div>
                <div ng-repeat="detail in client.client.details" ng-if ="detail.GroupName === 'Client Basic'">
                    <div class ='control-group'>
                        <label class="control-label">{{detail.typeName}}</label>
                        <div class="controls" style ="margin-top: 2px">
                            <input id = "{{detail.entityDetailId}}" type="text" class="form-control span8 userDetails" placeholder="{{detail.typeName}}" value ="{{detail.entityDetailContent}}" alt ="{{detail.typeName}}">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls" style ="margin-top: 40px">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="updateClient(event)">Update Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>