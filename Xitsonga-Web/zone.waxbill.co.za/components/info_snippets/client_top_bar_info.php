<div class="module span12" ng-controller="clientTopBarPanelController">
    <div class="module-head"><h3>Summary</h3></div>
    <div class="module-body">
        <div class="row-fluid">
            <div class="span12">
                <div class ="span4">
                    <table>
                        <tr>
                            <td style="padding-right:10px;"><b>Account holder</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.accountHolder}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Client ID</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.clientId}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Email address</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.emailAddress}}</td>
                        </tr>
                    </table>
                </div>
                
                <div class ="span4">
                    <table>
                        <tr>
                            <td style="padding-right: 15px"><b>Projects</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.projects}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Support tickets</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.supportTickets}}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px"><b>Previous login</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.lastLogin}}</td>
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
                            <td style="padding-right: 15px"><b>Last payment</b></td>
                            <td ng-cloak>{{clientTopBarDashPanel.countItems.previous_payment}}</td>
                        </tr>
                        -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
