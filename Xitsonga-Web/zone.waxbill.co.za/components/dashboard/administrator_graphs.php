<?php

?>


<div class="module">
    <div class="module-head">
        <h3>Roles</h3>
    </div>
    <div class="module-body">
        <div class="chart pie donut interactive">
            <div id="dashReport" class ="graph"></canvas>
            </div>
            <div id="hover">
            </div>
        </div>
    </div>
</div>
<div class="module">
    <div class="module-head">
        <h3>Users</h3>
    </div>
    <div class="module-body">
        <table class="table table-hover table-striped" ng-controller="systemUsersController">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client ID</th>
                    <th>Email Address</th>
                    <th>User Role</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat = "systemUser in systemUsers" ng-if="$index < 10">
                    <th scope="row">{{ systemUser.priority }}</th>
                    <td>{{ systemUser.userKey }}</td>
                    <td>{{ systemUser.email }}</td>
                    <td>{{ systemUser.roleName }}</td>
                </tr>
            </tbody>
        </table>
        <br/>
    </div>
</div>