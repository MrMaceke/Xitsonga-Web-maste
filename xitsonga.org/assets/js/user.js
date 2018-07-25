/**
 * Constants for user component
 * 
 * @type string or integer value of requested constant
 */
var USER_CONSTANTS = {
    /**
     * @returns {Array} a list of user component functions
     */
    "function":{
            "register_user" : "registerUser",
            "update_user" : "updateUser",
            "change_password" : "changePassword",
            "send_email"    : "sendContactMail",
            "reset_password"    : "resetPassword",
            "resend_activation"    : "resendActivation",
            "login_user"    : "loginUser"
     },
     "status":{
         "facebook_success": 998,
         "warning": 998,
         "success": 999,
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
var USER_PROCESSOR = {};

/**
 * 
 * @param {string} call
 * @param {JSON} data 
 * @returns HTML response based on type of call
 */
USER_PROCESSOR.backend_call = function(call,data){
    var url = "webBackend.php?type=" + call + "&data=" + JSON.stringify(data);
    $.ajax({
        dataType: 'json',
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                case USER_CONSTANTS.function.resend_activation:
                case USER_CONSTANTS.function.reset_password:
                case USER_CONSTANTS.function.change_password:
                case USER_CONSTANTS.function.update_user:
                case USER_CONSTANTS.function.send_email:
                case USER_CONSTANTS.function.register_user:
                case USER_CONSTANTS.function.login_user:{
                    USER_FEEDBACK.PutHTMLinProcessingState();
                    break;
                }
                break;
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case USER_CONSTANTS.function.send_email:
                {
                    if(data.status === USER_CONSTANTS.status.failed){
                        USER_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === USER_CONSTANTS.status.success){
                       $(".basic_form .form-control").val('');
                       $(".basic_form textarea").val('');
                       USER_FEEDBACK.AddSuccessMessageToHTML(data.infoMessage);
                    }else{
                        USER_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                    break;
                }
                
                case USER_CONSTANTS.function.update_user:{
                    if(data.status === USER_CONSTANTS.status.failed){
                        USER_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === USER_CONSTANTS.status.success){
                       USER_FEEDBACK.PutHTMLinNormalState();
                       USER_FEEDBACK.AddSuccessMessageToHTML(data.infoMessage);
                    }else{
                        USER_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case USER_CONSTANTS.function.resend_activation:
                case USER_CONSTANTS.function.reset_password:
                case USER_CONSTANTS.function.change_password:
                case USER_CONSTANTS.function.register_user:
                {
                    if(data.status === USER_CONSTANTS.status.failed){
                        USER_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === USER_CONSTANTS.status.success){
                       USER_FEEDBACK.PutHTMLinNormalState();
                       $(".basic_form .form-control").val('');
                       $(".basic_form textarea").val('');
                       USER_FEEDBACK.AddSuccessMessageToHTML(data.infoMessage);
                    }else{
                        USER_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case USER_CONSTANTS.function.login_user:
                {
                    if(data.status === USER_CONSTANTS.status.failed){
                        USER_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === USER_CONSTANTS.status.success){
                        USER_FEEDBACK.PutHTMLinNormalState();
                        USER_FEEDBACK.RedirectToHTMLPage("kaya");
                    }
                    else if(data.status === USER_CONSTANTS.status.warning){
                        $(".basic_form .form-control").val('');
                        $(".basic_form textarea").val('');
                        USER_FEEDBACK.AddSuccessMessageToHTML(data.infoMessage);         }
                    else{
                        USER_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        USER_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case USER_CONSTANTS.function.resend_activation:                
                case USER_CONSTANTS.function.reset_password:
                case USER_CONSTANTS.function.change_password:
                case USER_CONSTANTS.function.update_user:
                case USER_CONSTANTS.function.send_email:
                case USER_CONSTANTS.function.register_user:
                case USER_CONSTANTS.function.login_user:
                {
                    USER_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                    USER_FEEDBACK.PutHTMLinNormalState();
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
var USER_DATA = {};

/**
 * Returns user registration information in JSON format
 * @returns {JSON}
 * @static
 */
USER_DATA.registration_json = function(data){
    return {
        "firstName":data.firstName,
        "lastName":data.lastName,
        "email":data.email,
        "cemail":data.cemail,
        "password":data.password,
        "cpassword":data.cpassword
    };
};

USER_DATA.change_password_json = function(data){
    return {
        "currentPassword":data.current_password,
        "password":data.password,
        "cpassword":data.cpassword
    };
};

USER_DATA.mail_json = function(data){
    return {
        "names":data.names,
        "email":data.email,
        "phone":data.phone,
        "message":data.message
    };
};
/**
 * Returns user login information in JSON format
 * @returns {JSON}
 * @static
 */
USER_DATA.login_json = function(data){
    return {
        "email":data.email,
        "password":data.password
    };
};

/**
 * Returns user login information in JSON format
 * @returns {JSON}
 * @static
 */
USER_DATA.account_json = function(data){
    return {
        "firstName":data.firstName,
        "email":data.email
    };
};

/**
 *  User component data validator
 *  
 *  @returns {Boolean}
 */
var USER_VALIDATOR = {};

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
USER_VALIDATOR.validate_registration_input = function(data){
  
    if( USER_VALIDATOR.isEmpty(data.firstName) || 
        USER_VALIDATOR.isEmpty(data.lastName) ||
        USER_VALIDATOR.isEmpty(data.email) || 
        USER_VALIDATOR.isEmpty(data.password) ){
        
        USER_FEEDBACK.AddErrorMessageToHTML("All fields must be filled");
        
        return false;
    }else if( USER_VALIDATOR.minLength(data.firstName) || USER_VALIDATOR.minLength(data.lastName)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("First Name and Last Name must be at least 2 characters.");
        
        return false;
    }else if( !USER_VALIDATOR.validateEmail(data.email)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("Email is invalid.");
        
        return false;
    }else if( !USER_VALIDATOR.confirmPassword(data.password,data.cpassword)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("Passwords don't match.");
        
        return false;
    }
    
    return true;
};


USER_VALIDATOR.change_password_input = function(data){
  
    if( USER_VALIDATOR.isEmpty(data.current_password) || 
        USER_VALIDATOR.isEmpty(data.password) ||
        USER_VALIDATOR.isEmpty(data.password) ){
        
        USER_FEEDBACK.AddErrorMessageToHTML("All fields must be filled");
        
        return false;
    }else if( !USER_VALIDATOR.confirmPassword(data.password,data.cpassword)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("Passwords don't match.");
        
        return false;
    }
    return true;
 }

USER_VALIDATOR.validate_mail_input = function(data){
  
    if( USER_VALIDATOR.isEmpty(data.names) || 
        USER_VALIDATOR.isEmpty(data.phone) ||
        USER_VALIDATOR.isEmpty(data.email) || 
        USER_VALIDATOR.isEmpty(data.message) ){
        
        USER_FEEDBACK.AddErrorMessageToHTML("All fields must be filled");
        
        return false;
    }else if( USER_VALIDATOR.minLength(data.names)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("First Name must be at least 3 characters.");
        
        return false;
    }else if( !USER_VALIDATOR.validateEmail(data.email)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("Email is invalid.");
        
        return false;
    }else if( USER_VALIDATOR.minLength(data.message)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("Message be at least 3 characters.");
        
        return false;
    }else if(USER_VALIDATOR.minLength(data.phone)){
        
        USER_FEEDBACK.AddErrorMessageToHTML("Phone number must be at least 3 characters.");
        
        return false;
    }
    
    return true;
};

USER_VALIDATOR.isEmpty = function(aString){
    return aString.trim().length === 0 || aString.trim() === null ? true:false;
};

USER_VALIDATOR.confirmPassword = function(aString,aString2){
    return aString.trim() === aString2.trim() ? true:false;
};

USER_VALIDATOR.minLength = function(aString){
    return aString.trim().length <= 2? true:false;
};

USER_VALIDATOR.validateEmail = function(aString){
    var vRegrex = /\S+@\S+\.\S+/;
    return vRegrex.test(aString);
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
USER_VALIDATOR.validate_login_input = function(){
    return true;
};


/**
 * 
 * @return Unknown styles HTML with feeback messages
 */
var USER_FEEDBACK = {};

/**
 * Adds messages to HTML span, messages are styled in red
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
USER_FEEDBACK.AddErrorMessageToHTML = function (aMessage){
    noty({
        text        : aMessage,
        type        : "error",
        dismissQueue: true,
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        layout      : 'topCenter',
        theme       : 'defaultTheme',
        maxVisible  : 2
    });
    USER_FEEDBACK.PutHTMLinNormalState();
};
/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
USER_FEEDBACK.AddInfoMessageToHTML = function (aMessage){
    noty({
        text        : aMessage,
        type        : "information",
        dismissQueue: true,
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        layout      : 'topCenter',
        theme       : 'defaultTheme',
        maxVisible  : 2
    });
   USER_FEEDBACK.PutHTMLinNormalState();
};
/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
USER_FEEDBACK.AddSuccessMessageToHTML = function (aMessage){
    noty({
        text        : aMessage,
        type        : "success",
        dismissQueue: true,
        closeWith   : ['click', 'backdrop'],
        modal       : true,
        layout      : 'topCenter',
        theme       : 'defaultTheme',
        maxVisible  : 2
    });
   USER_FEEDBACK.PutHTMLinNormalState();
};
/**
 * Disables HTML input elements on current page
 * 
 */
USER_FEEDBACK.PutHTMLinProcessingState = function (){
    $(".btn").attr('disabled',true);
    $(".basic_form input").attr('disabled',true);
    
    $(".loading_image").append("<img src ='assets/images/loading_image.gif'/>");
};
/**
 * Enables HTML input elements on current page
 * 
 */
USER_FEEDBACK.PutHTMLinNormalState = function (){    
    $(".btn").attr('disabled',false);
    $(".basic_form input").attr('disabled',false);
    
     $(".loading_image").empty();
    
};

/**
 * Redirects user to Welcome page
 */
USER_FEEDBACK.RedirectToHTMLPage = function (aPage) {
    window.location = aPage;
}

/**
 * Redirects user to Welcome page
 */
USER_FEEDBACK.AppendHTMLToDiv = function (aDivID,aContent) {
  
}