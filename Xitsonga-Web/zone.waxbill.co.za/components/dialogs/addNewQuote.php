<?php

?>
<div class ='addNewQuoteDialog' style ="visibility: hidden" ng-controller="financialDevelopmentDealsController">
    <br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Generate Quote</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <input id = "entityType" type ="hidden" ng-repeat="systemProperty in systemProperties" value="{{systemProperty.propertyId}}" alt ="{{systemProperty.propertyName}}" ng-if ="systemProperty.groupName === 'System type' && systemProperty.propertyName === 'Project'"/>
                <div class="control-group">
                    <label class="col-sm-2 control-label">Project ID</label>
                    <div style ="margin-top: 5px" class="controls">
                        <span id = "projectId"  class ='form-control span8' style ="background: none;border:none;">{{project.projectId}}</span>
                    </div>
                </div>
                <div class="hr-dashed"></div>
                <div ng-repeat="detail in  project.details">
                    <div class ='control-group'>
                        <label class="control-label" ng-if ="detail.typeName ==='Project Name'">{{detail.typeName}}</label>
                        <div class="controls" style ="margin-top:5px" ng-if ="detail.typeName ==='Project Name'">
                            <span id = ""  class ='form-control span8' style ="background: none;border:none;">{{detail.entityDetailContent}}</span>
                        </div>
                    </div>
                </div>
                <div class ="deals_div"></div>
                <div class="control-group" style ="margin-bottom: 40px;margin-top: 10px">
                    <label class="control-label"><b><a href="" style="color:green" onclick="add_deal()">Add Deal + </a></b></label>
                    <div class="controls" style ="margin-top: 5px"><b><a href="" style="color:red" onclick="remove_deal(0)">- Remove Deal </a></b></div>
                </div>
                <div class="hr-dashed" style="border-bottom:1px solid #C4C4C4"></div>
                 <div class="control-group"  style ="margin-top: 5px">
                    <label class="col-sm-2 control-label" id ="quoteTotalPrice">R0.00</label>
                    <div style ="margin-top: 5px" class="controls">
                        <span id = ""  class ='form-control span8' style ="background: none;border:none;">Total Price</span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="generateQuote(event)">Generate Quote</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
