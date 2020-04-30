<?php

?>
<div class="module">
    <div class="module-head"><h3>Dashboard</h3></div>
</div>
<div class="btn-box-row row-fluid" ng-controller="consultantDashboardPanelController">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <a href="projects/" class="btn-box big span3"><i class="icon-coffee"></i><b>{{consultDashPanel.countItems.projects}}</b><p class="text-muted">Projects</p></a>
                <a href="tasks/" class="btn-box big span3"><i class="icon-check"></i><b>{{consultDashPanel.countItems.mytasks}}</b><p class="text-muted">Tasks</p></a>
                <a href="tickets/" class="btn-box big span3"><i class="icon-info-sign"></i><b>{{consultDashPanel.countItems.tickets}}</b><p class="text-muted">Tickets</p></a>
                <a href="invoices/" class="btn-box big span3"><i class="icon-trophy"></i><b>{{consultDashPanel.countItems.invoices}}</b><p class="text-muted">Invoices</p></a>
            </div>
        </div>
    </div>
</div>