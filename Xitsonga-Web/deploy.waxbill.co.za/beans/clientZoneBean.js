/**
 * Constants for ClientZoneBean
 * 
 * @type string or integer value of requested constant
 */
var CLIENTZONE = {
    /**
     * @returns {Array} a list of ClientZoneBean functions
     */
    "API":{
        "clientZoneBeanV1" : "https://zone.waxbill.co.za/php/ClientZoneBean.php"
    },
     
    "function":{
        "deployProjectToQA" : "deployProjectToQAJSONP",
        "retrieveDeploymentsJSONP": "retrieveDeployments",
        "retrieveSystemPropertiesJSONP" : "retrieveSystemProperties"
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
         "fa_unlock": "fa-unlock"
     }
};

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
                case CLIENTZONE.function.deployProjectToQA:{
                    FEEDBACK.PutHTMLinProcessingState();
                }
                break;
            }
        }, 
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case CLIENTZONE.function.deployProjectToQA: {
                   if(data.status === CLIENTZONE.status.success){
                       $(".form-validation input").val("");
                        FEEDBACK.AddSuccesMessageToHTML(data.message,CLIENTZONE.icon.fa_unlock);
                    }else if(data.status === CLIENTZONE.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,CLIENTZONE.icon.fa_unlock);
                    }
                    break;     
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case CLIENTZONE.function.deployProjectToQA:{
                    if(jqXHR.status === 0) {
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.connection,CLIENTZONE.icon.fa_unlock);
                    } else { 
                        FEEDBACK.AddErrorMessageToHTML(CLIENTZONE.error.critical,CLIENTZONE.icon.fa_unlock);
                    }
                    break;
                }
            }   
        }
    });
};

function retrieveSystemProperties($scope,$http) {
    var path = window.location.pathname.toLowerCase();
    path = path.replace("/", "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
  
    var json = 	{"pageName":path};
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveSystemPropertiesJSONP + "&data=" + JSON.stringify(json) + "&callback=JSON_CALLBACK";
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.systemProperties = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.systemProperties = response.systemProperties;
        }else {
           $scope.systemProperties = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
   });
}

function retrieveDeployments($scope,$http) {
    var path = window.location.pathname.toLowerCase();
    path = path.replace("/", "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
  
    var json = 	{"pageName":path};
    var url = CLIENTZONE.API.clientZoneBeanV1 + "?type=" + CLIENTZONE.function.retrieveDeploymentsJSONP + "&data=" + JSON.stringify(json);
    $http.get(url).success( function(response) {
        if(response.status === CLIENTZONE.status.failed){
           $scope.deployments = {"status":CLIENTZONE.status.failed,"message":response.message}; 
        }
        else if(response.status === CLIENTZONE.status.success) {
            $scope.deployments = response.deployments;
        }else {
           $scope.deployments = {"status":CLIENTZONE.status.failed,"message":CLIENTZONE.error.critical}; 
        }
   });
}

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

FEEDBACK.PutHTMLinProcessingState = function (){
    $("input").attr('disabled',true);
    $("textarea").attr('disabled',true);
    $("select").attr('disabled',true);
};
/**
 * Enables HTML input elements on current page
 * 
 */
FEEDBACK.PutHTMLinNormalState = function (){    
    $("input").attr('disabled',false);
    $("textarea").attr('disabled',false);
    $("select").attr('disabled',false);
};