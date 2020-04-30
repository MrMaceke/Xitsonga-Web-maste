<div class="module">
    <div class="module-head"><h3>Basic</h3></div>
    <div class="module-body">
        <div ng-show="showLoader">
            <div style="height: 215px"></div>
        </div>
        <div class ='control-group'>
            <label class="control-label"><strong>Client ID</strong></label>
            <div class="controls">
                {{client.client.clientID}}
            </div>
        </div>
        <div class ='control-group'>
            <label class="control-label"><strong>Email Address</strong></label>
            <div class="controls">
                {{client.client.emailAddress}}
            </div>
        </div>
        <div class ='control-group' ng-repeat="detail in client.client.details" ng-if ="detail.GroupName === 'Client Basic'">
            <label class="control-label"><strong>{{detail.typeName}}</strong></label>
            <div class="controls">
                {{detail.entityDetailContent}}
            </div>
        </div>
    </div>
</div>
<div class="module">
    <div class="module-head"><h3>Address</h3></div>
    <div class="module-body">
         <div ng-show="showLoader">
            <div style="height: 215px"></div>
        </div>
        <div class ='control-group' ng-repeat="detail in client.client.details" ng-if ="detail.GroupName === 'Client Address'">
            <label class="control-label"><strong>{{detail.typeName}}</strong></label>
            <div class="controls">
                {{detail.entityDetailContent}}
            </div>
        </div>
    </div>
</div>