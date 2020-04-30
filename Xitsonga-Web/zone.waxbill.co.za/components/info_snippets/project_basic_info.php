<div class="module" style ="z-index: 1000">
    <div class="module-head"><h3>Basic Info</h3></div>
    <div class="module-body" style="min-height: 300px">
        <div ng-show="showLoader">
            <div style="height: 200px"></div>
        </div>
        <div ng-cloak>
            <div class ='control-group'>
                <label class= "control-label"><strong>Client ID</strong></label>
                <div class="controls">
                    <a href ="client/{{project.clientId}}">{{project.clientId}}</a>
                </div>

            </div>
            <div class ='control-group'>
                <label class="control-label"><strong>Project ID</strong></label>
                <div class="controls">
                    {{project.projectId}}
                </div>
            </div>
            <div class ='control-group' ng-repeat="detail in project.details">
                <label class="dcontrol-label"><strong>{{detail.typeName}}</strong></label>
                <div class="controls">
                    {{detail.entityDetailContent}}
                </div>
            </div>
        </div>
    </div>
</div>