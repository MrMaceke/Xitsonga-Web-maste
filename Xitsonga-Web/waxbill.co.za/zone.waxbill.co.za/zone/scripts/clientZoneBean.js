/**
 * Constants for ClientZoneBean
 * 
 * @type string or integer value of requested constant
 */
var CLIENTZONE = {
    "server":{
         "current" : "/"
     },
    /**
     * @returns {Array} a list of ClientZoneBean functions
     */
    "API":{
        "clientZoneBeanV1" : "php/ClientZoneBean.php"
    },
     
    "function":{
        "validateResourceAccess" : "validateResourceAccess",
        "retrieveSystemUserInformation" : "retrieveSystemUserInformation",
        "retrieveLoggedUser": "retrieveLoggedUser",
        "retrieveSystemRoles" : "retrieveSystemRoles",
        "loginSystemUser" : "loginSystemUser",
        "updateSystemUserCredentials" : "updateSystemUserCredentials",
        "addNewSystemGroup" : "addNewSystemGroup",
        "addNewSystemUser" : "addNewSystemUser",
        "addNewClient" : "addNewClient",
        "addNewProject" : "addNewProject",
        "addNewTicket" : "addNewTicket",
        "addNewDeal"   : "addNewDeal",
        "addNewStageItem" : "addNewStageItem",
        "updateDeal" : "updateDeal",
        "updateClient" : "updateClient",
        "updateProject" : "updateProject",
        "updateClientDetails" : "updateClientDetails",
        "updateStageItem" : "updateStageItem",
        "uploadStageItemDocument" : "uploadStageItemDocument",
        "uploadProjectContractDocument" : "uploadProjectContractDocument",
        "updateSystemGroup" : "updateSystemGroup",
        "updateSystemUser" : "updateSystemUser",
        "updateLogFlagStatus" : "updateLogFlagStatus",
        "clearLogs" : "clearLogs",
        "resetUserPassword" : "resetUserPassword",
        "deleteSystemGroup" : "deleteSystemGroup",
        "deleteSystemProperty" : "deleteSystemProperty",
        "addNewSystemProperty" : "addNewSystemProperty",
        "updateSystemProperty" : "updateSystemProperty",
        "progressSupportTicket" : "progressSupportTicket",
        "progressProjectToNextStage" : "progressProjectToNextStage",
        "reassignQuoteToProject": "reassignQuoteToProject",
        "retrieveSystemSupportTickets" : "retrieveSystemSupportTickets",
        "retrieveInvoice": "retrieveInvoice",
        "retrieveInvoices" : "retrieveInvoices",
        "retrieveDeals" : "retrieveDeals",
        "retrieveQuotes" : "retrieveQuotes",
        "retrieveDeal" : "retrieveDeal",
        "removeDeal" : "removeDeal",
        "retrievePaymentsByProjectId" : "retrieveInvoicesForProject",
        "retrieveSystemSupportTicket" : "retrieveSystemSupportTicket",
        "retrieveSystemUsers":"retrieveSystemUsers",
        "retrieveClients":"retrieveClients",
        "retrieveClient":"retrieveClient",
        "retrieveSystemUser":"retrieveSystemUser",
        "retrieveProject":"retrieveProject",
        "retrieveProjects":"retrieveProjects",
        "retrieveUserTasks":"retrieveUserTasks",
        "retrieveProjectStageItem":"retrieveProjectStageItem",
        "retrieveProjectStageItems": "retrieveProjectStageItems",
        "retrieveProjectDocuments":"retrieveProjectDocuments",
        "retrieveProjectsForClient": "retrieveProjectsForClient",
        "retrieveSystemGroups":"retrieveSystemGroups",
        "retrieveSystemProperties":"retrieveSystemProperties",
        "retrieveStagesForProject":"retrieveStagesForProject",
        "retrieveAdminPanelDashboard":"retrieveAdminPanelDashboard",
        "retrieveClientTopBarPanel":"retrieveClientTopBarPanel",
        "retrieveProjectTopBarPanel":"retrieveProjectTopBarPanel",
        "retrieveConsultantPanelDashboard":"retrieveConsultantPanelDashboard"
     },
     "status":{
         "success": 999,
         "warning": 998,
         "not_found": -404,
         "failed": -999
     },
     "error":{
         "critical": "A critical error has occured",
         "connection": "Server not responding"
     },
     "icon":{
         "fa_unlock": "fa-unlock",
         "fa_cloud_download": "fa-cloud-download",
         "fa_check_square": "fa-check-square",
         "fa_wrench": "fa-wrench",
         "fa_info_circle":"fa-info-circle",
         "fa_times_circle": "fa-times-circle"
     }
};

function accessController($scope,$http) {
    var path = window.location.pathname.toLowerCase();
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === "" || path === "login"){
        path = "index";
    }
    
    var json = {"pageName":path};
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemUserInformation + "&data=" + JSON.stringify(json);
    $http.get(url).success( function(response) {
       if(response.status === CLIENTZONE.status.failed 
           || response.status === CLIENTZONE.status.success) {
            $scope.loggedUser = response; 
        }else {
            $scope.loggedUser = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
   });
}

function systemUserController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
    var json = {"pageName":path};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemUserInformation + "&data=" + JSON.stringify(json);
    NProgress.start();
    $scope.showLoader = true;
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed 
           || response.status === CLIENTZONE.status.success) {
            $scope.systemUser = response; 
        }else {
            $scope.systemUser = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function clientTopBarPanelController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    
    var json = {"pageName":path,"clientId":clientId};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveClientTopBarPanel + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            alert(response.message);
            $scope.clientTopBarDashPanel = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.clientTopBarDashPanel = response; 
        }else {
            $scope.clientTopBarDashPanel = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
    });
}


function projectTopBarPanelController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    
    var json = {"pageName":path,"projectId":clientId};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveProjectTopBarPanel + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            alert(response.message);
            $scope.projectTopBarDashPanel = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.projectTopBarDashPanel = response; 
        }else {
            $scope.projectTopBarDashPanel = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
    });
}

function adminDashboardPanelController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
    var json = {"pageName":path};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveAdminPanelDashboard + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            $scope.adminDashPanel = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.adminDashPanel = response; 
        }else {
            $scope.adminDashPanel = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
    });
}


function consultantDashboardPanelController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
    var json = {"pageName":path};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveConsultantPanelDashboard + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            $scope.consultDashPanel = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.consultDashPanel = response; 
        }else {
            $scope.consultDashPanel = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
    });
}

function systemRolesController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
    var json = {"pageName":path};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemRoles + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            $scope.systemRoles = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.systemRoles = response.systemRoles; 
        }else {
            $scope.systemRoles = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
    });
}

function systemGroupsController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
    
    $scope.showLoader = true;
    var json = {"pageName":path};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemGroups + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            $scope.systemGroups = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.systemGroups = response.systemGroups;
            if(json.pageName === "groups"){
                UTILITY.createSystemGroupTable($scope.systemGroups);
            }
        }else {
           $scope.systemGroups = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function userTasksController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "tasks";
    }
    
    $scope.showLoader = true;
    var json = {"pageName":path};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveUserTasks + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed) {
            $scope.tasks = {"status":CLIENTZONE.status.failed,"message":response.message}; 
            if(path === "timeline"){
                UTILITY.calenderObject = $('#timelineCalendar').fullCalendar({
                    events:[],
                    defaultView: 'basicWeek',
                    header: {
                        left: 'prev,today,next',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    eventRender: function(event, element) {
                        $(element).tooltip({title: event.tip});             
                    },eventClick: function(event) {
                        if (event.url) {
                            window.open(event.url);
                            return false;
                        }
                    }
                });
            }
        }else if(response.status === CLIENTZONE.status.success) {
            $scope.tasks = response.tasks;
            if(json.pageName === "tasks"){
               UTILITY.createUserTasksTable($scope.tasks);
            }else if(path === "timeline"){
                
                var events = [];
                for(index =0; index < $scope.tasks.length; index ++) {
                    task = $scope.tasks[index];
                    status = "";
                    name = "";
                    desc = "";
                    for (var aIndex = 0; aIndex < task.details.length; aIndex ++) {
                        detail = task.details[aIndex];
                        if(detail.typeName === "Item Status") {
                            status = detail.entityDetailContent;
                        }else if(detail.typeName === "Item Description") {
                            desc = detail.entityDetailContent;
                        }else if(detail.typeName === "Item Name") {
                            name = detail.entityDetailContent;
                        }
                    }
                    
                    date = task.dateCreated.split(" ");
                    eventColor = "#2E8B57";
                    
                    var currentDate = new Date();
                    
                    currentDate.setDate(currentDate.getDate() + 2); 
                    var day = currentDate.getDate();
                    var month = currentDate.getMonth() + 1;
                    var year = currentDate.getFullYear();
                    
                    end  = date[0];
                    if(status === "Task Pending"){
                        eventColor = "#3B91AD";
                        end = year + "-"+ month + "-" + day;
                    }else if(status === "Task Suspended"){
                        eventColor = "#CD0000";
                    }
                    
                    events.push({
                        title: "Task - " + task.itemId + " " + name + " - " + status,
                        start: date[0],
                        end:end,
                        tip: "Task - " + task.itemId + " " + desc + " - " + status,
                        url: "project/" + task.projectId,
                        color  : "#800080"
                    });
                }

                UTILITY.calenderObject = $('#timelineCalendar').fullCalendar({
                    events:events,
                    defaultView: 'basicWeek',
                    header: {
                        center: 'title',
                        left: 'prev,today,next',
                        right: 'year,month,agendaWeek',
                    },
                    eventAfterRender: function(event, element) {
                        element.attr('title', event.tip);
                        $(element).tooltip();
                    },eventClick: function(event) {
                        if (event.url) {
                            window.open(event.url,"_self");
                            return false;
                        }
                    }
                });
                var aJson = {
                    "pageName" : "timeline",
                    "entityTypeName": "project"
                };
                PROCESSOR.backend_call(CLIENTZONE.function.retrieveProjects,aJson);
            }
        }else {
           $scope.tasks = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
           $("#taskTable").remove();
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function supportTicketController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "ticket";
    }
    
    var params = path.split("/"); 
    var supportId = params.length > 1? params[1]: params[0];
    var json = {"pageName":path,"supportId":supportId};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemSupportTicket + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.supportTicket = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.supportTicket = response.supportTicket;
        }else {
           $scope.supportTicket = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}


function invoiceController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "invoice";
    }
    
    var params = path.split("/"); 
    var invoiceId = params.length > 1? params[1]: params[0];
    var json = {"pageName":path,"invoiceId":invoiceId};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveInvoice + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.invoice = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.invoice = response.invoice;
        }else {
           $scope.invoice = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function dealsController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "deals";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    var json = {"pageName":params[0],"clientId":clientId};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveDeals + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.deals = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.deals = response.developmentDeals;
            if(params[0] === "deals") {
                UTILITY.createDealsTable($scope.deals);
            }
        }else {
           $scope.deals = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}


function quotesController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "quotes";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    var json = {"pageName":params[0],"clientId":clientId};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveQuotes + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.quotes = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.quotes = response.quotes;
            if(params[0] === "quotes") {
                UTILITY.createQuotesTable($scope.quotes);
            }
        }else {
           $scope.quotes = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}


function invoicesController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "invoices";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    var json = {"pageName":params[0],"clientId":clientId};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveInvoices + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.invoices = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.invoices = response.invoices;
            if(params[0] === "invoices") {
                UTILITY.createInvoicesTable($scope.invoices);
            }
        }else {
           $scope.invoices = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function supportTicketsController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "tickets";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    var json = {"pageName":params[0],"clientId":clientId};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemSupportTickets + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.supportTickets = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.supportTickets = response.supportTickets;
            if(params[0] === "tickets") {
                UTILITY.createSupportTicketsTable($scope.supportTickets);
            }
        }else {
           $scope.supportTickets = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function clientProjectsController($scope,$http) {
    
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "projects";
    }
    
    $scope.showLoader = true;
    var json = {"pageName":path,"entityTypeName":"Project"};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveProjects + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.projects = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.projects = response.projects;
            if(path === "projects") {
                UTILITY.createAllProjectsTable($scope.projects);
            }else if(path === "dashboard") {
                systemProperties = ["Initiation","Agreement","Development","Testing","Release"];
                colors = ["#EE9A00","#104E8B","#03A89E","#8E388E","#800000","#EE9A00","#104E8B","#03A89E","#808080","#800000"];
                barData = [];
                for(var index = 0; index < systemProperties.length; index ++){
                    var count = 0;
                    for(var i = 0; i < response.projects.length; i ++){
                        project = response.projects[i];
                        
                        if(systemProperties[index] === project.projectStage) {
                            count ++;
                        }
                    }
                    barData.push(
                        {x:systemProperties[index], Projects: count}
                    );
                }
                index = -1;
                Morris.Bar({
                    element: 'graph',
                    data: barData,
                    xkey: 'x',
                    ykeys: ['Projects'],
                    gridIntegers: true,
                    ymin: 0,
                    barGap:1,
                    barSizeRatio:0.4,
                    grid:false,
                    labels: ['Projects','z'],
                    barColors: function (row, series, type) {
                        return colors[index ++];
                    }
                  });
            }
        }else {
           $scope.projects = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function clientsController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "users";
    }
    
    $scope.showLoader = true;
    var json = {"pageName":path,"entityTypeName":"Client"};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveClients + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.clients = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.clients = response.clients;
            if(path === "clients") {
                UTILITY.createClientsTable($scope.clients);
            }
        }else {
           $scope.clients = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function projectController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "project";
    }
    
    var params = path.split("/"); 
    var projectId = params.length > 1? params[1]: params[0];
    var json = {"pageName":path,"entityTypeName":"Project","projectId":projectId};
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveProject + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.project = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.project = response.project;
            $scope.showMainMenu = true;
            
            for(index = 0 ; index < $scope.project.details.length ; index ++){
                detail = $scope.project.details[index];
                if(detail.typeName ==="Project Status" && detail.entityDetailContent ==="Project Completed") {
                    $scope.showMainMenu = false;
                }
            }
            
        }else if(response.status === CLIENTZONE.status.not_found){
            window.location = "404/";
        } else {
           $scope.project = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    }).error( function(response) {
        $scope.showLoader = false;
        $scope.project = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
    });
}

function projectsController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "clients";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    $scope.showLoader = true;
    var json = {"pageName":path,"entityTypeName":"Project","clientId":clientId};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveProjectsForClient + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.projects = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.projects = response;
        }else {
           $scope.projects = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function currentClientController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "clients";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    $scope.showLoader = true;
    var json = {"pageName":path,"entityTypeName":"Client","clientId":clientId};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveClient + "&data=" + JSON.stringify(json);
    NProgress.start();
     $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           window.location = "404/";
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.client = response;
        }else {
           $scope.client = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}


function loggedUserController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "clients";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];
    $scope.showLoader = true;
    var json = {"pageName":path};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveLoggedUser + "&data=" + JSON.stringify(json);
    NProgress.start();
     $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.client = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.client = response;
        }else {
           $scope.client = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}

function systemUsersController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "users";
    }
    
    $scope.showLoader = true;
    var json = {"pageName":path};
 
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemUsers + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.systemUsers = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.systemUsers = response.systemUsers;
            UTILITY.scope = $scope;
            if(path === "users") {
                var doughnutData = [];
                
                var users = response.systemUsers;
                var rolesHash = {};
                for(var aRoleIndex = 0; aRoleIndex < users.length; aRoleIndex ++) {
                    count = rolesHash[users[aRoleIndex].roleName];
                    if (count === undefined){
                        rolesHash[users[aRoleIndex].roleName] = 1;
                    } else {
                        rolesHash[users[aRoleIndex].roleName] =  count + 1;
                    }
                }
                var doughnutData = [];
                var colors = ["success","warning","default","danger"];
                var index = 0;
                for (var role in rolesHash){
                    var aTemp =  {
                            color: colors[index ++],
                            number: rolesHash[role],
                            percentage: ((rolesHash[role] / users.length) * 100).toFixed(0),
                            roleName: role
                        };
   
                    doughnutData.push(aTemp);
                }
                
                $scope.userRolesPecentages = doughnutData;
                UTILITY.createSystemUserTable($scope.systemUsers);
            }else if(path === "dashboard"){
                var doughnutData = [];
                
                var users = response.systemUsers;
                var rolesHash = {};
                for(var aRoleIndex = 0; aRoleIndex < users.length; aRoleIndex ++) {
                    count = rolesHash[users[aRoleIndex].roleName];
                    if (count === undefined){
                        rolesHash[users[aRoleIndex].roleName] = 1;
                    } else {
                        rolesHash[users[aRoleIndex].roleName] =  count + 1;
                    }
                }
                var doughnutData = [];
                for (var role in rolesHash){
                    var aTemp =  {
                            data: [[0,rolesHash[role]]],
                            label: "(" + rolesHash[role] + ") " + role
                        };
   
                    doughnutData.push(aTemp);
                }
                
                $.plot($("#dashReport"), doughnutData,{
		    series: {
		        pie: {
		            innerRadius: 2,
		            show: true
		        }
		    },
		    grid: {
		        hoverable: true,
		        clickable: true
		    }
		});

                $("#dashReport").bind("plothover", pieHover);
                $("#dashReport").bind("plotclick", pieClick);
            }
        }else {
           $scope.systemUsers = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
        $scope.showLoader = false;
    });
}
 function pieHover(event, pos, obj) {
    if (!obj)
        return;
    percent = parseFloat(obj.series.percent).toFixed(2);
    $("#hover").html('<span>' + obj.series.label + ' - ' + percent + '%</span>');
}

function pieClick(event, pos, obj) {
    if (!obj)return;
    percent = parseFloat(obj.series.percent).toFixed(2);
    FEEDBACK.AddSuccesMessageToHTML('' + obj.series.label + 's : ' + percent + '%', CLIENTZONE.icon.fa_info_circle);
}

function systemPropertiesController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(CLIENTZONE.server.current, "");
    
    if(path === ""){
        path = "clients";
    }
    
    var params = path.split("/"); 
    var clientId = params.length > 1? params[1]: params[0];

    var json = {"pageName":params[0]};
    
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemProperties + "&data=" + JSON.stringify(json);
    NProgress.start();
    $scope.showLoader = true;
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
            $scope.showLoader = false;
            $scope.systemProperties = {"status":CLIENTZONE.status.failed,"message":response.message }; 
        } else if(response.status === CLIENTZONE.status.success) {
            $scope.systemProperties = response.systemProperties;
            if(json.pageName === "properties"){
                $scope.showLoader = false;
                UTILITY.createSystemPropertyTable($scope.systemProperties);
            }else if(json.pageName === "project"){
                $scope.projectId = clientId;
                UTILITY.scope = $scope;
                var aJson = {
                    "projectId"     : clientId
                };
                PROCESSOR.backend_call(CLIENTZONE.function.retrieveStagesForProject,aJson);
            }
        }else {
           $scope.showLoader = false;
           $scope.systemProperties = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
        NProgress.done();
    });
}
/**
 * Backend calls for user functions
 *  
 * @returns HTML response 
 */
var FILE_PROCESSOR = {};
FILE_PROCESSOR.backend_call = function(call,jsonArray,fileData){
    var url = CLIENTZONE.API.clientZoneBeanV1 +"?type=" + call + "&data=" + JSON.stringify(jsonArray);
    $.ajax({
        dataType: 'json',
        type: "POST",
        url:url,
        data: fileData,
        processData: false,
        contentType: false,
        beforeSend: function(xhr) {
            switch(call){
                case CLIENTZONE.function.uploadProjectContractDocument:
                case CLIENTZONE.function.uploadStageItemDocument:{
                   FEEDBACK.PutHTMLinProcessingState();
                }
                break;
            }
        }, 
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case CLIENTZONE.function.uploadProjectContractDocument: {
                    if(data.status === CLIENTZONE.status.success){
                        $(".addUploadContractDialog input[type=text]").val("");
                        $(".addUploadContractDialog input[type=file]").val("");
                        $(".addUploadContractDialog textarea").val("");
   
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
        
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;         
                }
                case CLIENTZONE.function.uploadStageItemDocument: {
                   if(data.status === CLIENTZONE.status.success){
                        $(".addUploadItemDocDialog input[type=text]").val("");
                        $(".addUploadItemDocDialog input[type=file]").val("");
                        $(".addUploadItemDocDialog textarea").val("");
                        
                        if(UTILITY.stageItemsTable !== null) {
                            UTILITY.addItemToStageItemsTable(UTILITY.stageItemsTable, data.item);
                            FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }else {
                            $(".addUploadItemDocDialog input[type=text]").val("");
                            $(".addUploadItemDocDialog input[type=file]").val("");
                            $(".addUploadItemDocDialog textarea").val("");
                             FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML("File successfully uploaded",CLIENTZONE.icon.fa_check_square);
                        }
                    }else if(data.status === CLIENTZONE.status.failed){
        
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;     
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case CLIENTZONE.function.uploadProjectContractDocument:
                case CLIENTZONE.function.uploadStageItemDocument:{
                    if(jqXHR.status === 0) {
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.connection,CLIENTZONE.icon.fa_cloud_download);
                    } else { 
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.critical,CLIENTZONE.icon.fa_cloud_download);
                    }
                    break;
                }
            }   
        }
    });
}
/**
 * Backend calls functions
 *  
 * @returns HTML response 
 */
var PROCESSOR = {};
/**
 * 
 * @param {string} call
 * @param {JSON} jsonArray 
 * @returns HTML response based on type of call
 */
PROCESSOR.backend_call = function(call,jsonArray){
    var url = CLIENTZONE.API.clientZoneBeanV1 +"?type=" + call + "&data=" + JSON.stringify(jsonArray);
    $.ajax({
        dataType: 'json',
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                case CLIENTZONE.function.loginSystemUser:
                case CLIENTZONE.function.addNewSystemGroup:
                case CLIENTZONE.function.addNewSystemUser:
                case CLIENTZONE.function.addNewTicket:
                case CLIENTZONE.function.addNewClient:
                case CLIENTZONE.function.addNewProject:
                case CLIENTZONE.function.addNewDeal:
                case CLIENTZONE.function.addNewStageItem:
                case CLIENTZONE.function.updateDeal:
                case CLIENTZONE.function.updateSystemGroup:
                case CLIENTZONE.function.deleteSystemGroup:
                case CLIENTZONE.function.deleteSystemProperty:
                case CLIENTZONE.function.resetUserPassword:
                case CLIENTZONE.function.addNewSystemProperty:
                case CLIENTZONE.function.updateSystemProperty:
                case CLIENTZONE.function.updateSystemUser:
                case CLIENTZONE.function.updateSystemUserCredentials:
                case CLIENTZONE.function.updateClient:
                case CLIENTZONE.function.updateLogFlagStatus:
                case CLIENTZONE.function.updateProject:
                case CLIENTZONE.function.updateStageItem:
                case CLIENTZONE.function.updateClientDetails:
                case CLIENTZONE.function.clearLogs:
                case CLIENTZONE.function.reassignQuoteToProject:
                case CLIENTZONE.function.progressProjectToNextStage:
                case CLIENTZONE.function.progressSupportTicket:
                case CLIENTZONE.function.retrieveDeal:
                case CLIENTZONE.function.removeDeal:
                case CLIENTZONE.function.retrieveStagesForProject:
                case CLIENTZONE.function.retrievePaymentsByProjectId:
                case CLIENTZONE.function.retrieveProjects:
                case CLIENTZONE.function.retrieveProjectStageItem:
                case CLIENTZONE.function.retrieveProjectDocuments:
                case CLIENTZONE.function.retrieveProjectStageItems:
                case CLIENTZONE.function.retrieveSystemUser:
                case CLIENTZONE.function.retrieveClient:{
                   FEEDBACK.PutHTMLinProcessingState();
                }
                break;
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case CLIENTZONE.function.loginSystemUser:{
                    if(data.status === CLIENTZONE.status.success){
                        FEEDBACK.PutHTMLinNormalState();
                        window.location = data.message ;
                    }else if(data.status === CLIENTZONE.status.failed){
                       FEEDBACK.AddErrorMessageToHTML(data.message, CLIENTZONE.icon.fa_unlock);
                    }
                    break;
                }
                case CLIENTZONE.function.updateSystemUserCredentials:{
                    if(data.status === CLIENTZONE.status.success){
                        $("form input").val("");
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_unlock);
                    }
                    break;
                } 
                case CLIENTZONE.function.addNewSystemGroup:{
                    if(data.status === CLIENTZONE.status.success){
                        $("form input").val("");$("form textarea").val("");
                        
                        if(UTILITY.systemGroupTable !== null) {
                            UTILITY.addGroupToSystemGroupTable(UTILITY.systemGroupTable,data.systemGroup);
                            FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }else {
                            FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.updateSystemGroup:{
                    if(data.status === CLIENTZONE.status.success){
                        aRow = UTILITY.systemGroupTable.row(".selected");
                        
                        aData = aRow.data();
                        
                        aData[UTILITY.systemGroupTableIndex["Group Name"]] = jsonArray.groupName;
                        aData[UTILITY.systemGroupTableIndex["Group Value"]] = jsonArray.groupValue;
                        aData[UTILITY.systemGroupTableIndex["Group Description"]] = jsonArray.groupDescription;
                        
                        
                        UTILITY.systemGroupTable.row(".selected").data(aData).draw();;
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.deleteSystemGroup:{
                    if(data.status === CLIENTZONE.status.success){

                        UTILITY.systemGroupTable.row(".selected").remove().draw();
                        
                        $.noty.closeAll();
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.resetUserPassword:{
                    if(data.status === CLIENTZONE.status.success){
                        $.noty.closeAll();
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.addNewSystemProperty:{
                    if(data.status === CLIENTZONE.status.success){
                        $("form input").val("");$("form textarea").val("");
                        
                        if(UTILITY.systemPropertyTable !== null){
                            UTILITY.addPropertyToSystemPropertyTable(UTILITY.systemPropertyTable,data.systemProperty);
                            FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }else {
                            FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.addNewSystemUser:{
                    if(data.status === CLIENTZONE.status.success){
                        $("form input").val("");$("form textarea").val("");
                        if(UTILITY.systemUserTable !== null){
                            UTILITY.addUserToSystemUserTable(UTILITY.systemUserTable,data.systemUser);
                            FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }else {
                            FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }  
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.addNewClient:{
                    if(data.status === CLIENTZONE.status.success){
                        $("form input").val("");$("form textarea").val("");
                        if(UTILITY.clientsTable !== null) {
                            UTILITY.addClientToClientTable(UTILITY.clientsTable,data.client);
                            FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }else {
                            FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.addNewStageItem: {
                   if(data.status === CLIENTZONE.status.success){
                        if(UTILITY.stageItemsTable !== null) {
                            $(".addNewItemDialog input[type=text]").val("");$(".addNewItemDialog textarea").val("");
                            UTILITY.addItemToStageItemsTable(UTILITY.stageItemsTable, data.item);
                            FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }else {
                            FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                        }
                    }else if(data.status === CLIENTZONE.status.failed){
        
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;     
                }
                case CLIENTZONE.function.addNewProject:{
                    if(data.status === CLIENTZONE.status.success){
                        $(".addProjectDialog form input[type=text]").val("");$(".addProjectDialog form textarea").val("");
                        FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                       
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.updateProject:
                case CLIENTZONE.function.updateClientDetails:
                case CLIENTZONE.function.updateClient:{
                    if(data.status === CLIENTZONE.status.success){
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
        
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.updateSystemProperty:{
                    if(data.status === CLIENTZONE.status.success){
                        aRow = UTILITY.systemPropertyTable.row(".selected");
                        
                        aData = aRow.data();
                        
                        aData[UTILITY.systemPropertyTableIndex["Property Name"]] = jsonArray.propertyName;
                        aData[UTILITY.systemPropertyTableIndex["Priority"]] = jsonArray.propertyValue;
                        aData[UTILITY.systemPropertyTableIndex["Property Description"]] = jsonArray.propertyDescription;
                        aData[UTILITY.systemPropertyTableIndex["Group Name"]] = jsonArray.propertyGroupDescription;
                        
                        UTILITY.systemPropertyTable.row(".selected").data(aData).draw();;
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.reassignQuoteToProject:
                case CLIENTZONE.function.removeDeal:
                case CLIENTZONE.function.updateDeal:
                case CLIENTZONE.function.addNewDeal:
                case CLIENTZONE.function.addNewTicket:
                case CLIENTZONE.function.clearLogs:
                case CLIENTZONE.function.updateLogFlagStatus:
                case CLIENTZONE.function.progressSupportTicket:
                case CLIENTZONE.function.progressProjectToNextStage: {
                    if(data.status === CLIENTZONE.status.success){
                        $.noty.closeAll();
                        FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.warning){
                        FEEDBACK.AddWarningMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);                       
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }    
                    break;  
                }
                case CLIENTZONE.function.retrieveSystemUser:{
                   if(data.status === CLIENTZONE.status.success){
                        UTILITY.addUserToUpdateUserView(data.client);
                        $(".updateUserDialog").css('visibility',"visible");
                        $(".updateUserDialog").slideDown('0.5');
                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        $.noty.closeAll();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }  
                    break;      
                }
                case CLIENTZONE.function.retrieveClient: {
                    if(data.status === CLIENTZONE.status.success){
                        UTILITY.addClientToProfileView(data.client);
                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        $.noty.closeAll();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }  
                    break;
                }
                case CLIENTZONE.function.retrieveStagesForProject: {
                    if(data.status === CLIENTZONE.status.success){
                        UTILITY.addStagesToStagePlugin(data.stages);
                        //FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);  
                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.warning){
                        FEEDBACK.AddWarningMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }    
                    break;  
                }
                case CLIENTZONE.function.retrieveProjectStageItems: {
                    if(data.status === CLIENTZONE.status.success){
                        UTILITY.createStageItemsTable(data.items);
                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.warning){
                        UTILITY.createStageItemsTable({});
                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }    
                    break;  
                }
                case CLIENTZONE.function.retrieveDeal:{
                    if(data.status === CLIENTZONE.status.success){
                        UTILITY.addDealToEditDealView(data.developmentDeal);

                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        $.noty.closeAll();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    } else if(data.status === CLIENTZONE.status.warning){
                        FEEDBACK.PutHTMLinNormalState();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;     
                }
                case CLIENTZONE.function.retrievePaymentsByProjectId:{
                    if(data.status === CLIENTZONE.status.success){
                        UTILITY.addItemToProjectInvoiceView(data.invoices);

                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        $.noty.closeAll();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    } else if(data.status === CLIENTZONE.status.warning){
                        FEEDBACK.PutHTMLinNormalState();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;     
                }
                case CLIENTZONE.function.retrieveProjectDocuments: {
                   if(data.status === CLIENTZONE.status.success){
                        UTILITY.addItemToProjectDocumentsView(data.items);

                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        $.noty.closeAll();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    } else if(data.status === CLIENTZONE.status.warning){
                        FEEDBACK.PutHTMLinNormalState();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;     
                }
                case CLIENTZONE.function.retrieveProjectStageItem: {
                    if(data.status === CLIENTZONE.status.success){
                        if(UTILITY.ajaxResponseHolder) {
                             UTILITY.addItemToUpdateStageItemView(data.item);
                        }else{
                            UTILITY.addItemToStageItemView(data.item);
                        }
                        FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === CLIENTZONE.status.failed){
                        $.noty.closeAll();
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);                       
                    }  
                    break;
                }
                case CLIENTZONE.function.updateSystemUser:{
                    if(data.status === CLIENTZONE.status.success){
                        aRow = UTILITY.systemUserTable.row(".selected");
                        
                        aData = aRow.data();
                        
                        aData[UTILITY.systemUserTableIndex["email"]] = jsonArray.email;
                        aData[UTILITY.systemUserTableIndex["role"]] = jsonArray.systemRoleName;
                        
                        UTILITY.systemUserTable.row(".selected").data(aData).draw();
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.updateStageItem:{
                    if(data.status === CLIENTZONE.status.success){
                        UTILITY.initStageItemTableIndex();
                        
                        aRow = UTILITY.stageItemsTable.row(".selected");
                        
                        aData = aRow.data();
                        for (var aIndex = 0; aIndex < data.item.details.length; aIndex ++) {
                            detail = data.item.details[aIndex];
                            if(detail.typeName === "Item Description" && data.item.itemType ==="Document"){
                                
                            }else{
                                aData[UTILITY.stageItemTableIndex[detail.typeName]] = detail.entityDetailContent;
                            }
                        };
                        
                        UTILITY.stageItemsTable.row(".selected").data(aData).draw();
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.deleteSystemProperty:{
                    if(data.status === CLIENTZONE.status.success){

                        UTILITY.systemPropertyTable.row(".selected").remove().draw();
                        
                        $.noty.closeAll();
                        
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_check_square);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_times_circle);
                    }
                    break;
                }
                case CLIENTZONE.function.retrieveProjects:{
                    if(data.status === CLIENTZONE.status.success){
                        if(jsonArray.pageName === "timeline") {
                            
                            for(var index = 0; index < data.projects.length; index ++){
                                var project = data.projects[index];
                                var dateCreated = "";
                                var releaseDate = "";
                                var projectName = "";
                                
                                for(var detailsIndex = 0; detailsIndex < project.details.length; detailsIndex ++){
                                    detail = project.details[detailsIndex];
                                    if(detail.typeName === "Project Name") {
                                        projectName = detail.entityDetailContent;
                                    }else if(detail.typeName === "Release Forecast") {
                                        //31-07-2016
                                        var date = detail.entityDetailContent.split("-");
                                 
                                        releaseDate = new Date(date[2] + "-" + date[1] + "-" + date[0]);
                                    }
                                }
                                
                                var dateCreated = new Date(project.dateCreated);
                                
                                var myEvent = {
                                    title: "Project - " + project.projectId + " " + projectName + " - " + project.projectStage,
                                    start: dateCreated,
                                    end:releaseDate,
                                    tip: "Project - " + project.projectId + " " + projectName + " - " + project.projectStage,
                                    url: "project/" + project.projectId
                                };
                                UTILITY.calenderObject.fullCalendar( 'renderEvent', myEvent,true);
                            }
                            PROCESSOR.backend_call(CLIENTZONE.function.retrieveSystemSupportTickets,jsonArray);
                        }
                    }
                    break;
                }
                case CLIENTZONE.function.retrieveSystemSupportTickets:{
                    if(data.status === CLIENTZONE.status.success){
                        if(jsonArray.pageName === "timeline") {
                            for(var index = 0; index < data.supportTickets.length; index ++){
                                supportTicket = data.supportTickets[index];
                                dateCreated = supportTicket.dateCreated.split(" ");
                                dueDate = supportTicket.dueDate.split(" ");
                                var myEvent = {
                                    title: "Ticket - " + supportTicket.supportId + " " + supportTicket.description + " - " + supportTicket.status,
                                    start: new Date(dateCreated[0]),
                                    end: new Date(dueDate[0]),
                                    tip: "Ticket - " + supportTicket.supportId + " " + supportTicket.description + " - " + supportTicket.status,
                                    url: "ticket/" + supportTicket.supportId,
                                    color: "#FFA500"
                                };
                                UTILITY.calenderObject.fullCalendar( 'renderEvent', myEvent,true);
                            }
                        }
                    }
                    break;
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case CLIENTZONE.function.updateDeal:
                case CLIENTZONE.function.addNewDeal:
                case CLIENTZONE.function.addNewSystemUser:
                case CLIENTZONE.function.addNewTicket:
                case CLIENTZONE.function.addNewSystemGroup:
                case CLIENTZONE.function.addNewClient:
                case CLIENTZONE.function.addNewProject:
                case CLIENTZONE.function.addNewStageItem:
                case CLIENTZONE.function.updateSystemGroup:
                case CLIENTZONE.function.deleteSystemGroup:
                case CLIENTZONE.function.deleteSystemProperty:
                case CLIENTZONE.function.addNewSystemProperty:
                case CLIENTZONE.function.updateLogFlagStatus:
                case CLIENTZONE.function.updateSystemProperty:
                case CLIENTZONE.function.updateSystemUser:
                case CLIENTZONE.function.updateClientDetails:
                case CLIENTZONE.function.updateClient:
                case CLIENTZONE.function.updateProject:
                case CLIENTZONE.function.updateStageItem:
                case CLIENTZONE.function.updateSystemUserCredentials:
                case CLIENTZONE.function.resetUserPassword:
                case CLIENTZONE.function.clearLogs:
                case CLIENTZONE.function.retrieveProjects:
                case CLIENTZONE.function.progressSupportTicket:
                case CLIENTZONE.function.progressProjectToNextStage:
                case CLIENTZONE.function.reassignQuoteToProject:
                case CLIENTZONE.function.retrieveStagesForProject:
                case CLIENTZONE.function.retrieveProjectStageItem:
                case CLIENTZONE.function.retrieveProjectDocuments:
                case CLIENTZONE.function.retrievePaymentsByProjectId:
                case CLIENTZONE.function.retrieveProjectStageItems:
                case CLIENTZONE.function.removeDeal:
                case CLIENTZONE.function.retrieveDeal:
                case CLIENTZONE.function.retrieveSystemUser:
                case CLIENTZONE.function.retrieveClient:
                case CLIENTZONE.function.loginSystemUser:{
                    if(jqXHR.status === 0) {
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.connection,CLIENTZONE.icon.fa_cloud_download);
                    } else { 
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.critical,CLIENTZONE.icon.fa_cloud_download);
                    }
                    break;
                }
                case CLIENTZONE.function.retrieveClient: {
                    if(jqXHR.status === 0) {
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.connection,CLIENTZONE.icon.fa_cloud_download);
                    } else { 
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.critical,CLIENTZONE.icon.fa_cloud_download);
                    }
                    break;  
                }
               
             }   
        }
    });
};


/**
 *  UTility Class
 *  
 * @returns HTML response 
 */
var UTILITY = {};

UTILITY.systemGroupTable = null;
UTILITY.systemPropertyTable = null;
UTILITY.systemUserTable = null;
UTILITY.clientsTable = null;
UTILITY.dealsTable = null;
UTILITY.quotesTable = null;
UTILITY.stageItemsTable = null;
UTILITY.ajaxResponseHolder = null;
UTILITY.calenderObject = null;
UTILITY.scope = null;
UTILITY.systemGroupTableIndex = {};
UTILITY.systemPropertyTableIndex = {};
UTILITY.systemUserTableIndex = {};
UTILITY.clientsTableIndex = {};
UTILITY.stageItemTableIndex = {};

UTILITY.initStageItemTableIndex = function() {
    UTILITY.stageItemTableIndex["ID"] = 0;
    UTILITY.stageItemTableIndex["Item Type"] = 2;
    UTILITY.stageItemTableIndex["Task ID"] = 2;
    UTILITY.stageItemTableIndex["Item Name"] = 3;
    UTILITY.stageItemTableIndex["Item Description"] = 4;
    UTILITY.stageItemTableIndex["Item Status"] = 5;
};


UTILITY.initSystemGroupTableIndex = function() {
    UTILITY.systemGroupTableIndex["ID"] = 0;
    UTILITY.systemGroupTableIndex["Group Name"] = 2;
    UTILITY.systemGroupTableIndex["Group Value"] = 1;
    UTILITY.systemGroupTableIndex["Group Description"] = 3;
};

UTILITY.initSystemPropertyTableIndex = function() {
    UTILITY.systemPropertyTableIndex["ID"] = 0;
    UTILITY.systemPropertyTableIndex["Priority"] = 1;
    UTILITY.systemPropertyTableIndex["Property Name"] = 2;
    UTILITY.systemPropertyTableIndex["Group Name"] = 3;
    UTILITY.systemPropertyTableIndex["Property Description"] = 4;
};

UTILITY.initSystemUserTableIndex = function() {
    UTILITY.systemUserTableIndex["ID"] = 0;
    UTILITY.systemUserTableIndex["client ID"] = 1;
    UTILITY.systemUserTableIndex["email"] = 2;
    UTILITY.systemUserTableIndex["role"] = 3;
};

UTILITY.initClientTableIndex = function() {
    UTILITY.clientsTableIndex["ID"] = 0;
    UTILITY.clientsTableIndex["client ID"] = 1;
    UTILITY.clientsTableIndex["Names"] = 2;
    UTILITY.clientsTableIndex["email"] = 3;
    UTILITY.clientsTableIndex["role"] = 4;
    UTILITY.clientsTableIndex["action"] = 5;
};

UTILITY.createStageItemsTable = function(pStageItems){
    $("#" + UTILITY.ajaxResponseHolder).empty();
    $("#" + UTILITY.ajaxResponseHolder).append("<table id = 'stageItemsTable' class='display table table-bordered allTables' cellspacing='0' width='100%'><tbody></tbody></table>");
   
    aTable = $('#' + UTILITY.ajaxResponseHolder + " #stageItemsTable").DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "#",  "sWidth": "5%"},
            { "sTitle": "ID",  "sWidth": "10%"},
            { "sTitle": "Name", "sWidth": "20%"},
            { "sTitle": "Description", "sWidth": "25%"},
            { "sTitle": "Status", "sWidth": "20%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "paging": false,
        "info":     false,
        "searching": false,
        "ordering": true
    });
   
    
    UTILITY.stageItemsTable = aTable;
    
    for (var aIndex = 0; aIndex < pStageItems.length; aIndex ++) {
        UTILITY.addItemToStageItemsTable(aTable,pStageItems[aIndex]);
    }
    
    $('#' + UTILITY.ajaxResponseHolder + ' #stageItemsTable tbody').on( 'click', 'tr', function () {
       UTILITY.stageItemsTable.$('tr.selected').removeClass('selected');
       if ( $(this).hasClass('selected') ) {
           $(this).removeClass('selected');
       }
       else {
           $(this).addClass('selected');
       }
   });
};

UTILITY.createSystemGroupTable = function(pSystemGroups){
    aTable = $('#groupsTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Priority",  "sWidth": "10%"},
            { "sTitle": "Group Name", "sWidth": "20%"},
            { "sTitle": "Group Description", "sWidth": "45%"},
            { "sTitle": "Date Created", "sWidth": "25%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "paging": false,
        "pageLength": 10,
        "order": [[ 1, "asc" ]],
        "info":     false
    });
    
    for (var aGroupIndex = 0; aGroupIndex < pSystemGroups.length; aGroupIndex ++) {
        UTILITY.addGroupToSystemGroupTable(aTable,pSystemGroups[aGroupIndex]);
    }
    
    UTILITY.systemGroupTable = aTable;
    
    $('#groupsTable tbody').on( 'click', 'tr', function () {
        UTILITY.systemGroupTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
            UTILITY.addSelectGroupToForm();
        }
    });
};

UTILITY.createSystemPropertyTable = function(pSystemProperties){
    aTable = $('#propertiesTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Priority",  "sWidth": "10%"},
            { "sTitle": "Property Name", "sWidth": "20%"},
            { "sTitle": "Group Name", "sWidth": "20%"},
            { "sTitle": "Property Description", "sWidth": "30%"},
            { "sTitle": "Date Created", "sWidth": "20%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": false,
        "order": [[ 3, "asc" ],[ 1, "asc" ]],
        "info":     false
    });
    
    for (var aPropertyIndex = 0; aPropertyIndex < pSystemProperties.length; aPropertyIndex ++) {
        UTILITY.addPropertyToSystemPropertyTable(aTable,pSystemProperties[aPropertyIndex]);
    }
    
    UTILITY.systemPropertyTable = aTable;
    
    $('#propertiesTable tbody').on( 'click', 'tr', function () {
        UTILITY.systemPropertyTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
            UTILITY.addSelectPropertyToForm();
        }
    });
};

UTILITY.createClientsTable = function(pClients){
    aTable = $('#clientsTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Client ID",  "sWidth": "30%"},
            { "sTitle": "Names", "sWidth": "35%"},
            { "sTitle": "Business Name", "sWidth": "35%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "order": [[ 3, "asc" ]],
        "info":     false
    });
    
    for (var aIndex = 0; aIndex < pClients.length; aIndex ++) {
        UTILITY.addClientToClientTable(aTable,pClients[aIndex]);
    }
    
    UTILITY.clientsTable = aTable;
    
    $('#clientsTable tbody').on( 'click', 'tr', function () {
        UTILITY.clientsTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
            //UTILITY.addSelectUserToForm();
        }
    });
};

UTILITY.createSystemUserTable = function(pSystemUsers){
    aTable = $('#usersTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Client ID",  "sWidth": "25%"},
            { "sTitle": "Email Address", "sWidth": "25%"},
            { "sTitle": "System Role",  "sWidth": "25%"},
            { "sTitle": "Date Created", "sWidth": "25%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "order": [[ 3, "asc" ]],
        "info":     false
    });
    
    for (var aUserIndex = 0; aUserIndex < pSystemUsers.length; aUserIndex ++) {
        UTILITY.addUserToSystemUserTable(aTable,pSystemUsers[aUserIndex]);
    }
    
    UTILITY.systemUserTable = aTable;
    
    $('#usersTable tbody').on( 'click', 'tr', function () {
        UTILITY.systemUserTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
            UTILITY.addSelectUserToForm();
        }
    });
};


UTILITY.createSupportTicketsTable = function(pSupportTickets){
    aTable = $('#supportTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Ticket",  "sWidth": "10%"},
            { "sTitle": "Project ID",  "sWidth": "15%"},
            { "sTitle": "Description", "sWidth": "40%"},
            { "sTitle": "Due Date", "sWidth": "15%"},
            { "sTitle": "Status",  "sWidth": "10%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "searching": true,
        "order": [[ 4, "desc" ]],
        "info":     false
    });
    
    for (var aUserIndex = 0; aUserIndex < pSupportTickets.length; aUserIndex ++) {
        UTILITY.addTicketToSupportTicketsTable(aTable,pSupportTickets[aUserIndex]);
    }
    
    $('#supportTable tbody').on( 'click', 'tr', function () {
        aTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
        }
    });
};


UTILITY.createInvoicesTable = function(pInvoices){
    aTable = $('#invoiceTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Invoice",  "sWidth": "10%"},
            { "sTitle": "Project ID",  "sWidth": "15%"},
            { "sTitle": "Reference", "sWidth": "15%"},
            { "sTitle": "Description", "sWidth": "30%"},
            { "sTitle": "Amount", "sWidth": "15%"},
            { "sTitle": "Date", "sWidth": "15%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "searching": true,
        "order": [[ 6, "desc" ]],
        "info":     false
    });
   
    for (var aUserIndex = 0; aUserIndex < pInvoices.length; aUserIndex ++) {
        UTILITY.addInvoiceIntoInvoicesTable(aTable,pInvoices[aUserIndex]);
    }
    
    $('#invoiceTable tbody').on( 'click', 'tr', function () {
        aTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
        }
    });
};


UTILITY.createQuotesTable = function(pQuotes){
    aTable = $('#quotesTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Quote Number",  "sWidth": "25%"},
            { "sTitle": "Project ID",  "sWidth": "25%"},
            { "sTitle": "Start Date", "sWidth": "25%"},
            { "sTitle": "End Date", "sWidth": "25%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "searching": true,
        "order": [[ 4, "desc" ]],
        "info":     false
    });
   
    UTILITY.quotesTable = aTable;
   
    for (var aIndex = 0; aIndex < pQuotes.length; aIndex ++) {
        UTILITY.addQuoteIntoQuotesTable(aTable,pQuotes[aIndex]);
    }
    
    $('#quotesTable tbody').on( 'click', 'tr', function () {
        aTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
            $(".addNewQuoteDialog").slideUp('0.5');
            
            
        }
    });
};


UTILITY.createDealsTable = function(pDeals){
    aTable = $('#dealsTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Deal Code",  "sWidth": "15%"},
            { "sTitle": "Deal Name",  "sWidth": "15%"},
            { "sTitle": "Description", "sWidth": "40%"},
            { "sTitle": "Deal Price", "sWidth": "15%"},
            { "sTitle": "End Date", "sWidth": "15%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "searching": true,
        "order": [[ 5, "desc" ]],
        "info":     false
    });
   
    UTILITY.dealsTable = aTable;
   
    for (var aIndex = 0; aIndex < pDeals.length; aIndex ++) {
        UTILITY.addDealIntoDealsTable(aTable,pDeals[aIndex]);
    }
    
    $('#dealsTable tbody').on( 'click', 'tr', function () {
        aTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
            $(".updateDealDialog").slideUp('0.5');
            
            var aJson = {
                "dealCode": UTILITY.retrieveDealSelectRowDealCode()
            };

            //PROCESSOR.backend_call(CLIENTZONE.function.retrieveDeal,aJson);
        }
    });
};

UTILITY.createAllProjectsTable = function(pSystemProjects){
    aTable = $('#projectTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Project ID",  "sWidth": "20%"},
            { "sTitle": "Name", "sWidth": "30%"},
            { "sTitle": "Stage Name", "sWidth": "15%"},
            { "sTitle": "Release",  "sWidth": "15%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "searching": true,
        "order": [[ 4, "desc" ]],
        "info":     false
    });
    
    for (var aUserIndex = 0; aUserIndex < pSystemProjects.length; aUserIndex ++) {
        UTILITY.addProjectToProjectsTable(aTable,pSystemProjects[aUserIndex]);
    }
    
    $('#projectTable tbody').on( 'click', 'tr', function () {
        aTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
        }
    });
};

UTILITY.createUserTasksTable = function(pTasks){
    aTable = $('#taskTable').DataTable( {
        "aoColumns": [
            { "bVisible": false },
            { "sTitle": "Task ID",  "sWidth": "20%"},
            { "sTitle": "Project ID",  "sWidth": "20%"},
            { "sTitle": "Name", "sWidth": "20%"},
            { "sTitle": "Status", "sWidth": "20%"},
            { "sTitle": "Date",  "sWidth": "20%"}
        ],
        "bAutoWidth": false,
        "bProcessing": false,
        "pageLength": 25,
        "paging": true,
        "order": [[ 5, "desc" ]],
        "info":     false
    });
    
    for (var aIndex = 0; aIndex < pTasks.length; aIndex ++) {
        UTILITY.addTaskToUserTasksTable(aTable,pTasks[aIndex]);
    }
    
    $('#taskTable tbody').on( 'click', 'tr', function () {
        aTable.$('tr.selected').removeClass('selected');
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $(this).addClass('selected');
        }
    });
};

UTILITY.addPropertyToSystemPropertyTable = function(pTable, pSystemProperty){
    pTable.row.add(
        [
            pSystemProperty.propertyId,
            pSystemProperty.propertyValue,
            pSystemProperty.propertyName,
            pSystemProperty.groupName,
            pSystemProperty.propertyDescription,
            pSystemProperty.dateCreated
        ]
     ).draw(false); 
};

UTILITY.addUserToSystemUserTable = function(pTable, pSystemUser){
    pTable.row.add(
        [
            pSystemUser.userId,
            pSystemUser.userKey,
            pSystemUser.email,
            pSystemUser.roleName,
            pSystemUser.dateCreated
        ]
     ).draw(false); 
};

UTILITY.addGroupToSystemGroupTable = function(pTable, pSystemGroup){
    pTable.row.add(
        [
            pSystemGroup.groupId,
            pSystemGroup.groupValue,
            pSystemGroup.groupName,
            pSystemGroup.groupDescription,
            pSystemGroup.dateCreated
        ]
     ).draw(false); 
};

UTILITY.addTicketToSupportTicketsTable = function(pTable, pSupportTicket){
    dueDate = pSupportTicket.dueDate.split(" ");
    pTable.row.add(
        [
            pSupportTicket.supportId,
            "<a href ='ticket/" + pSupportTicket.supportId +"/'>" + pSupportTicket.supportId + "</a>",
            pSupportTicket.projectId,
            pSupportTicket.description,
            dueDate[0],
            pSupportTicket.status
        ]
     ).draw(false); 
};



UTILITY.addQuoteIntoQuotesTable = function(pTable, pQuote){
    endDate = pQuote.endDate.split(" ");
    startDate = pQuote.startDate.split(" ");
    link = "No project";
    if(pQuote.projectId !== "P000001") {
        link = "<a href ='project/" + pQuote.projectId +"/'>" + pQuote.projectId +"</>"
    }
    pTable.row.add(
        [
            pQuote.quoteNumber,
            pQuote.quoteNumber,
            link,
            startDate[0],
            endDate[0]
        ]
     ).draw(false); 
};


UTILITY.addDealIntoDealsTable = function(pTable, pDeal){
    endDate = pDeal.endDate.split(" ");
    pTable.row.add(
        [
            pDeal.dealId,
            pDeal.dealCode,
            pDeal.dealName,
            pDeal.dealDescription,
            pDeal.dealAmountPrice,
            endDate[0]
        ]
     ).draw(false); 
};

UTILITY.addInvoiceIntoInvoicesTable = function(pTable, pInvoice){
    paymentDate = pInvoice.paymentDate.split(" ");
    pTable.row.add(
        [
            pInvoice.paymentId,
            "<a href ='invoice/" + pInvoice.paymentId +"/'>" + pInvoice.paymentId + "</a>",
            "<a href ='project/" + pInvoice.projectId +"/'>" + pInvoice.projectId + "</a>",
            pInvoice.reference,
            pInvoice.description,
            pInvoice.amount,
            paymentDate[0]
        ]
     ).draw(false); 
};

UTILITY.addProjectToProjectsTable = function(pTable, pItem){
    var name ="";
    var desc ="";
    var forecast ="";
    var status = pItem.projectStage;

    for (var aIndex = 0; aIndex < pItem.details.length; aIndex ++) {
        detail =  pItem.details[aIndex];
        if(detail.typeName === "Project Name"){
            name = detail.entityDetailContent; 
        }else if(detail.typeName === "Release Forecast"){
            dateArray = detail.entityDetailContent.split("-");
            forecast = new Date(dateArray[2] + "-" + dateArray[1] + "-" + dateArray[0]); 
        }else if(detail.typeName === "Project Description"){
            desc = detail.entityDetailContent; 
        }else if(detail.typeName === "Project Status"){
            status = "<b style ='color:green'>Project Completed</b>"; 
        }
    }
    
    
    pTable.row.add(
        [
            pItem.projectId,
            "<a href ='project/" + pItem.projectId +"'>" + pItem.projectId + "</a>",
            name,
            status,
            forecast.toString().slice(0,15)
        ]
     ).draw(false); 
};

UTILITY.addTaskToUserTasksTable = function(pTable, pItem){
    var name ="";
    var desc ="";
    var status ="";
    for (var aIndex = 0; aIndex < pItem.details.length; aIndex ++) {
        detail = pItem.details[aIndex];
        if(detail.typeName === "Item Name"){
            name = detail.entityDetailContent; 
        }else if(detail.typeName === "Item Status"){
            if(detail.entityDetailContent === "Task Completed") {
                status = "<b style ='color:green'>" + detail.entityDetailContent +"</b>"; 
            }else if(detail.entityDetailContent === "Task Suspended") {
                status = "<b style ='color:red'>" + detail.entityDetailContent +"</b>"; 
            } else {
                status = " <b style ='color:#0088cc'>" + detail.entityDetailContent +"</b>"; 
            }
        }else if(detail.typeName === "Item Description"){
            desc = detail.entityDetailContent; 
        }
    }
    
    pTable.row.add(
        [
            pItem.itemId,
            pItem.itemId,
            "<a href ='project/" + pItem.projectId +"'>" + pItem.projectId + "</a>",
            name,
            status,
            pItem.dateCreated
        ]
     ).draw(false); 
};

UTILITY.addItemToStageItemsTable = function(pTable, pItem){
    var taskName ="";
    var desc ="";
    var status ="";
    for (var aIndex = 0; aIndex < pItem.details.length; aIndex ++) {
        detail = pItem.details[aIndex];
        if(detail.typeName === "Item Name"){
            taskName = detail.entityDetailContent; 
        }else if(detail.typeName === "Assigned Person"){
            assigned = detail.entityDetailContent; 
        }else if(detail.typeName === "Item Description"){
            desc = detail.entityDetailContent; 
        }else if(detail.typeName === "Item Status"){
            status = detail.entityDetailContent; 
        }
    }
    var icon  = '';
    if(pItem.itemType === "Document"){
        ext = desc.split("."); 
        if(ext[ext.length - 1] === "pdf"){
            icon  = '<span class="text-center"><i class="icon-file"></i></span>';
        }else if(ext[ext.length - 1] === "docx"){
            icon  = '<span class="text-center"><i class="icon-file"></i></span>';
        }else if(ext[ext.length - 1] === "zip"){
            icon  = '<span class="text-center"><i class="icon-file"></i></span>';
        }else {
            icon  = '<span class="text-center"><i class="icon-file"></i></span>';
        }
        status = "Doc completed";
        desc = "<a target ='_tab' href ='documents/" + detail.entityDetailContent + "'>" + detail.entityDetailContent + "</a>"; 
    }
    
    pTable.row.add(
        [
            pItem.stageId,
            icon,
            pItem.stageId,
            taskName,
            desc,
            status
        ]
     ).draw(false); 
};

UTILITY.addStageTitleAndBody = function(pStage,pIndex, pActive) {
    var aHtml = "<h3>" + pStage.propertyName + "</h3>";
    //UTILITY.scope.loggedUser.credentials.roleName !=='Client' 
	// UTILITY.scope.showMainMenu
    if(pActive) {
        aHtml += "<section style ='border:none;box-shadow:none;'><div class='module' style ='border:none;box-shadow:none;padding-bottom:5px'><div class ='module-head' style ='border:none;background:#EDEDED'>" + UTILITY.addActivateStageMenu(pStage.propertyName,pStage.propertyId) + "</div><div style = 'border:none' class ='module-body' id ='stageContentDiv" + pIndex +"'><table style ='padding:5px' id = 'stageItemsTable' class='display table table-condensed' cellspacing='0' width='100%'><tbody></tbody></table></div></div></section>";
    }else{
        aHtml += "<section><div class='module' style ='border:none;box-shadow:none;padding-bottom:5px'><div class ='module-head' style ='border:none;background:#EDEDED'>" + UTILITY.addUnactivateStageMenu(pStage.propertyName,pStage.propertyId) + "</div><div style = 'border:none' class ='module-body'  id ='stageContentDiv" + pIndex +"'><table style ='padding:5px' id = 'stageItemsTable' class='display table table-condensed allTables' cellspacing='0' width='100%'><tbody></tbody></table></div></div></section>";
    }
    return aHtml;
};

UTILITY.addActivateStageMenu = function(propertyName, propertyId) {
    var aHtml = '<div class="module-head" style ="border:none;background:none!important;padding:0px">';
    aHtml += '<button alt ="' + propertyName +'" id ="' + propertyId +'" title = "Edit selected Item" type="button" class="btn btn-primary setupAddNewItem" onclick ="openUpdateStageItemDialog(event)"><i class="icon-edit"></i></button>'; 
    aHtml += '&nbsp;<button alt ="' + propertyName +'" id ="' + propertyId +'" title = "Add Item" type="button" class="btn btn-primary setupAddNewItem" onclick ="setupAddNewItem(event)"><i class="icon-plus"></i></button>'; 
    aHtml += '&nbsp;<button title = "Upload Document" type="button" class="btn btn-primary setupAddNewItem" onclick ="setupUploadItemDoc(event)"><i class="icon-upload"></i></button>'; 
    aHtml += '&nbsp;<button  title = "View Item Details" alt ="' + propertyName +'" id ="' + propertyId +'" title = "" type="button" class="btn btn-primary" onclick ="openStageItemDialog(event)"><i class="icon-info-sign"></i></button>'; 

    return aHtml + "</div>";
};

UTILITY.addUnactivateStageMenu = function(propertyName, propertyId) {
    var aHtml = '<div class="module-head" style ="border:none;background:none;padding:0px">';
    aHtml += '<button alt ="' + propertyName +'" id ="' + propertyId +'" title = "View Item Details" type="button" class="btn btn-primary small" onclick ="openStageItemDialog(event)"><i class="icon-info-sign"></i></button>'; 
    return aHtml + "</div>";
};

UTILITY.addStagesToStagePlugin = function(pStages){
    var count = 0;
    for(index = 0; index < UTILITY.scope.systemProperties.length; index ++){
        stage = UTILITY.scope.systemProperties[index];

        if(stage.groupName === "Project Stages"){
            active = (count === (pStages.length - 1) && pStages[0].activateMenu === "YES"? true: false);
            var aHtml = UTILITY.addStageTitleAndBody(stage,count,active);

            $("#stages").append(aHtml);
            count ++;
        }
    }
    
    $("#stages").steps({
        headerTag: "h3",
        bodyTag: "section",
        enableFinishButton: false,
        enablePagination: false,
        startIndex: (pStages.length - 1),
        onStepChanged: function (event, currentIndex, priorIndex) {
            if(priorIndex !== currentIndex) {
                var aJson = {
                    "projectId" : UTILITY.scope.projectId,
                    "stageName" : pStages[currentIndex].stageName
                };
                UTILITY.stageItemsTable = null;

                UTILITY.ajaxResponseHolder = "stageContentDiv" + (currentIndex);
                $("#" + UTILITY.ajaxResponseHolder + " #stageItemsTable" ).empty();
                $("#" + UTILITY.ajaxResponseHolder + " .dataTables_filter" ).remove();
                PROCESSOR.backend_call(CLIENTZONE.function.retrieveProjectStageItems,aJson);
            }
        }
    });

    $( ".btn" ).tooltip();
    
    $("#stages .steps li").removeClass();
    $("#stages .steps li").addClass("disabled");
    var lastStage = "0987";
    for (var aIndex = 0; aIndex < pStages.length; aIndex ++) {
        aStage = pStages[aIndex];
        $('#stages .steps li:contains("' + aStage.stageName +'")').removeClass("disabled");
        $('#stages .steps li:contains("' + aStage.stageName +'")').addClass("done");
        
        lastStage = aStage.stageName;
    }
    
    $('#stages .steps li:contains("' + lastStage +'")').addClass("current");
    $('#stages .steps li:contains("' + lastStage +'")').addClass("first");
    $('#stages .steps li:contains("' + lastStage +'")').removeClass("done");
    var aJson = {
        "projectId" : UTILITY.scope.projectId,
        "stageName" : lastStage
    };
    UTILITY.ajaxResponseHolder = "stageContentDiv" + (pStages.length - 1);
    PROCESSOR.backend_call(CLIENTZONE.function.retrieveProjectStageItems,aJson);
};

UTILITY.addUserToUpdateUserView = function(pClient){
    var businessName = "";
    var names = "";
    var names = "";
    
    for (var aIndex = 0; aIndex < pClient.details.length; aIndex ++) {
        detail = pClient.details[aIndex];
        if(detail.typeName === "First Name"){
            names += detail.entityDetailContent + " "; 
        }else if(detail.typeName === "Last Name"){
            names += detail.entityDetailContent + " "; 
        }else if(detail.typeName === "Business Name"){
            businessName += detail.entityDetailContent; 
        }
    }
    var aHtml = "";
    for (var aIndex = 0; aIndex < pClient.details.length; aIndex ++) {
        detail = pClient.details[aIndex];
        aHtml += "<div class ='control-group ajaxLoadedUserInfo'><label class ='control-label'>" + detail.typeName +"</label>";
        aHtml += "<div class='controls'><input id = '" + detail.entityDetailId + "' type='text' class='form-control userDetails span8' placeholder='" + detail.typeName + "' value ='" + detail.entityDetailContent + "' alt ='" + detail.typeName + "'/></div></div>";
    } 
    
    $(aHtml).insertAfter(".updateUserDialogAjaxDiv");
};

UTILITY.addClientToProfileView = function(pClient){
    var businessName = "";
    var names = "";
    var names = "";
    
    for (var aIndex = 0; aIndex < pClient.details.length; aIndex ++) {
        detail = pClient.details[aIndex];
        if(detail.typeName === "First Name"){
            names += detail.entityDetailContent + " "; 
        }else if(detail.typeName === "Last Name"){
            names += detail.entityDetailContent + " "; 
        }else if(detail.typeName === "Business Name"){
            businessName += detail.entityDetailContent; 
        }
    }
    
    var aHtml = "<div class ='text-left'>";
        aHtml += "<div class ='control-group'><label class ='control-label'><strong>Client ID</strong></label><div class ='controls' id ='clientId'>" + pClient.clientID +"</div></div>";
        aHtml += "<div class ='control-group'><label class ='control-label'><strong>Email Address</strong></label><div class ='controls'>" + pClient.emailAddress +"</div></div>";
        
    for (var aIndex = 0; aIndex < pClient.details.length; aIndex ++) {
        detail = pClient.details[aIndex];
        aHtml += "<div class ='control-group'><label class ='control-label'><strong>" + detail.typeName +"</strong></label><div class ='controls'>" + detail.entityDetailContent +"</div></div>";
    }
    
    aHtml += "</div>";  
    
    $("#clientProfileView .modal-body").html(aHtml);
    $("#clientProfileView").modal('show');
};

UTILITY.addItemToStageItemView = function(pStageItem){
    var aHtml = "<div class ='text-left'>";
        
    for (var aIndex = 0; aIndex < pStageItem.details.length; aIndex ++) {
        detail = pStageItem.details[aIndex];
        
        if(pStageItem.itemType === "Document" && detail.typeName === "Item Description") {
            desc = "<a target ='_tab' href ='documents/" + detail.entityDetailContent + "'>" + detail.entityDetailContent + "</a>"; 

            aHtml += "<div class ='control-group'><label class ='control-label'><strong>" + "Download link" +"</strong></label><div class ='controls'>" + desc +"</div></div>";

        } else{
            aHtml += "<div class ='control-group'><label class ='control-label'><strong>" + detail.typeName +"</strong></label><div class ='controls'>" + detail.entityDetailContent +"</div></div>";
        }
    }
    
    aHtml += "</div>";  
    
    $("#stageItemView .modal-body").html(aHtml);
    $("#stageItemView").modal('show');
};

UTILITY.addItemToProjectInvoiceView = function(pInvoices){
    var aHtml = "<div class ='text-left'>";
    for (var aItemIndex = 0; aItemIndex < pInvoices.length; aItemIndex ++) { 
        aInvoice = pInvoices[aItemIndex];
        
        link = "<a href ='invoice/" + aInvoice.paymentId  + "/'>" + "Open Invoice " + aInvoice.paymentId + "</a>"; 
        
        aHtml += "<div class ='control-group'><label class ='control-label'><strong>" + aInvoice.paymentId + " - " + aInvoice.amount +"</strong></label><div class ='controls'>" + link +"</div></div>";
        aHtml += "</div>";  
        
        if(aItemIndex  != pInvoices.length - 1){
            aHtml += "<hr/>"; 
        }
    }
    
    $("#projectInvoicesView .modal-body").html(aHtml);
    $("#projectInvoicesView").modal('show');
}


UTILITY.addDealToEditDealView = function(pDeal){
    startDateTemp = pDeal.startDate.split(" ")[0];
    endDateArrayTemp = pDeal.endDate.split(" ")[0];
    
    startDateArray = startDateTemp.split("-");
    endDateArray =  endDateArrayTemp.split("-");;
    
    startDate = (startDateArray[2] + "-" + startDateArray[1] + "-" + startDateArray[0]); 
    endDate = (endDateArray[2] + "-" + endDateArray[1] + "-" + endDateArray[0]); 
    
    $(".updateDealDialog #dealCode").val(pDeal.dealCode);
    $(".updateDealDialog #dealName").val(pDeal.dealName);
    $(".updateDealDialog #description").val(pDeal.dealDescription);
    $(".updateDealDialog #dealPrice").val(pDeal.dealPrice);
    $(".updateDealDialog #updateStartDate").val(startDate);
    $(".updateDealDialog #updateEndDate").val(endDate);
    
    $(".updateDealDialog").css('visibility',"visible");
    $(".addNewDealDialog").hide();

    $(".updateDealDialog").slideDown('0.5');
};

UTILITY.addItemToProjectDocumentsView = function(pDocuments){
    var aHtml = "<div class ='text-left'>";
    for (var aItemIndex = 0; aItemIndex < pDocuments.length; aItemIndex ++) { 
        aDocument = pDocuments[aItemIndex];
        for (var aIndex = 0; aIndex < aDocument.details.length; aIndex ++) {
            detail = aDocument.details[aIndex];

            if(aDocument.itemType === "Document" && detail.typeName === "Item Description") {
                desc = "<a target ='_tab' href ='documents/" + detail.entityDetailContent + "'>" + detail.entityDetailContent + "</a>"; 
            } else if(aDocument.itemType === "Document" && detail.typeName === "Item Name") {
                name = detail.entityDetailContent; 
            }
        }
        aHtml += "<div class ='control-group'><label class ='control-label'><strong>" + name +"</strong></label><div class ='controls'>" + desc +"</div></div>";
        aHtml += "</div>";  
        
        if(aItemIndex  != pDocuments.length - 1){
            aHtml += "<hr/>"; 
        }
    }
    
    $("#stageDocumentsView .modal-body").html(aHtml);
    $("#stageDocumentsView").modal('show');
};

UTILITY.addItemToUpdateStageItemView = function(pStageItem){
    var aHtml = "<div class ='form-horizontal row-fluid'>";
    aHtml += "<div class ='control-group'>";
    aHtml += '<label class="control-label">Item ID</label>';
    aHtml += '<div class="controls">';
    aHtml +='<span id = "itemId" class="form-control span8" style ="border:none">' + pStageItem.stageId + '</span>';
    aHtml += "</div>";
    aHtml += "</div>";
    for (var aIndex = 0; aIndex < pStageItem.details.length; aIndex ++) {
        detail = pStageItem.details[aIndex];
        if(detail.typeName !== "Assigned Person" && detail.typeName !== "Item Status") {
            if(pStageItem.itemType === "Document" && detail.typeName === "Item Description") {

            } else if(detail.typeName === "Item Description"){
                aHtml += "<div class ='control-group'>";
                aHtml += '<label class="control-label">' + detail.typeName +"</label>";
                aHtml += '<div class="controls">';
                aHtml += '<textarea id = "' + detail.entityDetailId + '" type="text" class="form-control span8 itemDetails" placeholder="' + detail.entityDetailContent + '" alt ="' + detail.typeName + '">' + detail.entityDetailContent + "</textarea>";
                aHtml += "</div>";
                aHtml += "</div>";
            }else{
                aHtml += "<div class ='control-group'>";
                aHtml += '<label class="control-label">' + detail.typeName +"</label>";
                aHtml += '<div class="controls">';
                aHtml += '<input id = "' + detail.entityDetailId + '" type="text" class="form-control span8 itemDetails" placeholder="' + detail.entityDetailContent + '" value ="' + detail.entityDetailContent +'" alt ="' + detail.typeName + '">';
                aHtml += "</div>";
                aHtml += "</div>";
            }
        }else if(detail.typeName === "Item Status") {
            aHtml += "<div class ='control-group'>";
            aHtml += '<label class="control-label">' + detail.typeName +"</label>";
            aHtml += '<div class="controls">';
            
            aHtml += '<select id = "' + detail.entityDetailId + '" type="text" class="form-control span8 itemDetails" placeholder="' + detail.entityDetailContent + '" value ="' + detail.entityDetailContent +'" alt ="' + detail.typeName + '">';

            for (var aCount = 0; aCount < UTILITY.scope.systemProperties.length; aCount ++) {
                property = UTILITY.scope.systemProperties[aCount];
                selected = "";
                if(property.propertyId === detail.entityDetailContentId){
                    selected = "selected";
                }
                if(property.groupName === "Task Status"){
                    aHtml += '<option style ="border-left:none;border-right:none;border-radius: 0px;" class="" value ="' + property.propertyId + '" ' + selected +'>' + property.propertyName + '</option>';
                }
            } 
            
            aHtml += "</select>";
            aHtml += "</div>";
            aHtml += "</div>";
        }
    }
    
    aHtml += "</div>";  
    
    $("#updateStageItemView .modal-body").html(aHtml);
    $("#updateStageItemView").modal('show');
};

UTILITY.addClientToClientTable = function(pTable, pClient){
    var businessName = "";
    var names = "";
    
    for (var aIndex = 0; aIndex < pClient.details.length; aIndex ++) {
        detail = pClient.details[aIndex];
        if(detail.typeName === "First Name"){
            names += detail.entityDetailContent + " "; 
        }else if(detail.typeName === "Last Name"){
            names += detail.entityDetailContent + " "; 
        }else if(detail.typeName === "Business Name"){
            businessName += detail.entityDetailContent; 
        }
    }
    
    pTable.row.add(
        [
            pClient.clientID,
            "<a href ='client/" + pClient.clientID + "/'>" + pClient.clientID + "</a>",
            names,
            businessName
        ]
     ).draw(false); 
};

UTILITY.findGroupRowById = function (pRowID){
    var aReturn = UTILITY.systemGroupTable.rows().data('#' + pRowID);

    return aReturn;
};

UTILITY.systemGroupHasSelectedRow = function (){
    UTILITY.initSystemGroupTableIndex();
    
    row = UTILITY.systemGroupTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.systemPropertyHasSelectedRow = function (){
    
    row = UTILITY.systemPropertyTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.systemUserHasSelectedRow = function (){
    
    row = UTILITY.systemUserTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.clientHasSelectedRow = function (){
    
    row = UTILITY.clientsTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.stageItemHasSelectedRow = function (){
    
    row = UTILITY.stageItemsTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.retrieveStageItemSelectRowId = function () {    
    row = UTILITY.stageItemsTable.rows('.selected').data();
    
    return row[0][0];
};

UTILITY.retrieveSystemGroupSelectRowId = function () {
    UTILITY.initSystemGroupTableIndex();
    
    row = UTILITY.systemGroupTable.rows('.selected').data();
    
    return row[0][UTILITY.systemGroupTableIndex["ID"]];
};

UTILITY.retrieveSystemProperySelectRowId = function () {
    UTILITY.initSystemPropertyTableIndex();
    
    row = UTILITY.systemPropertyTable.rows('.selected').data();
    
    return row[0][UTILITY.systemPropertyTableIndex["ID"]];
};

UTILITY.retrieveClientSelectRowId = function () {
    UTILITY.initClientTableIndex();
    
    row = UTILITY.clientsTable.rows('.selected').data();
    
    return row[0][UTILITY.clientsTableIndex["ID"]];
};

UTILITY.retrieveSystemUserSelectRowClientId = function () {
    UTILITY.initClientTableIndex();
    
    row = UTILITY.systemUserTable.rows('.selected').data();
    
    return row[0][UTILITY.systemUserTableIndex["client ID"]];
};

UTILITY.retrieveDealSelectRowDealCode = function () {
    row = UTILITY.dealsTable.rows('.selected').data();
    
    return row[0][1];
};

UTILITY.retrieveQuoteSelectRowQuoteNumber = function () {
    row = UTILITY.quotesTable.rows('.selected').data();
    
    return row[0][1];
};


UTILITY.addSelectQuoteForm = function (){
    row = UTILITY.quotesTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.addSelectDealForm = function (){
    row = UTILITY.dealsTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    return true;
};

UTILITY.addSelectUserToForm = function (){
    UTILITY.initSystemUserTableIndex();
    
    row = UTILITY.systemUserTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
 
    $(".updateUserDialog #userId").val(row[0][UTILITY.systemUserTableIndex["ID"]]);
    $(".updateUserDialog #clientID").html(row[0][UTILITY.systemUserTableIndex["client ID"]]);
    
    $(".updateUserDialog #emaillAddress").val(row[0][UTILITY.systemUserTableIndex["email"]]);
    $(".updateUserDialog #emaillAddress").next().html(row[0][UTILITY.systemUserTableIndex["email"]]);
    
    var option = $(".updateUserDialog #systemRole option:contains(" + (row[0][UTILITY.systemUserTableIndex["role"]]) + ")").val();
    $(".updateUserDialog #systemRole").val(option);
    
    $(".updateUserDialog #systemRole").next().html(row[0][UTILITY.systemUserTableIndex["role"]]);
    
    $(".ajaxLoadedUserInfo").remove();
    
    $(".updateUserDialog").slideUp("0.5");
    
    return true;
};

UTILITY.addSelectGroupToForm = function (){
    UTILITY.initSystemGroupTableIndex();
    
    row = UTILITY.systemGroupTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    $(".updateGroupDialog #groupId").val(row[0][UTILITY.systemGroupTableIndex["ID"]]);
    $(".updateGroupDialog #groupName").val(row[0][UTILITY.systemGroupTableIndex["Group Name"]]);
    $(".updateGroupDialog #groupName").next().html(row[0][UTILITY.systemGroupTableIndex["Group Name"]]);
    $(".updateGroupDialog #groupValue").val(row[0][UTILITY.systemGroupTableIndex["Group Value"]]);
    $(".updateGroupDialog #groupValue").next().html("#" + row[0][UTILITY.systemGroupTableIndex["Group Value"]])
    $(".updateGroupDialog #groupDescription").val(row[0][UTILITY.systemGroupTableIndex["Group Description"]]);
    $(".updateGroupDialog #groupDescription").next().html(row[0][UTILITY.systemGroupTableIndex["Group Description"]]);
    
    return true;
};
UTILITY.addSelectPropertyToForm = function (){
    UTILITY.initSystemPropertyTableIndex();
    
    row = UTILITY.systemPropertyTable.rows('.selected').data();
    
    if(row === null || row.length === 0){
        return false;
    }
    
    $(".updatePropertyDialog #propertyGroup option").removeAttr("selected");
    
    $(".updatePropertyDialog #propertyId").val(row[0][UTILITY.systemPropertyTableIndex["ID"]]);
    $(".updatePropertyDialog #propertyPriority").val(row[0][UTILITY.systemPropertyTableIndex["Priority"]]);
    $(".updatePropertyDialog #propertyPriority").next().html("#" + row[0][UTILITY.systemPropertyTableIndex["Priority"]]);
    $(".updatePropertyDialog #propertyName").val(row[0][UTILITY.systemPropertyTableIndex["Property Name"]]);
    $(".updatePropertyDialog #propertyName").next().html(row[0][UTILITY.systemPropertyTableIndex["Property Name"]]);
    
    var option = $(".updatePropertyDialog #propertyGroup option:contains(" + (row[0][UTILITY.systemPropertyTableIndex["Group Name"]]) + ")").val();
    $(".updatePropertyDialog #propertyGroup").val(option);
    
    $(".updatePropertyDialog #propertyGroup").next().html(row[0][UTILITY.systemPropertyTableIndex["Group Name"]]);
    $(".updatePropertyDialog #propertyDescription").val(row[0][UTILITY.systemPropertyTableIndex["Property Description"]]);
    $(".updatePropertyDialog #propertyDescription").next().html(row[0][UTILITY.systemPropertyTableIndex["Property Description"]]);
    
    return true;
};
/**
 * 
 * @return Unknown styles HTML with feeback messages
 */
var FEEDBACK = {};

/**
 * Adds messages to HTML span, messages are styled in red
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
FEEDBACK.AddErrorMessageToHTML = function (aMessage,aIcon){
    var html  = '<div class="alert alert-dismissible alert-danger text-center"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button>' + aMessage + '</div>';
    $(".error").empty();
    //$(".error").append(html).slideDown(1);
    
    var n = noty({
        id          : "errorId",
        text        : '<div class="activity-item text-light"> <i class="fa '+ aIcon +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
        type        : 'error',
        dismissQueue: true,
        theme       : 'relax',
        layout      : 'center',
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        maxVisible  : 10,
        animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutLeft',
            easing: 'swing',
            speed : 250
        }
    });
    
    FEEDBACK.PutHTMLinNormalState();
};
/**
 * Adds messages to HTML span, messages are styled in yellow
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
FEEDBACK.AddWarningMessageToHTML = function (aMessage,aIcon){
    var html  = '<div class="alert alert-dismissible alert-danger text-center"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button>' + aMessage + '</div>';
    $(".error").empty();
    //$(".error").append(html).slideDown(1);
    
    var n = noty({
        id          : "warningId",
        text        : '<div class="activity-item text-dark"> <i class="fa '+ aIcon +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
        type        : 'warning',
        dismissQueue: true,
        theme       : 'relax',
        layout      : 'center',
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        maxVisible  : 10,
        animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutLeft',
            easing: 'swing',
            speed : 250
        }
    });
    
    FEEDBACK.PutHTMLinNormalState();
};
/**
 * Adds messages to HTML span, messages are styled in red
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
FEEDBACK.AddSuccesMessageToHTML = function (aMessage, aIcon){
    var html  = '<div class="alert alert-dismissible alert-success text-center"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button>' + aMessage + '</div>';
    $(".error").empty();
    //$(".error").append(html).slideDown(1);
    var n = noty({
        text        : '<div class="activity-item text-dark"> <i class="fa '+ aIcon +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
        type        : 'success',
        dismissQueue: true,
        theme       : 'relax',
        layout      : 'center',
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        maxVisible  : 10,
        animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutLeft',
            easing: 'swing',
            speed : 250
        }
    });
    FEEDBACK.PutHTMLinNormalState();
};

/**
 * Adds messages to HTML span, messages are styled in red
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
FEEDBACK.AddInfoMessageToHTML = function (aMessage, aIcon){
    var html  = '<div class="alert alert-dismissible alert-success text-center"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button>' + aMessage + '</div>';
    $(".error").empty();
    //$(".error").append(html).slideDown(1);
    var n = noty({
        text        : '<div class="activity-item text-dark"> <i class="fa '+ aIcon +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
        type        : 'info',
        dismissQueue: true,
        theme       : 'relax',
        layout      : 'center',
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        maxVisible  : 10,
        animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutLeft',
            easing: 'swing',
            speed : 250
        }
    });
    FEEDBACK.PutHTMLinNormalState();
};

FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML = function (aMessage, aIcon){
    var html  = '<div class="alert alert-dismissible alert-success text-center"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button>' + aMessage + '</div>';
    $(".error").empty();
    //$(".error").append(html).slideDown(1);
    var n = noty({
        text        : '<div class="activity-item text-dark"> <i class="fa '+ aIcon +'"></i><div class="activity" style ="">' + aMessage +' </div> </div>',
        type        : 'success',
        dismissQueue: true,
        theme       : 'relax',
        layout      : 'center',
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        maxVisible  : 10,
        animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutLeft',
            easing: 'swing',
            speed : 250
        },
        buttons: [
            {
                addClass: 'btn btn-primary', 
                text: 'Refresh', 
                onClick: function ($noty) {
                    window.location = window.location;
                }
            }
       ]
    });
    FEEDBACK.PutHTMLinNormalState();
};

/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
FEEDBACK.AddDownloadDialogToHTML = function (aMessage, aURL){
    noty({
        text        : aMessage,
        type        : "",
        dismissQueue: true,
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        layout      : 'topCenter',
        theme       : 'defaultTheme',
        maxVisible  : 2,
        buttons     : [
            {addClass: 'btn btn-primary', text: 'Save File', onClick: function ($noty) {
                $noty.close();
                var win = window.open(aURL, '_blank');
                win.focus();
            }
            },
            {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
                $noty.close();
                FEEDBACK.PutHTMLinNormalState();
               }
            }
        ]
    });    
};

FEEDBACK.PutHTMLinProcessingState = function (){
    $(".btn").attr('disabled',true);
    $(".form-horizontal input").attr('disabled',true);
    $(".form-horizontal textarea").attr('disabled',true);
    $(".form-horizontal select").attr('disabled',true);
    
    $(".error").empty();
    NProgress.start();
};
/**
 * Enables HTML input elements on current page
 * 
 */
FEEDBACK.PutHTMLinNormalState = function (){    
    $(".btn").attr('disabled',false);
    $(".form-horizontal input").attr('disabled',false);
    $(".form-horizontal textarea").attr('disabled',false);
    $(".form-horizontal select").attr('disabled',false);
    
    NProgress.done();
};