<?php

?>

<div class="module">
    <div class="module-head">
        <h3>Project Progress</h3>
    </div>
    <div class="module-body">
        <div id="graph" style="height: 300px; width: 100%;">
	</div>
    </div>
</div>

<div class="module" ng-controller="systemPropertiesController" ng-cloak>
    <div class="module-head"><h3>Projects</h3></div>
    <div class="module-body">
        <table class="table table-hover" ng-controller="clientProjectsController" ng-cloak>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Stage</th>
                    <th>Forecast</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat = "project in projects" ng-if="$index < 10 && projects.length > 0">
                    <th scope="row">{{ $index + 1 }}</th>
                    <td><a href="project/{{ project.projectId }}">{{ project.projectId }}</a></td>
                    <td ng-repeat = "detail in project.details" ng-if="detail.typeName === 'Project Name'">{{ detail.entityDetailContent }}</td>
                    <td>{{ project.projectStage}}</td>
                    <td><span ng-repeat = "detail in project.details" ng-if="detail.typeName === 'Release Forecast'">{{ detail.entityDetailContent }}</span></td>
                </tr>
            </tbody>
        </table>
        <div ng-if="projects.status === -999">
            {{ projects.message }}
        </div>
    </div>
</div>


<div class="module">
    <div class="module-head"><h3>Invoices</h3></div>
    <div class="module-body" ng-controller="invoicesController" style="min-height: 100px">
        <table class="table table-hover" ng-if="invoices.status !== -999">
            <thead>
                <tr>
                    <th style ="width: 10%">Ticket</th>
                    <th style ="width: 10%">Project ID</th>
                    <th style ="width: 10%">Reference</th>
                    <th style ="width: 20%">Description</th>
                    <th style ="width: 10%">Amount</th>
                    <th style ="width: 10%">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat = "invoice in invoices">
                    <td><a title ='View Invoice' href="invoice/{{invoice.paymentId}}">{{invoice.paymentId}}</a></td>
                    <td>{{invoice.projectId}}</td>
                    <td>{{invoice.reference}}</td>
                    <td>{{invoice.description}}</td>
                    <td>{{invoice.amount}}</td>
                    <td>{{invoice.paymentDate.split(' ')[0]}}</td>
                </tr>
            </tbody>
        </table>
        <div ng-if="invoices.status === -999">
            {{ invoices.message }}
        </div>
    </div>
</div>

<div class="module">
    <div class="module-head"><h3>Tickets</h3></div>
    <div class="module-body" ng-controller="supportTicketsController" style="min-height: 100px">
        <table class="table table-hover" ng-if="supportTickets.status !== -999">
            <thead>
                <tr>
                    <th style ="width: 15%">Ticket</th>
                    <th style ="width: 15%">Project ID</th>
                    <th style ="width: 40%">Description</th>
                    <th style ="width: 15%">Due Date</th>
                    <th style ="width: 15%">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat = "supportTicket in supportTickets">
                    <td><a title ='View Ticket' href="ticket/{{supportTicket.supportId}}">{{supportTicket.supportId}}</a></td>
                    <td>{{supportTicket.projectId}}</td>
                    <td>{{supportTicket.description}}</td>
                    <td>{{supportTicket.dueDate.split(' ')[0]}}</td>
                    <td>{{supportTicket.status}}</td>
                </tr>
            </tbody>
        </table>
        <div ng-if="supportTickets.status === -999">
            {{ supportTickets.message }}
        </div>
    </div>
</div>