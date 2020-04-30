/**
 * Constants for DocumentCreatorBean
 * 
 * @type string or integer value of requested constant
 */
var DOCUMENT_CREATOR = {
    "server":{
         "current" : "/"
     },
    /**
     * @returns {Array} a list of DocumentCreatorBean functions
     */
    "API":{
        "documentCreatorBeanV1" : "php/DocumentCreatorBean.php"
    },
     
    "function":{
        "downloadProjectContract" : "downloadProjectContract",
        "downloadQuote" : "downloadQuote",
        "downloadExternalQuotePDF":"downloadExternalQuotePDF",
        "downloadInvoice" : "downloadInvoice",
        "downloadClientBasicInformation" : "createClientBasicInformationPDF",
        "downloadClientCredentialsPack" : "downloadClientCredentialsPackPDF"
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


/**
 * Backend calls functions
 *  
 * @returns HTML response 
 */
var DOC_CREATOR_PROCESSOR = {};
/**
 * 
 * @param {string} call
 * @param {JSON} jsonArray 
 * @returns HTML response based on type of call
 */
DOC_CREATOR_PROCESSOR.backend_call = function(call,jsonArray){
    var url = DOCUMENT_CREATOR.API.documentCreatorBeanV1 +"?type=" + call + "&data=" + JSON.stringify(jsonArray);
    $.ajax({
        dataType: 'json',
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                case DOCUMENT_CREATOR.function.downloadInvoice:
                case DOCUMENT_CREATOR.function.downloadQuote:
                case DOCUMENT_CREATOR.function.downloadExternalQuotePDF:
                case DOCUMENT_CREATOR.function.downloadProjectContract:
                case DOCUMENT_CREATOR.function.downloadClientCredentialsPack:
                case DOCUMENT_CREATOR.function.downloadClientBasicInformation:{
                   FEEDBACK.PutHTMLinProcessingState();
                }
                break;
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case DOCUMENT_CREATOR.function.downloadInvoice:
                case DOCUMENT_CREATOR.function.downloadQuote:
                case DOCUMENT_CREATOR.function.downloadExternalQuotePDF:
                case DOCUMENT_CREATOR.function.downloadProjectContract:
                case DOCUMENT_CREATOR.function.downloadClientCredentialsPack:
                case DOCUMENT_CREATOR.function.downloadClientBasicInformation:{
                    if(data.status === DOCUMENT_CREATOR.status.success){
                        var aMessage = "<b>" + "The document is ready" + "</b><br/><hr>";
                           aMessage = aMessage + "- The documet is system generated. Please contact system administrator if you encounter any issues";
                           aMessage = aMessage + "<hr>";
                           aMessage = aMessage + "- Press \"<b>Save File</b>\" and then \"<b>Cntr + S</b>\" Thank you for downloading." ;
                        FEEDBACK.PutHTMLinNormalState();
                        FEEDBACK.AddDownloadDialogToHTML(aMessage,data.message);
                    }else if(data.status === DOCUMENT_CREATOR.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,DOCUMENT_CREATOR.icon.fa_unlock);
                    }
                    break;
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case DOCUMENT_CREATOR.function.downloadExternalQuotePDF:
                case DOCUMENT_CREATOR.function.downloadInvoice:
                case DOCUMENT_CREATOR.function.downloadQuote:
                case DOCUMENT_CREATOR.function.downloadProjectContract:
                case DOCUMENT_CREATOR.function.downloadClientCredentialsPack:
                case DOCUMENT_CREATOR.function.downloadClientBasicInformation:{
                    if(jqXHR.status === 0) {
                        FEEDBACK.AddErrorMessageToHTML(DOCUMENT_CREATOR.error.connection,DOCUMENT_CREATOR.icon.fa_cloud_download);
                    } else { 
                        FEEDBACK.AddErrorMessageToHTML(DOCUMENT_CREATOR.error.critical,DOCUMENT_CREATOR.icon.fa_cloud_download);
                    }
                    break;
                }
             }   
        }
    });
};