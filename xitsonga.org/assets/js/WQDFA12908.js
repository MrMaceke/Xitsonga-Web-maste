/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(e){
    
    //Tett Box
    
    MANAGE_FEEDBACK.AppendRichTextEditor($(".richText"));
    
    // Tables
    var currentElementDiv = "";
    $('#item_types_data_table').dataTable({"bPaginate": true,"sPaginationType": "full_numbers"});
    $('#entity_data_table').dataTable({"bPaginate": true,"sPaginationType": "full_numbers"});
    $('#exercises_data_table').dataTable({"bPaginate": true,"sPaginationType": "full_numbers"});
    
    /*
    $('#entity_data_table').dataTable({
                "sPaginationType": "full_numbers",
                "aLengthMenu": [[10,50,100,200,250,300,-1], [10,50,100,200,250,300,"All"]],
                "iDisplayLength": 10,
                "sDom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>'
             });
    */

    
    $(document).on('click', '.add_rich_text', function () {
        $(this).parent().parent().find("textarea").val("");
        
        if($(this).parent().parent().find("textarea").css("display") == "none"){
            MANAGE_FEEDBACK.RemoveRichTextEditor();
        }else{
            MANAGE_FEEDBACK.AppendRichTextEditor($(this).parent().parent().find("textarea"));
        }
    });

    $(document).on('change propertychange  click keyup keydown paste cut', 'textarea', function () {
        $(this).height(0).height(this.scrollHeight);
    }).find('textarea').change();
    
    $(".dialog .close").addClass("close2");
    
    var rowCount2 = 0;
    $(".close2").click(function(e){
        $("#overlay").remove();
        var id =  currentElementDiv;
        rowCount2 = 0;
        $('html, body').animate({
                scrollTop: $("#" + id).offset().top - 200
        }, 500);
        MANAGE_FEEDBACK.RemoveRichTextEditor();
        $.noty.closeAll(); 
        MANAGE_FEEDBACK.PutHTMLinNormalState();
    });
    
    $(".cancel").click(function(e){
        $("#overlay").remove();
        var id =  currentElementDiv;
        
        $('html, body').animate({
                scrollTop: $("#" + id).offset().top - 200
        }, 500);
        rowCount2 = 0;
        MANAGE_FEEDBACK.RemoveRichTextEditor();
        $.noty.closeAll() ;
        MANAGE_FEEDBACK.PutHTMLinNormalState();
    });
   
    // add functions
   
    $('#add_type').click(function(e){e.preventDefault(); addItemType();});
    $('#add_translation').click(function(e){e.preventDefault(); addTranslation();});
    $('#send_system_email').click(function(e){e.preventDefault(); sendEmail();});
    $('#add_entity').click(function(e){e.preventDefault(); addEntity();});
    $('#add_exercise').click(function(e){e.preventDefault(); addExercise();});
    $('#add_question').click(function(e){e.preventDefault(); addQuestion();});
    $('#update_answers').click(function(e){e.preventDefault(); addAnswers();});
    $('#update_exercise').click(function(e){e.preventDefault(); editExercise();});
    $('#update_question').click(function(e){e.preventDefault(); editQuestion();});
    $('#edit_type').click(function(e){e.preventDefault(); editType($(".edit_type_form #type_id").val());});
    $('#update_entity').click(function(e){e.preventDefault(); editEntity($(".edit_entity_form #entity_id").val());});
    $('#update_user').click(function(e){e.preventDefault(); editUser($(".update_user_form #user_id").val());});
    
    var rowCount = 2;
    
    
    $(document).on("click","a.add_description_row",function(e){
        $('.edit_entity_form table tr:nth-last-child(2)').before(MANAGE_FEEDBACK.AddHTMLSelectAndTextAreaElement());
        rowCount2 ++;
    });
    
    $(document).on("click","a.add_description_row",function(e){
        $('.add_entity_form table tr:nth-last-child(2)').before(MANAGE_FEEDBACK.AddHTMLSelectAndTextAreaElement());
        rowCount2 ++;
    });
    
    $(document).on("click","a.remove_description_row",function(e){
        if(rowCount2 > 0){
            $('.add_entity_form table tr:nth-last-child(3)').remove();
            rowCount2 --;
        }
    });
    
    $(document).on("click","a.remove_description_row",function(e){
        if(rowCount2 > 0){
            $('.edit_entity_form table tr:nth-last-child(3)').remove();
            rowCount2 --;
        }
    });
    
    
    $(document).on("click","a.remove_answer_row",function(e){
        var count = $('.' + "answer_row").length + 1;
        if(count > 2){
            $('.answers_table .answer_row:last').remove();
            rowCount --;
        }
    });
    
    $(document).on("click","a.add_answer_row",function(e){
        var count = $('.' + "answer_row").length + 1;
        var append = "<tr class ='answer_row'>\n\
                        <td>\n\
                            <span style ='font-size: 15px;text-transform:uppercase'>Answer " + count +"</span><br/>\n\
                            <span style ='font-size: 11px;'>Text of the answer</span>\n\
                        </td>\n\
                        <td>\n\
                            <textarea id ='title' placeholder='A title of the answer' class='form-control' rows='1' cols='50'></textarea>\n\
                        </td>\n\
                        <td>\n\
                            <select class ='correct_answer form-control'>\n\
                                <option value='0'>Incorrect</option>\n\
                                <option value='1'>Correct</option>\n\
                            </select>\n\
                        </td>\n\
                      </tr>";
        $('.answers_table .lastTableRow').before(append);
        
       
    });
    
    // upload image
    $('#MyUploadForm').submit(function(e) {
        var options = {
           target:   '#entity_image',  beforeSubmit:  beforeSubmit,resetForm: true
        };
        $(this).ajaxSubmit(options);        
        return false;
    });
    
    function afterSubmit(){
        $("#image_status").html("Copyrighted images are not allowed.")
    }
    function beforeSubmit(){
        $("#image_status").html("Copyrighted images are not allowed.");
       if (window.File && window.FileReader && window.FileList && window.Blob){
            if( !$('#imageInput').val()){$("#image_status").html("Please select file"); return false;}
            var fsize = $('#imageInput')[0].files[0].size;var ftype = $('#imageInput')[0].files[0].type; // get file type
            switch(ftype){
                case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':break;
                default: $("#image_status").html("<b>"+ftype+"</b> Unsupported file type!");return false;
            }
            if(fsize>1048576 ){$("#image_status").html("<b>"+fsize +"</b> Too big Image file!.");return false;}
            $("#entity_image").html("<img src ='assets/images/loading_image.gif'/>");  
                        
            element = MANAGE_CONSTANTS.element.change;
            element.text("Edit Image");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").css("background","#B0E2FF");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(1)').css("background","#B0E2FF");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(2)').css("background","#B0E2FF");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(3)').css("background","#B0E2FF");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(4)').css("background","#B0E2FF");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");
            MANAGE_CONSTANTS.element.change.parent().parent("tr").find('td:nth-child(5)').css("background","#B0E2FF");
        }  
        else{$("#image_status").html("Please upgrade your browser.");return false;}
    }
    // add functions
    $(document).on("click","a.overlay",function(e){
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
    });
    
    // edit functions
    $(document).on("click","span.remove_detail",function(e){
        e.preventDefault();
        
        MANAGE_CONSTANTS.element.change = $(this);
        
        var vId = $(this).parent().parent().find('textarea').attr('class').split(" ")[2];
        
        removeEntityDetail(vId);
    });
    
    $(document).on("click","a.edit_type",function(e){
        e.preventDefault(); 
        
        var vName = $(this).parent().parent().find('td:nth-child(1)').text();
        var vType = $(this).parent().parent().find('td:nth-child(2)').text();
        
        MANAGE_CONSTANTS.element.change = $(this);
        
        $(".edit_type_form #type_id").val($(this).attr('id'));
        $(".edit_type_form #description").val(vName);
        $('.edit_type_form #type option').filter(function (){ return $(this).text() === vType; }).attr("selected", true);
        
        currentElementDiv = $(this).attr('id');
        
        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);

        window.location = aLink[0]  + "#openEditModal";
    });
    
    $(document).on("click","a.edit_user",function(e){
        e.preventDefault(); 
        
        currentElementDiv = $(this).attr('id');
        
        MANAGE_CONSTANTS.element.change = $(this);
        
        var aLink = window.location.href;
        
        $("#username_title").html($(this).parent().parent().find('td:nth-child(1)').text());
        $(".update_user_form #user_id").val($(this).attr('id'));
        
        var access = $(this).parent().parent().find('td:nth-child(4)').text();
        
        if(access === "Yes"){
            $(".update_user_form #right").val("1");
        }else{
            $(".update_user_form #right").val("0");
        }
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        
        window.location = aLink[0]  + "#openEditUserModal";
    });
    
     $(document).on("click","a.edit_exercise",function(e){
        e.preventDefault(); 
        
        var vName = $(this).parent().parent().find('td:nth-child(1)').text();
        var vDesc = $(this).parent().parent().find('td:nth-child(2)').text();
        var vPublished = $(this).parent().parent().find('td:nth-child(5)').text() === "No"?"0":"1";
       
        MANAGE_CONSTANTS.element.change = $(this);
       
        currentElementDiv = $(this).attr('id');
        
        $(".edit_exercise_form #title").val(vName);
        $(".edit_exercise_form #description").val(vDesc);
        $(".edit_exercise_form #exerciseID").val(currentElementDiv);
        $(".edit_exercise_form #published").val(vPublished);
        
        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        
        window.location = aLink[0]  + "#openEditExerciseModal";
    });
    
    $(document).on("click","#openSendMailModalButton",function(e){
        e.preventDefault(); 
       
        MANAGE_CONSTANTS.element.change = $(this);
       
        currentElementDiv = $(this).attr('id');
                
        MANAGE_FEEDBACK.AppendRichTextEditor($(".send_form #content"));
        
        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        window.location = aLink[0]  + "#openSendMailModal";
    });
    
     $(document).on("click","a.edit_question",function(e){
        e.preventDefault(); 
        
        var vName = $(this).parent().parent().find('td:nth-child(1)').text();
       
        MANAGE_CONSTANTS.element.change = $(this);
       
        currentElementDiv = $(this).attr('id');
        
        $(".edit_question_form #title").val(vName);
        $(".edit_question_form #question_id").val(currentElementDiv);
        
        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        
        window.location = aLink[0]  + "#openQuestionEditModal";
    });
    
    $(document).on("click","a.edit_image",function(e){
        e.preventDefault(); 
        
        $("#image_status").html("Copyrighted images are not allowed.");
        
        $(".entity_image img").attr("src","assets/images/entity/no_image.png");
        
        MANAGE_CONSTANTS.element.change = $(this);
        
        var id = $(this).attr('id');
        
        currentElementDiv = id;
        
        $("#image_entity_id2").val(currentElementDiv);
        
        var vName = $(this).parent().parent().find('td:nth-child(1)').text();
        
        $("#image_title").html("* " + vName);
        
        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        
        MANAGE_FEEDBACK.RemoveRichTextEditor();
        
        
        window.location = aLink[0]  + "#openUploadImageModal";
        
        loadEntityImage($(this).attr('id'),$(this), true);
    });
    
    
    $(document).on("click","a.add_answers",function(e){
        e.preventDefault();
        
        currentElementDiv = $(this).attr('id');
        
        MANAGE_CONSTANTS.element.change = $(this);
        
        $(".add_answers_form #question_id").val(currentElementDiv);
        
        var aTitle = $(this).parent().parent().find('td:nth-child(1)').text();
        
        $("#answerTitle").html("* Question - " + aTitle);
        
        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        
        window.location = aLink[0]  + "#openAnswersModal";
        
        $(".answer_row").remove();
        
        loadAnswers($(this).attr('id'));
    });
    
    $(document).on("click","a.edit_entity",function(e){
        e.preventDefault(); 
        
        currentElementDiv = $(this).attr('id');
        
        MANAGE_CONSTANTS.element.change = $(this);
        
        $(".edit_entity_form #entity_id").val($(this).attr('id'));
        
        var vName = $(this).parent().parent().find('td:nth-child(1)').text();
        var vType = $(this).parent().parent().find('td:nth-child(3)').text();
        
        var currentElement = $(this);
        
        $(".edit_entity_form #name").val(vName);
        
        $(".dynamically_added").remove();
        
        $(".edit_entity_form #item_type option").attr("selected", false);
        $(".edit_entity_form #website_link option").attr("selected", false);
        $(".edit_entity_form #dictionary_type option").attr("selected", false);
        
        $(".edit_entity_form #item_type option").filter(function () {return $(this).html() === '--Default--' ; }).attr("selected", true);
        $(".edit_entity_form #website_link option").filter(function () {return $(this).html() === '--Default--' ; }).attr("selected", true);
        $(".edit_entity_form #dictionary_type option").filter(function () {return $(this).html() === '--Default--' ; }).attr("selected", true);


        var aLink = window.location.href;
        
        aLink = aLink.split("#");
        
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        
        $(".about_entity_val#english_translation").val("");
        
        window.location = aLink[0]  + "#openEditModal";
        
        loadEntityDetails($(this).attr('id'),currentElement);
    });

    //Supporting functions
    
    function addItemType(){
        var vItemData = new Array();
        vItemData["item"] = $(".add_type_form #description").val();
        vItemData["type"] = $(".add_type_form #type").val();
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_item_type,MANAGE_DATA.add_type_json(vItemData));
    }
    
    function addTranslation(){
        var vItemData = {
            "item": $(".add_translation_form #item").val(),
            "replacement": $(".add_translation_form #replacement").val(),
            "language": $(".add_translation_form #language").val(),
            "pattern": $(".add_translation_form #pattern").val(),
            "swapLeft": $(".add_translation_form #swapLeft").is(':checked')? 1:0,
            "swapRight": $(".add_translation_form #swapRight").is(':checked')? 1:0,
            "pushFirst": $(".add_translation_form #pushFirst").is(':checked')? 1:0,
            "pushLast": $(".add_translation_form #pushLast").is(':checked')? 1:0
        };

        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_translation,vItemData);
    }
    
    function editQuestion(){
        var vTitle = $(".edit_question_form #title").val();
        var vId = $(".edit_question_form #question_id").val();
        var vDelete = $("#delete_exercise").is(":checked")? "0":"1";

        var vJSON = {
            "question_id": vId,
             "delete":vDelete,
            "questionText": vTitle
        };
        
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.edit_question,vJSON);
    }
    
    function editExercise(){
        var vTitle = $(".edit_exercise_form #title").val();
        var vText = $(".edit_exercise_form #description").val();
        var vId = $(".edit_exercise_form #exerciseID").val();
        var vPublished = $(".edit_exercise_form #published").val();
        var vDelete = $("#delete_exercise").is(":checked")? "0":"1";
        
        var vJSON = {
            "exercise_id": vId,
            "title": vTitle,
            "text": vText,
            "delete":vDelete,
            "published":vPublished
        };
        
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.edit_exercise,vJSON);
    }
    
    function editUser(vId){
        var vJSON = {
            "user_id": vId,
            "right": $(".update_user_form #right").val()
        };
        
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.edit_user,vJSON);
    }
    
    function editType(vId){
        
        var vName = $(".edit_type_form #description").val();
        var vType = $(".edit_type_form #type").val();
        var vDelete = $("#delete_type").is(":checked")? "0":"1";
        var vTypeValue = $(".edit_type_form #type option[value='" + vType +"']").text();

        var vItemJSON = {
            "id": vId,
            "name": vName,
            "typeValue":vType,
            "deleteType":vDelete,
            "itemType": vType
        };

        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.edit_type,MANAGE_DATA.edit_type_json(vItemJSON));
    }
    
    function loadEntityImage(id,element,image){         
        var vItemJSON = {
            "entityId"  : id,
            "image"     :image
        };
        MANAGE_CONSTANTS.element.change = element;
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.getEntityDetailsByEntityId,vItemJSON);
    }
    
    function loadEntityDetails(id,element){         
        var vItemJSON = {
            "entityId": id
        };
        MANAGE_CONSTANTS.element.change = element;
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.getEntityDetailsByEntityId,vItemJSON);
    }
    
    function loadAnswers(id){         
        var vItemJSON = {
            "questionID": id
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.getAnswersByQuestionID,vItemJSON);
    }
    
    function sendEmail(){
       var vUserType = $(".send_form #user_type").val();
       var vSubject = $(".send_form #subject").val();
       var vContent =  $(".send_form .Editor-editor").html();
       
        var vJSON = {
            "userType": vUserType,
            "subject": vSubject,
            "content":vContent
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.send_server_migration_email,vJSON);
    }
    
    function addQuestion(){
       var vTitle = $(".add_question_form #title").val();
       var vQuestionId = $(".add_question_form #question_id").val();
       var vCorrent =  $(".add_question_form #correct").val();
       
        var vJSON = {
            "questionText": vTitle,
            "exerciseId": vQuestionId,
            "corrent":vCorrent
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_question, vJSON);
    }
    
    function addAnswers(){
        var vQuestionId = $(".add_answers_form #question_id").val();
       
        var vDetail  = {};
        var vCount = 0;
        $(".add_answers_form .answer_row").each(function(e){
            vDetail[vCount] = {
                "answerId": $(this).find('textarea').val(),
                "answerText": $(this).find('textarea').val(),
                "correct": $(this).find('select').val()
            };
            vCount ++;
        });
       
        var vJSON = {
            "questionID": vQuestionId,
            "answers":vDetail
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_answers, vJSON);
    }
    
    function removeEntityDetail(vId){
        var vJSON = {
            "id": vId
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.remove_entity_detail, vJSON);
    }
    
    function addExercise(){
       var vTitle = $(".add_exercise_form #title").val();
       var vDescription = $(".add_exercise_form #description").val();
       
        var vJSON = {
            "title": vTitle,
            "text": vDescription
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_exercise, vJSON);
    }
    
    function addEntity(){
        var vName = $(".add_entity_form #name").val();
        var vType = $(".add_entity_form #item_type").val();
        var vTypeValue = $(".edit_entity_form #item_type option[value='" + vType +"']").text();
        var vTown = $(".add_entity_form #town").val();
            
        var vDetail  = {};
        var vCount = 0;
        $(".add_entity_form .about_entity_val").each(function(e){
            var content = $(this).val();
            var itemType = $(this).attr('id');
            
            if(content === ""){
                content =  $(".add_entity_form .Editor-editor").html();
            }
            
            if($(this).attr("rel") === "different_id"){
                var aDigitValue = $(this).parent().siblings().find("select").val();
                itemType = $(this).parent().siblings().find("select option[value='" + aDigitValue +"']").text();
            }
            
            vDetail[vCount] = {
                "itemType": itemType,
                "content": content
            };
            vCount ++;
        });

        var vItemJSON = {
            "name": vName,
            "itemType": vType,
            "typeValue":vTypeValue,
            "details" : vDetail
        };
        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_entity, vItemJSON);
    }
    
    function editEntity(id){
        var vId = id;
        var vName = $(".edit_entity_form #name").val();
        var vType = $(".edit_entity_form #item_type").val();
        var vDelete = $("#delete_entity").is(":checked")? "0":"1";
        var vTypeValue = $(".edit_entity_form #item_type option[value='" + vType +"']").text();
        var vDetail  = {};
        var vCount = 0;
        $(".edit_entity_form .about_entity_val").each(function(e){
            
            var aTypeID = $(this).attr('class').split(' ')[2];
            var aItemType = $(this).attr('id');
            if($(this).attr("rel") === "different_id"){
                var aDigitValue = $(this).parent().siblings().find("select").val();
                aItemType = $(this).parent().siblings().find("select option[value='" + aDigitValue +"']").text();
            }
            
            var content = $(this).val();
            if(content === ""){
                aTypeID = $("#editor_trans_val").val();
                content =  $(".edit_entity_form .Editor-editor").html();
            }
            
            vDetail[vCount] = {
                "id": aTypeID,
                "itemType":aItemType,
                "content": content
            };
            vCount ++;
        });

        var vItemJSON = {
            "id": vId,
            "name": vName,
            "itemType": vType,
            "typeValue":vTypeValue,
            "deleteItem":vDelete,
            "details" : vDetail
        };

        MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.edit_entity, vItemJSON);
    }
});

