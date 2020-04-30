<div class="module span12" ng-controller="projectTopBarPanelController">
    <div class="module-head"><h3>Summary</h3></div>
    <div class="module-body">
        <div class="row-fluid">
            <div class="span12">
                <div class ="span4">
                    <table>
                        <tr>
                            <td style="padding-right:10px;"><b>Account holder</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.accountHolder}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Project ID</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.projectId}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Project name</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.projectName}}</td>
                        </tr>
                    </table>
                </div>
                
                <div class ="span4">
                    <table>
                        <tr>
                            <td style="padding-right: 15px"><b>Project started</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.startDate}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Release forecast</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.releaseForecast}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Project finished</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.completedDate}}</td>
                        </tr>
                    </table>
                </div>
                
                <div class ="span4">
                    <table>
                        <tr>
                            <td style="padding-right: 15px"><b>Special message</b></td>
                            <td ng-cloak>-</td>
                        </tr>
                        <!--
                        <tr>
                            <td style="padding-right: 15px"><b>Deposit paid</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.depositPaid}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Outstanding amount</b></td>
                            <td ng-cloak>{{projectTopBarDashPanel.countItems.balance}}</td>
                        </tr>
                        -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
