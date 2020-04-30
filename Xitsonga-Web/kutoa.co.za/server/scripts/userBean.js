/**
 * Constants for UserBean
 * 
 * @type string or integer value of requested constant
 */
var USER_BEAN = {
    "server":{
         "current" : "/kutoa"
     },
    /**
     * @returns {Array} a list of UserBean functions
     */
    "API":{
        "kutoaBeanV1" : "php/KutoaBean.php"
    },
     
    "function":{
        "sendSMS":"sendSMS",
        "cancelTrip": "cancelTrip",
        "startTrip": "startTrip",
        "retrieveConfirmedRide":"retrieveConfirmedRide",
        "retrieveRides" : "retrieveRides",
        "retrieveLatestTrackForTrip" : "retrieveLatestTrackForTrip",
        "requestRide" : "requestRide",
        "registerUser" : "registerUser",
        "acceptRideOffer" : "acceptRideOffer",
        "sendOneTimePIN" : "sendOneTimePIN",
        "resendOneTimePIN": "resendOneTimePIN",
        "verifyDigitCode": "verifyDigitCode",
        "addNewTrackUpdateForTrip": "addNewTrackUpdateForTrip"
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
     }
};


/**
 * Backend calls functions
 *  
 * @returns HTML response 
 */
var USER_PROCESSOR = {};
/**
 * 
 * @param {string} call
 * @param {JSON} jsonArray 
 * @returns HTML response based on type of call
 */
USER_PROCESSOR.backend_call = function(call,jsonArray){
    var url = USER_BEAN.API.kutoaBeanV1 +"?type=" + call + "&data=" + JSON.stringify(jsonArray);
    $.ajax({
        dataType: 'json',
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                default:{
                   break;
                }
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                default:{
                    alert(JSON.stringify(data));
                    break;  
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                default:{
                   alert(jqXHR.responseText);
                }
            }   
        }
    });
};