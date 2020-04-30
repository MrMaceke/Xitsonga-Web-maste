<?php

?>
<div ng-controller="adminDashboardPanelController">
    <div class="module">
        <div class="module-head"><h3>System</h3></div>
        <div class="module-body">
            <?php require_once './components/menu/admin_dashboard_menu.php'; ?>
        </div>
    </div>
    <div class="btn-box-row row-fluid">
        <div class="span12" ng-cloak>
            <div class="row-fluid">
                <div class="span12">
                    <a target = "_tab" href="logging/syslogs.txt" class="btn-box big span3"><i class="icon-file"></i><b ng-cloak>{{adminDashPanel.countItems.systemErrors}}</b><p class="text-muted">System Logs</p></a>
                    <a href="users/" class="btn-box big span3"><i class="icon-group"></i><b ng-cloak>{{adminDashPanel.countItems.users}}</b><p class="text-muted">System Users</p></a>
                    <a href="groups/" class="btn-box big span3"><i class="icon-sitemap"></i><b ng-cloak>{{adminDashPanel.countItems.groups}}</b><p class="text-muted">System Groups</p></a>
                    <a href="properties/" class="btn-box big span3"><i class="icon-tags"></i><b ng-cloak>{{adminDashPanel.countItems.properties}}</b><p class="text-muted">System Properties</p></a>
                </div>
            </div>
        </div>
    </div>
</div>