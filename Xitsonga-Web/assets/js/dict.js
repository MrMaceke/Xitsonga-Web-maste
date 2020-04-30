/**
 * Constants for user component
 * 
 * @type string or integer value of requested constant
 */
var DICT_CONSTANTS = {
    /**
     * @returns {Array} a list of user component functions
     */
    "function":{
        "ask_time"    : "askTime",
        "ask_number"    : "askNumber",
        "translate"    : "liveTranslate",
        "elizaChat"    : "elizaChat"
     },
     "status":{
         "success": 999,
         "warning": 998,
         "failed": -999
     },
     "error":{
         "critical": "A critical error has occured.",
         "internet_connection": "Server not responding. Your internet connection might be down."
     }
};

/**
 * Backend calls for user functions
 *  
 * @returns HTML response 
 */
var DICT_PROCESSOR = {};

/**
 * 
 * @param {string} call
 * @param {JSON} data 
 * @returns HTML response based on type of call
 */
DICT_PROCESSOR.backend_call = function(call,data){
    var url = "webBackend.php?type=" + call + "&data=" + JSON.stringify(data);
    $.ajax({
        dataType: 'json',
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                case DICT_CONSTANTS.function.translate:{
                    $("#xitsongaTranslate").val("...");
                    break;
                }
                case DICT_CONSTANTS.function.elizaChat:{
                    $("#didYouMean").html("Rivoningo wa ha ehleketa");
                    break;
                }
                case DICT_CONSTANTS.function.ask_time:
                case DICT_CONSTANTS.function.ask_number:{
                    DICT_FEEDBACK.PutHTMLinProcessingState();
                    break;
                }
                break;
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case DICT_CONSTANTS.function.translate:{
                    if(data.status === DICT_CONSTANTS.status.failed){
                        $("#xitsongaTranslate").val(data.errorMessage);
                    }
                    else if(data.status === DICT_CONSTANTS.status.success){
                        $("#xitsongaTranslate").val(data.infoMessage);
                        
                    } else if(data.status === DICT_CONSTANTS.status.warning){
                        $("#xitsongaTranslate").val(data.errorMessage);
                    }
                    break;
                }
                case DICT_CONSTANTS.function.elizaChat:{
                    if(data.status === DICT_CONSTANTS.status.failed){
                        $("#didYouMean").html(data.errorMessage);
                    }
                    else if(data.status === DICT_CONSTANTS.status.success){
                        $("#toTranslate").val(data.infoMessage);
                        $("#didYouMean").html("");
                        $("#fromTranslate").val("");
                    }  else {
                        $("#didYouMean").html(data.errorMessage);
                    }
                    break;
                }
                case DICT_CONSTANTS.function.ask_time:
                case DICT_CONSTANTS.function.ask_number:
                {
                    if(data.status === DICT_CONSTANTS.status.failed){
                        DICT_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        DICT_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === DICT_CONSTANTS.status.success){
                        DICT_FEEDBACK.PutHTMLinNormalState();
                    
                        $(".basic_form input").val("");

                        var appendHTML = "<div id='removedMe' class='jumbotron top-space' style ='margin-top:25px'><h4>" + data.infoMessage + "</h4></div>";
                        $("#removedMe").remove();

                        $(appendHTML).appendTo(".number_div");
                    }else{
                        DICT_FEEDBACK.AddErrorMessageToHTML(DICT_CONSTANTS.error.critical);
                        DICT_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case DICT_CONSTANTS.function.translate:{
                    $("#xitsongaTranslate").val(DICT_CONSTANTS.error.critical);
                    break;
                }
                case DICT_CONSTANTS.function.elizaChat:{
                    $("#didYouMean").html("tintambhu ti tsemekile");
                    break;
                }
                case DICT_CONSTANTS.function.ask_time:
                case DICT_CONSTANTS.function.ask_number:
                {
                    DICT_FEEDBACK.AddErrorMessageToHTML(DICT_CONSTANTS.error.critical);
                    DICT_FEEDBACK.PutHTMLinNormalState();
                    break;
                }
             }   
        }
    });
};

/**
 *  Returns JSON data for required by backend call
 *  
 *  @returns {JSON}
 */
var DICT_DATA = {};

/**
 * Returns user login information in JSON format
 * @returns {JSON}
 * @static
 */
DICT_DATA.number_json = function(data){
    return {
        "number":data.number
    };
};

DICT_DATA.time_json = function(data){
    return {
        "time":data.time
    };
};

DICT_DATA.translate_json = function(data){
    return {
        "text":data.text,
        "langauge":data.langauge
    };
};

/**
 *  User component data validator
 *  
 *  @returns {Boolean}
 */
var DICT_VALIDATOR = {};

/**
 * Input validation for registration information
 * <br/>
 * <br/>
 * - Checks for empty fields<br/>
 * - Invalid names<br/>
 * - Invalid email address<br/>
 * - Invalid password<br/>
 * - Matching values for password and confirm password
 * 
 * @returns {Boolean}
 */
DICT_VALIDATOR.validate_registration_input = function(){
    return true;
};
/**
 * Input validation for login information
 * <br/>
 * <br/>
 * - Checks for empty fields<br/>
 * - Invalid email address<br/>
 * - Invalid password<br/>
 * 
 * @returns {Boolean}
 */
DICT_VALIDATOR.validate_login_input = function(){
    return true;
};


/**
 * 
 * @return Unknown styles HTML with feeback messages
 */
var DICT_FEEDBACK = {};

/**
 * Adds messages to HTML span, messages are styled in red
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
DICT_FEEDBACK.AddErrorMessageToHTML = function (aMessage){
    $(".error").notify(aMessage);
    DICT_FEEDBACK.PutHTMLinNormalState();
};

/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
DICT_FEEDBACK.AddSuccessMessageToHTML = function (aMessage){
   
};
/**
 * Disables HTML input elements on current page
 * 
 */
DICT_FEEDBACK.PutHTMLinProcessingState = function (){
    $(".btn").attr('disabled',true);
    $(".basic_form input").attr('disabled',true);
};
/**
 * Enables HTML input elements on current page
 * 
 */
DICT_FEEDBACK.PutHTMLinNormalState = function (){    
    $(".btn").attr('disabled',false);
    $(".basic_form input").attr('disabled',false);
};

/**
 * Redirects user to Welcome page
 */
DICT_FEEDBACK.RedirectToHTMLPage = function (aPage) {
    window.location = aPage;
}

/**
 * Redirects user to Welcome page
 */
DICT_FEEDBACK.AppendHTMLToDiv = function (aDivID,aContent) {
  
}