/**
 * Constants for user component
 * 
 * @type string or integer value of requested constant
 */
var MANAGE_CONSTANTS = {
    /**
     * @returns {Array} a list of user component functions
     */
    "element": {
            "change":null
    },
    "function":{
            "download_type_as_PDF" : "downloadTypeAsPDF",
            "add_item_type" : "addItemType",
            "add_translation" : "addTranslationConfig",
            "edit_translation" : "editTranslationConfig",
            "remove_translation" : "removeTranslationConfig",
            "update_file" : "updateFile", 
            "add_entity":"addEntity",
            "add_exercise":"addNewExercise",
            "add_question":"addNewQuestion", 
            "add_answers":"addAnswersForQuestion",
            "submit_exercise":"submitExercise",
            "add_entity_detail":"AddEntityDetail",
            "rate_entity":"rateEntity",
            "edit_type": "editType",
            "edit_exercise": "editExercise",
            "edit_user": "editUserAccess",
            "edit_question": "editQuestion",
            "edit_entity": "editEntity",
            "add_entity_bulk": "AddEntityBulk",
            "add_post": "addPost",
            "remove_post": "removePost",
            "remove_entity_detail": "removeEntityDetail",
            "send_server_migration_email": "sendServerSystemEmail",
            "send_suggestion_email": "sendSuggestionEmail",
            "getAnswersByQuestionID":"getAnswersByQuestionID",
            "getEntityDetailsByEntityId": "getEntityDetailsByEntityId"
    },
     "status":{
         "warning": 998,
         "success": 999,
         "failed": -999
     },
     "error":{
         "critical": "A critical error has occured",
         "internet_connection": "Server not responding"
     }
};

/**
 * Backend calls for user functions
 *  
 * @returns HTML response 
 */
var MANAGE_PROCESSOR = {};

/**
 * 
 * @param {string} call
 * @param {JSON} data 
 * @returns HTML response based on type of call
 */
MANAGE_PROCESSOR.backend_call = function(call,data){
    var JsonObject = data;
    var url = "webBackend.php?type=" + call + "&data=" + encodeURIComponent(JSON.stringify(data));
    $.ajax({
        dataType: "json",
        url:url,
        beforeSend: function(xhr) {
            switch(call){
                case MANAGE_CONSTANTS.function.submit_exercise:{
                    MANAGE_FEEDBACK.PutHTMLinProcessingState();
                    break;
                }
                case MANAGE_CONSTANTS.function.download_type_as_PDF:{
                    MANAGE_FEEDBACK.PutHTMLinProcessingState();
                    break;
                }
                
                case MANAGE_CONSTANTS.function.remove_translation:
                case MANAGE_CONSTANTS.function.send_suggestion_email:
                case MANAGE_CONSTANTS.function.send_server_migration_email:
                case MANAGE_CONSTANTS.function.add_entity_bulk:
                case MANAGE_CONSTANTS.function.add_entity_detail:
                case MANAGE_CONSTANTS.function.edit_user: 
                case MANAGE_CONSTANTS.function.edit_entity:
                case MANAGE_CONSTANTS.function.edit_exercise:
                case MANAGE_CONSTANTS.function.edit_question:
                case MANAGE_CONSTANTS.function.edit_type:
                case MANAGE_CONSTANTS.function.add_post:
                case MANAGE_CONSTANTS.function.add_translation:
                case MANAGE_CONSTANTS.function.remove_translation:
                case MANAGE_CONSTANTS.function.edit_translation:
                case MANAGE_CONSTANTS.function.add_post: 
                case MANAGE_CONSTANTS.function.add_entity:
                case MANAGE_CONSTANTS.function.getEntityDetailsByEntityId:
                case MANAGE_CONSTANTS.function.getAnswersByQuestionID:
                case MANAGE_CONSTANTS.function.add_exercise:
                case MANAGE_CONSTANTS.function.add_question:
                case MANAGE_CONSTANTS.function.add_answers:
                case MANAGE_CONSTANTS.function.remove_entity_detail:
                case MANAGE_CONSTANTS.function.add_item_type:{
                    $.noty.closeAll() ;
                    MANAGE_FEEDBACK.PutHTMLinProcessingState();
                    break;
                }
                break;
            }
        },
        success: function(data, textStatus, jqXHR) {
            switch(call){
                case MANAGE_CONSTANTS.function.download_type_as_PDF:{
                    MANAGE_FEEDBACK.PutHTMLinNormalState();
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                         MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                        var aMessage = "<b>" + "Your document is ready" + "</b><br/><hr>";
                           aMessage = aMessage + "- The document has a list of a maximum 50 \"" + JsonObject.sub_type + "\" ";
                           aMessage = aMessage + "<hr>";
                           aMessage = aMessage + "- Press \"<b>Save File</b>\" and then \"<b>Cntr + S</b>\" Thank you for downloading." ;

                         MANAGE_FEEDBACK.AddDownloadDialogToHTML(aMessage,data.infoMessage);
                    }else{
                       MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                    }   
                    break;
                }
                case MANAGE_CONSTANTS.function.rate_entity:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        alert("Rating failed. An error occured");
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                       
                    }else{
                       alert(USER_CONSTANTS.error.critical);
                    }   
                    break;
                }
                case MANAGE_CONSTANTS.function.getAnswersByQuestionID:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        //MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                            var details = data.answers;
                            
                            if ('null' !== details){
                                $(".answer_row").remove();
                                for(var count = 0; count < details.length; count ++){
                                    var select = "";
                                    if(details[count].correct === "1"){
                                        select = "selected";
                                    }
                                    var append = "<tr class ='answer_row'>\n\
                                        <td>\n\
                                            <span style ='font-size: 15px;text-transform:uppercase'>Answer " + (count + 1)+"</span><br/>\n\
                                            <span style ='font-size: 11px;'>Text of the answer</span>\n\
                                        </td>\n\
                                        <td>\n\
                                            <textarea id ='title' placeholder='A title of the answer' class='form-control' rows='1' cols='50'>" + details[count].answerText +"</textarea>\n\
                                        </td>\n\
                                        <td>\n\
                                            <select class ='correct_answer form-control'>\n\
                                                <option value='0' " + select +">Incorrect</option>\n\
                                                <option value='1' " + select +">Correct</option>\n\
                                            </select>\n\
                                        </td>\n\
                                      </tr>";
                                    $('.answers_table .lastTableRow').before(append);
                                }
                            }
                             MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.getEntityDetailsByEntityId:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       
                        var details = data.entityDetails;
                        $("#entity_id").val(data.entity_id);
                        $("#image_entity_id").val(data.entity_id);
                        //$('.edit_entity_form #item_type').find('option[text="' + data.itemType +'"]').attr("selected", true);
                        
                        
                        $('.edit_entity_form #item_type option:contains(' + data.itemType + ')').each(function(){
                            if ($(this).text() === data.itemType) {
                                $(".edit_entity_form #item_type option").removeAttr("selected");
                                $('[name=item_type]').val($(this).val().trim());
                                return false;
                            }
                            
                            return true;
                        });
                       $(".edit_entity_form #" + "dictionary_type").attr("class","about_entity_val form-control");
                        $(".edit_entity_form #" + "website_link").attr("class","about_entity_val form-control");
                       for(var count = 0; count < details.length; count ++){
                           var detail = details[count];
                           
                           var name = detail.typeName;
                           name = name.replace(" ","_");
                           name = name.toLowerCase();
                           if(detail.typeName.indexOf("Image") > -1){
                           	var content = detail.content.trim();
                               $("#entity_image img").attr("src", "assets/images/entity/" + content.toLowerCase());
                           }
                           var content = detail.content.trim();
                          
                          if(content.length > 0){
				if(name === "english_translation" ||  name === "url_reference"){
                                    if(detail.htmlContent === "2" && !JsonObject.image){ 
                                        MANAGE_FEEDBACK.AppendRichTextEditor($(".edit_entity_form .about_entity_val#english_translation"));
                                        
                                        $(".Editor-editor").html(detail.content);
                                        $("#editor_trans_val").remove();
                                        $("<input id ='editor_trans_val' type ='hidden' value='" + detail.id +"'/>").insertAfter(".Editor-editor");
                                    }else{
                                        MANAGE_FEEDBACK.RemoveRichTextEditor();
                                        $(".edit_entity_form #" + name).val(detail.content);
                                        $(".edit_entity_form #" + name).attr("class","about_entity_val form-control "  + detail.id);
                                    }
				}else if(name === "dictionary_type" 
                                        ||  name === "website_link"){
                                   
                                    $('.edit_entity_form #' + name + ' option:contains(' + content + ')').each(function(){
                                        if ($(this).text() === content) {
                                            $(".edit_entity_form #" + name).removeAttr("selected");
                                            $(".edit_entity_form #" + name).val($(this).val().trim());
                                            return false;
                                        }
                                        return true;
                                    });
                                   
                                    $(".edit_entity_form #" + name).attr("class","about_entity_val form-control "  + detail.id);
				}else if(name !== "rating" && name !== "image"){
                                    $('.edit_entity_form table tr:nth-last-child(2)').before(MANAGE_FEEDBACK.AddHTMLTextAreaElement(name,detail.content,detail.id));
                                }
			}
                       }
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.remove_post:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       $("#front_end_posts" + JsonObject.id).remove();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;   
                }
                 case MANAGE_CONSTANTS.function.remove_entity_detail:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_CONSTANTS.element.change.parent().parent().remove();
                       
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;   
                }
                case MANAGE_CONSTANTS.function.edit_entity:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       //Set updated values
                       
                       MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(1)').html("<a target ='_tab' href ='dictionary/xitsonga?_="+ JsonObject.name + "'>" + JsonObject.name + "</a>");
                       MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(2)').html(JsonObject.details[0].content);
                       MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(3)').text(JsonObject.typeValue);
                       
                       if(JsonObject.deleteItem === "0"){
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#FFC1C1");
                          
                           $("#delete_entity").attr("checked", false);
                       }else{
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");
                        }
                       
                       MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;   
                }
                case MANAGE_CONSTANTS.function.edit_question:{
                  if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                        if(JsonObject.delete === "0"){
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#FFC1C1");

                           //MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(5)').text("");
                           $("#delete_question").attr("checked", false);
                           
                           MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                           MANAGE_FEEDBACK.PutHTMLinNormalState();
                       }else{
                            MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(1)').text(JsonObject.questionText);

                            MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");

                            MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                            MANAGE_FEEDBACK.PutHTMLinNormalState();
                        }
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;      
                }
                case MANAGE_CONSTANTS.function.edit_exercise:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       if(JsonObject.delete === "0"){
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#FFC1C1");

                           //MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(5)').text("");
                           $("#delete_exercise").attr("checked", false);
                           
                           MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                           MANAGE_FEEDBACK.PutHTMLinNormalState();
                       }else{
                            var published = JsonObject.published === "0"?"No":"Yes";
                            MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(1)').html("<a href ='manage/exercises?_=" + JsonObject.title+ "'>" + JsonObject.title + "</a>");
                            MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(2)').text(JsonObject.text);
                            MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(5)').text(published);

                            MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");


                            MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                            MANAGE_FEEDBACK.PutHTMLinNormalState();
                        }
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.edit_type:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                          //Set updated values
                       
                       MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(1)').text(JsonObject.name);
                       MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(2)').text(JsonObject.typeValue);
                       
                       if(JsonObject.deleteType === "0"){
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#FFC1C1");
                           MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#FFC1C1");

                           //MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(5)').text("");
                           $("#delete_type").attr("checked", false);
                           
                       }else{
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");
                            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");
                            
                        }
                        MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;   
                }
                case MANAGE_CONSTANTS.function.add_entity_detail:{
                       
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                        MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                        
                        $(".basic_form input[type=text]").val("");
                        $(".basic_form textarea").val("");
                        
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.add_entity_bulk:
                {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       $(".basic_form input[type=text]").val("");
                       $(".basic_form textarea").val("");
                       MANAGE_FEEDBACK.AddSuccessMessageToHTML(data.infoMessage);
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessageToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                    break;
                }
                case MANAGE_CONSTANTS.function.submit_exercise:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_FEEDBACK.RedirectToHTMLPage("learn?_=" + data.infoMessage + "&-=results");
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break; 
                }
                case MANAGE_CONSTANTS.function.edit_user: {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                        var access = JsonObject.right;
                        var text = "No";
                        if(access === "1"){
                           text = "Yes"; 
                        }
                        
                        MANAGE_CONSTANTS.element.change.parent().parent().find('td:nth-child(4)').text(text);

                        MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");

                        MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML(data.infoMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.add_answers:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                        var len = 0;
                        for (var i in JsonObject.answers) { len++ };
                        MANAGE_CONSTANTS.element.change.parent().parent().find('.add_answers').html("+ " + (len) +" answers");
                       
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");
                        MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");

                       
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break; 
                }
                case MANAGE_CONSTANTS.function.add_question:
                {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                        $(".basic_form input[type=text]").val("");
                        $(".basic_form textarea").val("");

                        var vTr = "<tr style ='background:#B4EEB4'>";
                            vTr += "<td>" + JsonObject.questionText + "</td>";
                            vTr += "<td>You</td>";
                            vTr += "<td>Radio</td>";
                            vTr += "<td>" + "<a id = '" + data.infoMessage +"' class ='add_answers' href ='manage/exercises?_=" + JsonObject.questionText+ "'>+ 0 answers</a>" + " - <a id = '" + data.infoMessage +"' class ='edit_question' href ='manage/exercises?_=" + JsonObject.questionText+ "'>Edit</a>" + "</td>";
                            vTr += "</tr>";
                            
                        $('#exercises_data_table tbody').prepend(vTr);

                        MANAGE_FEEDBACK.AddSuccessMessage2ToHTML("Question Added Succesfully");
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.add_answers:
                case MANAGE_CONSTANTS.function.add_item_type:
                {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                        $(".basic_form input[type=text]").val("");
                        $(".basic_form textarea").val("");
                        
                        MANAGE_FEEDBACK.RemoveRichTextEditor();
                        
                        MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.add_exercise:
                {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === MANAGE_CONSTANTS.status.success){
                        $(".basic_form input[type=text]").val("");
                        $(".basic_form textarea").val("");

                        var vTr = "<tr style ='background:#B4EEB4'>";
                            vTr += "<td>" + "<a href ='manage/exercises?_=" + JsonObject.title+ "'>" + JsonObject.title + "</a>" +"</td>";
                            vTr += "<td>" + JsonObject.text +"</td>";
                            vTr += "<td>You</td>";
                            vTr += "<td>" + new Date().toJSON().slice(0,10) +"</td>";
                            vTr += "<td>No</td>";
                            vTr += "<td><a id='" + data.infoMessage +"' class='edit_exercise' href='manage/exercises'>Edit Exercise</a></td>";
                            vTr += "</tr>";
                            
                        $('#exercises_data_table tbody').prepend(vTr);
                        
                        MANAGE_FEEDBACK.RemoveRichTextEditor();
                        
                        MANAGE_FEEDBACK.AddSuccessMessage2ToHTML("Exercise Added Successfully");
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.add_entity:
                {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }else if(data.status === MANAGE_CONSTANTS.status.warning){
                        var aMessage = "<b>" + data.errorMessage + "</b><br/><hr>";
                            aMessage = aMessage + "- Entity \"" + JsonObject.name + "\" already exists. The URL is <a target ='_tab' href ='dictionary/xitsonga?_=" + JsonObject.name +"'>" + JsonObject.name +"</a>";
                            aMessage = aMessage + "<hr>";
                            aMessage = aMessage + "- If entity has different meaning click on \"Force add\". The system will ignore the same spelling." ;
                            aMessage = aMessage + "<hr>";
                            aMessage = aMessage + "- If entity is the same, but definition is incorrect or incomplete. Click \"Cancel\" and edit the entity.";
                        MANAGE_FEEDBACK.AddWarningDialogToHTML(aMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                        $(".basic_form input[type=text]").val("");
                        $(".basic_form textarea").val("");
                        $(".add_entity_form #item_type option").attr("selected", false);
                        $(".add_entity_form #website_link option").attr("selected", false);
                        $(".add_entity_form #dictionary_type option").attr("selected", false);

                        $(".add_entity_form #item_type option").filter(function () {return $(this).html() === '--Default--' ; }).attr("selected", true);
                        $(".add_entity_form #website_link option").filter(function () {return $(this).html() === '--Default--' ; }).attr("selected", true);
                        $(".add_entity_form #dictionary_type option").filter(function () {return $(this).html() === '--Default--' ; }).attr("selected", true);
                        
                        var vTr = "<tr style ='background:#B4EEB4'>";
                            vTr += "<td><a target ='_tab' href ='dictionary/xitsonga?_=" + JsonObject.name +"'>" + JsonObject.name +"</a></td>";
                            vTr += "<td>" + JsonObject.details[0].content +"</td>";
                            vTr += "<td>" + JsonObject.typeValue +"</td>";
                            vTr += "<td>You</td>";
                            vTr += "<td>" + new Date().toJSON().slice(0,10) +"</td>";
                            vTr += "<td>"+ "<a id='" + data.infoMessage+ "' class='edit_entity' href='manage/entity'>Edit Entity</a> - <a id='" + data.infoMessage+ "' class='edit_image' href='manage/entity'>Add Image</a>" +"</td>";
                            vTr += "</tr>";
                            
                        $('#entity_data_table tbody').prepend(vTr);
                        
                        MANAGE_FEEDBACK.RemoveRichTextEditor();
                        
                        MANAGE_FEEDBACK.AddSuccessMessage2ToHTML("Entity Added Succesfully");
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;
                }
                case MANAGE_CONSTANTS.function.send_suggestion_email:{
                     if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       $(".user_suggestions_send_form #suggestion").empty();
                        setTimeout(function(){
                            window.location = "dictionary/xitsonga?_=" + $(".user_suggestions_send_form #main").val() + "&success=yes";
                        }, 500);
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;              
                }
                case MANAGE_CONSTANTS.function.remove_translation: {
                     if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){  
                        MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                        var editLink = $("#audits_api_data_table #" + $("#editConfig").val());
                        $(editLink).parent().siblings().addClass("removed_row");
                        $(editLink).parent().children("a").text("")
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    } 
                    break;     
                 }
                case MANAGE_CONSTANTS.function.add_translation: {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       $(".add_translation_form input[type=text]").val("");
                       $(".add_translation_form input[type=check]").attr('checked', false);
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    } 
                    break;
                }
                case MANAGE_CONSTANTS.function.edit_translation: {
                     if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                       
                       /**
                        * 
                        */
                        var fields = ["item", "replacement", "language", "pattern"];
                        var check = ["swap_left", "swap_right", "push_first", "push_last"];

                        var editLink = $("#audits_api_data_table #" + $("#editConfig").val());
                        for (var index = 0; index < fields.length; index++) {
                            var text = $(".edit_translation_form #" + fields[index]).val();
                            
                            $(editLink).parent().siblings("." + fields[index]).text(text);
                        }

                        for (var index = 0; index < check.length; index++) {
                            var text = "0";
                            if ($(".edit_translation_form #" + check[index]).prop('checked')) {
                                text = "1";
                            }
                            
                            $(editLink).parent().siblings("." + check[index]).text(text);
                        }
                        
                        
                        $(editLink).parent().siblings().addClass("updated_row");
                        
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    } 
                    break;   
                }
                
                case MANAGE_CONSTANTS.function.update_file: {
                       if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    } 
                    break; 
                }
                case MANAGE_CONSTANTS.function.remove_translation: {
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    } 
                    break;
                }
                case MANAGE_CONSTANTS.function.send_server_migration_email:{
                    if(data.status === MANAGE_CONSTANTS.status.failed){
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(data.errorMessage);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else if(data.status === MANAGE_CONSTANTS.status.success){
                       MANAGE_FEEDBACK.AddSuccessMessage2ToHTML(data.infoMessage);
                       $(".send_form input[type=text]").val("");
                       $(".send_form textarea").val("");
                       $(".send_form .Editor-editor").empty();
                       MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    else{
                        MANAGE_FEEDBACK.AddErrorMessage2ToHTML(USER_CONSTANTS.error.critical);
                        MANAGE_FEEDBACK.PutHTMLinNormalState();
                    }
                    break;                  
                }

            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch(call){
                case MANAGE_CONSTANTS.function.rate_entity:{
                    alert(MANAGE_CONSTANTS.error.critical);
                }
                case MANAGE_CONSTANTS.function.add_entity_bulk:{
                    MANAGE_FEEDBACK.AddErrorMessageToHTML(jqXHR.responseText);
                    MANAGE_FEEDBACK.PutHTMLinNormalState();
                    break;
                }
                case MANAGE_CONSTANTS.function.add_translation:
                case MANAGE_CONSTANTS.function.remove_translation:
                case MANAGE_CONSTANTS.function.update_file:
                case MANAGE_CONSTANTS.function.edit_translation:
                case MANAGE_CONSTANTS.function.send_suggestion_email:
                case MANAGE_CONSTANTS.function.download_type_as_PDF: 
                case MANAGE_CONSTANTS.function.edit_user: 
                case MANAGE_CONSTANTS.function.getEntityDetailsByEntityId:
                case MANAGE_CONSTANTS.function.submit_exercise:
                case MANAGE_CONSTANTS.function.edit_question:
                case MANAGE_CONSTANTS.function.edit_exercise:
                case MANAGE_CONSTANTS.function.getAnswersByQuestionID:
                case MANAGE_CONSTANTS.function.send_server_migration_email:
                case MANAGE_CONSTANTS.function.remove_entity_detail:
                case MANAGE_CONSTANTS.function.remove_post:
                case MANAGE_CONSTANTS.function.add_entity_detail:
                case MANAGE_CONSTANTS.function.add_post:
                case MANAGE_CONSTANTS.function.edit_entity:
                case MANAGE_CONSTANTS.function.edit_type:
                case MANAGE_CONSTANTS.function.add_question:
                case MANAGE_CONSTANTS.function.add_exercise:
                case MANAGE_CONSTANTS.function.add_answers:                
                case MANAGE_CONSTANTS.function.add_entity:
                case MANAGE_CONSTANTS.function.add_item_type:
                {
                    MANAGE_FEEDBACK.AddErrorMessage2ToHTML(MANAGE_CONSTANTS.error.critical);
                    //MANAGE_FEEDBACK.AddErrorMessageToHTML(jqXHR.responseText);
                    MANAGE_FEEDBACK.PutHTMLinNormalState();
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
var MANAGE_DATA = {};

/**
 * Returns type information in JSON format
 * @param {Array} data type information
 * @returns {JSON}
 * @static
 */
MANAGE_DATA.add_type_json = function(data){
    return {
        "description":data.description,
        "type":data.type
    };
};

/**
 * Returns type information in JSON format
 * 
 * @param {Array} data type information
 * @returns {JSON}
 * @static
 */
MANAGE_DATA.edit_type_json = function(data){
    return {
        "id":data.id,
        "name":data.name,
        "deleteType": data.deleteType,
        "itemType":data.itemType
    };
};


/**
 *  User component data validator
 *  
 *  @returns {Boolean}
 */
var MANAGE_VALIDATOR = {};

/**
 * @returns {Boolean}
 */
MANAGE_VALIDATOR.validate_add_type_input = function(){
    return true;
};


/**
 * 
 * @return styles HTML with feeback messages
 */
var MANAGE_FEEDBACK = {};

/**
 * Returns HTML Table row with content
 * 
 * @param {type} aID
 * @param {type} aDescription
 * @param {type} aContent
 * @returns {String}
 */
MANAGE_FEEDBACK.AddHTMLTextAreaElement = function (aDescription, aContent, aID){
    var vReturn = "<tr class ='dynamically_added'><td><span class ='remove_detail' style='font-size: 18px;font-weight: bold;margin-left:0px;color: red;display:inline-block;padding: 5px;cursor: pointer' title='Remove detail' alt ='Remove detail'>-</span><span style ='font-size: 15px;text-transform:uppercase'>" + aDescription +"</span><br/><span style ='font-size: 11px;'>A default description for type</span></td>";
        vReturn = vReturn + "<td><textarea class='about_entity_val form-control "  + aID + "' rows='1' cols='30'>"+ aContent+"</textarea></td>";
        vReturn = vReturn + "</tr>";
        
        return vReturn;
};



/**
 * Returns HTML Table row with content
 * 
 * @returns {String}
 */
MANAGE_FEEDBACK.AddHTMLSelectAndTextAreaElement = function (){
    var vHTML = $("#description_types").html();
    
    var vReturn = "<tr class ='dynamically_added'><td><select style ='margin-top:2px'>" + vHTML +"</select><br/><span style ='font-size: 11px;'>Select a description type</span></td>";
        vReturn = vReturn + "<td><textarea style ='margin-top:2px' class='about_entity_val form-control' rel = 'different_id' rows='1' cols='30'></textarea></td>";
        vReturn = vReturn + "</tr>";
        
        return vReturn;
};

/**
 * Adds messages to HTML span, messages are styled in red
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with error messages
 */
MANAGE_FEEDBACK.AddErrorMessageToHTML = function (aMessage){
   // $(".error,.error2").notify(aMessage,{ className: "error", position:"bottom" });
    
    $("#fileContentsServer").append(aMessage + "<br/>");
    document.getElementById("fileContents").innerHTML = "";

    $("#upload_entity_dialog").animate({
          scrollBottom: $("#openUploadModal dialog").scrollTop() + $("#fileContentsServer").height()
    });
};

/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddSuccessMessageToHTML = function (aMessage){
    //$(".error,.error2").notify(aMessage,{ className: "success", position:"bottom" });
    
    $("#fileContentsServer").append(aMessage + "<br/>");
    document.getElementById("fileContents").innerHTML = "";

    $("#upload_entity_dialog").animate({
          scrollBottom: $("#openUploadModal dialog").scrollTop() + $("#fileContentsServer").height()
    });
};


/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddErrorMessage2ToHTML = function (aMessage){
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
    MANAGE_FEEDBACK.PutHTMLinNormalState();

};



/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddErrorMessage2ToHTML = function (aMessage){
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
    MANAGE_FEEDBACK.PutHTMLinNormalState();

};
/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddSuccessMessage2ToHTML = function (aMessage){
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
    MANAGE_FEEDBACK.PutHTMLinNormalState();
};
/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddDownloadDialogToHTML = function (aMessage, aURL){
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
                url = "generated/" + aURL;
                var win = window.open(url, '_blank');
                win.focus();
            }
            },
            {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
               $noty.close();
               MANAGE_FEEDBACK.PutHTMLinNormalState();
            }
            }
        ]
    });    
};
/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddWarningDialogToHTML = function (aMessage){
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
            {addClass: 'btn btn-primary', text: 'Force Add', onClick: function ($noty) {
                var vName = $(".add_entity_form #name").val();
                var vType = $(".add_entity_form #item_type").val();
                var vTypeValue = $(".edit_entity_form #item_type option[value='" + vType +"']").text();
                var vTown = $(".add_entity_form #town").val();

                var vDetail  = {};
                var vCount = 0;
                $(".add_entity_form .about_entity_val").each(function(e){
                    vDetail[vCount] = {
                        "itemType": $(this).attr('id'),
                        "content": $(this).val()
                    };
                    vCount ++;
                });

                var vItemJSON = {
                    "name": vName,
                    "force": 1,
                    "itemType": vType,
                    "typeValue":vTypeValue,
                    "details" : vDetail
                };
                $noty.close();
                MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_entity, vItemJSON);
            }
            },
            {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
               $noty.close();
               MANAGE_FEEDBACK.PutHTMLinNormalState();
            }
            }
        ]
    });    
};
/**
 * Adds messages to HTML span, messages are styled in green
 * 
 * @param {string} aMessage
 * @return Unknown styles HTML with success messages
 */
MANAGE_FEEDBACK.AddUpdateSuccessMessageToHTML = function (aMessage){
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
    MANAGE_FEEDBACK.PutHTMLinNormalState();
};

/**
 * Disables HTML input elements on current page
 * 
 */
MANAGE_FEEDBACK.PutHTMLinProcessingState = function (){
    $(".btn").attr('disabled',true);
    $(".basic_form input").attr('disabled',true);
    $(".basic_form textarea").attr('disabled',true);
    
    $(".loading_image").append("<img src ='assets/images/loading_image.gif'/>");
    
};
/**
 * Enables HTML input elements on current page
 * 
 */
MANAGE_FEEDBACK.PutHTMLinNormalState = function (){    
    $(".btn").attr('disabled',false);
    $(".basic_form input").attr('disabled',false);
    $(".basic_form textarea").attr('disabled',false);
    
    $(".loading_image").empty();
};

/**
 * Redirects user to Welcome page
 */
MANAGE_FEEDBACK.RedirectToHTMLPage = function (aPage) {
    window.location = aPage;
}

/**
 * Redirects user to Welcome page
 */
MANAGE_FEEDBACK.AppendHTMLToDiv = function (aDivID,aContent) {
}
MANAGE_FEEDBACK.RemoveRichTextEditor = function () {
    $(".Editor-container").remove();
    
    $(".about_entity_val#english_translation").css("display","inline");
}

/**
 * 
 * @param {type} aDivID
 * @returns {undefined}
 */
MANAGE_FEEDBACK.AppendRichTextEditor = function (aElement) {
        $(".about_entity_val#english_translation").val("");
        $(".Editor-container").remove();
        aElement.Editor({
            'texteffects':false,
            'aligneffects':false,
            'textformats':false,
            'fonteffects':false,
            'actions' : false,
            'insertoptions' : false,
            'extraeffects' : false,
            'advancedoptions' : false,
            'screeneffects':false,
            'bold': true,
            'italics': true,
            'underline':true,
            'ul':false,
            'undo':false,
            'redo':false,
            'l_align':false,
            'r_align':false,
            'c_align':false,
            'justify':false,
            'insert_link':true,
            'unlink':true,
            'insert_img':false,
            'hr_line':false,
            'block_quote':false,
            'source':false,
            'strikeout':false,
            'indent':false,
            'outdent':false,
            'fonts':false,
            'print':false,
            'rm_format':false,
            'status_bar':false,
            'font_size':false,
            'color':false,
            'splchars':false,
            'select_all':false,
            'togglescreen':false
        });   
}
