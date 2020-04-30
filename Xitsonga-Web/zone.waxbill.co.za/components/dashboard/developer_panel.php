<?php

?>
<div class="btn-box-row row-fluid" ng-controller="consultantDashboardPanelController">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <a href="tasks/" class="btn-box big span6"><i class="icon-tasks"></i><b>{{consultDashPanel.countItems.mytasks}}</b><p class="text-muted">My Tasks</p></a>
                <a href="projects/" class="btn-box big span6"><i class="icon-coffee"></i><b>{{consultDashPanel.countItems.projects}}</b><p class="text-muted">Projects</p></a>
            </div>
        </div>
    </div>
</div>