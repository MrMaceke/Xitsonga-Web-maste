/**
 * Constants for FinanceBean
 * 
 * @type string or integer value of requested constant
 */
var FINANCE_BEAN = {
    "server":{
         "current" : "/"
     },
    /**
     * @returns {Array} a list of FinanceBean functions
     */
    "API":{
        "financeBeanV1" : "php/FinanceBean.php"
    },
     
    "function":{
        "generateQuoteAndDownload" : "generateQuoteAndDownload",
        "generateExternalQuoteAndDownload" : "generateExternalQuoteAndDownload",
        "retrieveDevelomentDeals" : "retrieveDevelomentDeals",
        "addNewPayment"           : "addNewPayment"
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
var FINANCE_PROCESSOR = {};
/**
 * 
 * @param {string} call
 * @param {JSON} jsonArray 
 * @returns HTML response based on type of call
 */
FINANCE_PROCESSOR.backend_call = function(call,jsonArray){
    var url = FINANCE_BEAN.API.financeBeanV1 +"?type=" + call + "&data=" + JSON.stringify(jsonArray);
    $.ajax({
        dataType: 'json',
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                case FINANCE_BEAN.function.addNewPayment:
                case FINANCE_BEAN.function.generateExternalQuoteAndDownload:
                case FINANCE_BEAN.function.generateQuoteAndDownload:{
                   FEEDBACK.PutHTMLinProcessingState();
                }
                break;
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case FINANCE_BEAN.function.generateExternalQuoteAndDownload:{
                    if(data.status === FINANCE_BEAN.status.success){
                        var aMessage = "<b>" + "The document is ready" + "</b><br/><hr>";
                           aMessage = aMessage + "- The documet is system generated. Please contact system administrator if you encounter any issues";
                           aMessage = aMessage + "<hr>";
                           aMessage = aMessage + "- Press \"<b>Save File</b>\" and then \"<b>Cntr + S</b>\" Thank you for downloading." ;
                        remove_all_deals_and_close_dialog();
                        $(".addNewQuoteDialog .form-horizontal input").val("");
                        FEEDBACK.PutHTMLinNormalState();
                        FEEDBACK.AddDownloadDialogToHTML(aMessage,data.message);
                    }else if(data.status === FINANCE_BEAN.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,FINANCE_BEAN.icon.fa_unlock);
                    }
                    break;
                }
                case FINANCE_BEAN.function.generateQuoteAndDownload:{
                    if(data.status === FINANCE_BEAN.status.success){
                        var aMessage = "<b>" + "The document is ready" + "</b><br/><hr>";
                           aMessage = aMessage + "- The documet is system generated. Please contact system administrator if you encounter any issues";
                           aMessage = aMessage + "<hr>";
                           aMessage = aMessage + "- Press \"<b>Save File</b>\" and then \"<b>Cntr + S</b>\" Thank you for downloading." ;
                        remove_all_deals_and_close_dialog();
                        FEEDBACK.PutHTMLinNormalState();
                        FEEDBACK.AddDownloadDialogToHTML(aMessage,data.message);
                    }else if(data.status === FINANCE_BEAN.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,FINANCE_BEAN.icon.fa_unlock);
                    }
                    break;
                }
                case FINANCE_BEAN.function.addNewPayment:{
                    if(data.status === FINANCE_BEAN.status.success){
                        $.noty.closeAll();
                        FEEDBACK.AddSuccesMessageAndRefreshButtonToHTML(data.message,FINANCE_BEAN.icon.fa_check_square);
                    }else if(data.status === FINANCE_BEAN.status.warning){
                        FEEDBACK.AddWarningMessageToHTML(data.message,FINANCE_BEAN.icon.fa_check_square);                       
                    }else if(data.status === FINANCE_BEAN.status.failed){
                        FEEDBACK.AddErrorMessageToHTML(data.message,FINANCE_BEAN.icon.fa_times_circle);                       
                    }    
                    break;  
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case FINANCE_BEAN.function.generateExternalQuoteAndDownload:                
                case FINANCE_BEAN.function.addNewPayment:
                case FINANCE_BEAN.function.generateQuoteAndDownload:{
                    if(jqXHR.status === 0) {
                        FEEDBACK.AddErrorMessageToHTML(FINANCE_BEAN.error.connection,FINANCE_BEAN.icon.fa_cloud_download);
                    } else { 
                        FEEDBACK.AddErrorMessageToHTML(FINANCE_BEAN.error.critical,FINANCE_BEAN.icon.fa_cloud_download);
                    }
                    break;
                }
             }   
        }
    });
};
var DEVELOPMENT_DEALS = null;
var DEALS_CALCULATION_TOTAL = new Number(0);
var DEVELOPMENT_DELAS_DIV = null;
function financialDevelopmentDealsController($scope,$http) {
    var path = window.location.pathname;
    path = path.replace(FINANCE_BEAN.server.current, "");
    path = path.replace("/", "");
    
    if(path === ""){
        path = "index";
    }
    var json = {"pageName":path};
    
    var url = FINANCE_BEAN.API.financeBeanV1 + "?type=" + FINANCE_BEAN.function.retrieveDevelomentDeals + "&data=" + JSON.stringify(json);
    NProgress.start();
    $http.get(url).success( function(response) {
        if(response.status === FINANCE_BEAN.status.failed) {
            alert(response.message);
            $scope.developmentDeals = {"status":FINANCE_BEAN.status.failed,"message":response.message}; 
        }else if(response.status === FINANCE_BEAN.status.success) {
            $scope.developmentDeals = response.developmentDeals; 
            DEVELOPMENT_DEALS = response.developmentDeals;
        }else {
            alert(FINANCE_BEAN.error.critical);
            $scope.developmentDeals = {"status":FINANCE_BEAN.status.failed,"message":FINANCE_BEAN.error.critical}; 
        }
        NProgress.done();
    });
}

function add_deal (){
    $(".deals_div").append(addDealBox());
};

function remove_deal (){
    $(".deals_cover:last").remove();
    DEALS_CALCULATION_TOTAL = new Number(0);
    $(".priceHolder").each(function (){
        var amount = parseFloat($(this).attr('alt'));
        DEALS_CALCULATION_TOTAL = DEALS_CALCULATION_TOTAL + amount;
        
    });
   $("#quoteTotalPrice").text('R' + parseFloat(DEALS_CALCULATION_TOTAL, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
};

function remove_all_deals_and_close_dialog (){
    $(".deals_cover").remove();
    DEALS_CALCULATION_TOTAL = new Number(0);
    $(".priceHolder").each(function (){
        var amount = parseFloat($(this).attr('alt'));
        DEALS_CALCULATION_TOTAL = DEALS_CALCULATION_TOTAL + amount;
        
    });
   $(".addNewQuoteDialog").slideUp('0.5');
   $("#quoteTotalPrice").text('R' + parseFloat(DEALS_CALCULATION_TOTAL, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
};


function addDealBox() {
    var vHtml = '<div class="control-group deals_cover">';
    vHtml += '<label class="col-sm-2 control-label priceHolder" id ="price" alt="0">' + '-' + '</label>';
    vHtml += '<div class="controls">' + addDeletOptions() +'</div>';
    vHtml += '</div>';
    return vHtml;
}
   
$(document).on('change',".deals_details",function(){
    var dealerCode = $(this).val();
    $(this).parent().parent().find("#price").text("-");
    $(this).parent().parent().find("#price").attr("alt","0");
    for(var index = 0; index < DEVELOPMENT_DEALS.length; index ++) {
        if(DEVELOPMENT_DEALS[index].dealCode === dealerCode) {
            $(this).parent().parent().find("#price").attr("alt",DEVELOPMENT_DEALS[index].dealPrice);
            $(this).parent().parent().find("#price").text('R' + parseFloat(DEVELOPMENT_DEALS[index].dealPrice, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        }
    }
    DEALS_CALCULATION_TOTAL = new Number(0);
    $(".priceHolder").each(function (){
        var amount = parseFloat($(this).attr('alt'));
        DEALS_CALCULATION_TOTAL = DEALS_CALCULATION_TOTAL + amount;
        
    });
   $("#quoteTotalPrice").text('R' + parseFloat(DEALS_CALCULATION_TOTAL, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());

});


function addDeletOptions() {
    var vHtml = '<select class ="deals_details">';
    vHtml += '<option value ="0">' + "Default" + '</option>';
    for(var index = 0; index < DEVELOPMENT_DEALS.length; index ++) {
        vHtml += '<option value ="' + DEVELOPMENT_DEALS[index].dealCode  + '">' + DEVELOPMENT_DEALS[index].dealName + '</option>';
    }
    vHtml += '</select>';
    return vHtml;
}