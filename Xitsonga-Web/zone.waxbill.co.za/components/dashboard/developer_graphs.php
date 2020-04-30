<?php

?>

<div class="module">
    <div class="module-head"><h3>Recent projects</h3></div>
    <div class="module-body">
        <table class="table table-hover" ng-controller="clientProjectsController">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Forecast</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat = "project in projects" ng-if="$index < 4">
                    <th scope="row">{{ $index + 1 }}</th>
                    <td><a href="project/{{ project.projectId }}">{{ project.projectId }}</a></td>
                    <td ng-repeat = "detail in project.details" ng-if="detail.typeName === 'Project Name'">{{ detail.entityDetailContent }}</td>
                    <td><span ng-repeat = "detail in project.details" ng-if="detail.typeName === 'Release Forecast'">{{ detail.entityDetailContent }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>