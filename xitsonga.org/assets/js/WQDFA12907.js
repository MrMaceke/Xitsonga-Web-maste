/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $("#upload_entity_link").click(function(e){ e.preventDefault();uploadEntity();});
   
    function uploadEntity(){
        $("#fileContents").empty();$("#fileContentsServer").empty();
        var file = $('#upload_entity_file')[0].files[0];
        
        if (file) {
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload = function (evt) {
                document.getElementById("fileContents").innerHTML = "If message doesn't disappear, an error occured.";
                var allTextLines = evt.target.result.split("\r\n");
                var headings = allTextLines[0].split(':');
                var record_num = headings.length; 
                var lines = [];
                var vType ="";

                $("#types option").each(function(e){
                   if($(this).html().toLowerCase() === headings[0].toLowerCase()){
                       vType = $(this).val();
                   }
                });
               
                var mainJSON = {};
                var batch = 0;
                var error_occured = false;
                for (var j=1; j< allTextLines.length ; j++) {
                    var entries = allTextLines[j].split(':');
                    
                    var vName = entries[0];
                    var vDetail  = {};
                    var vCount = 0;
                    if(entries.length >= record_num){
                        
                        $("#content_types option").each(function(e){
                            if($(this).html().toLowerCase() === headings[1].toLowerCase() ){
                                var content = entries[1];
                                vDetail[vCount] = {
                                    "itemType": $(this).html(),
                                    "content": content.trim()
                                };
                                vCount ++;
                            }
                            
                            if(entries.length > 2){
                                if($(this).html().toLowerCase() === headings[2].toLowerCase() ){
                                    var content = entries[2];
                                    vDetail[vCount] = {
                                        "itemType": $(this).html().toLowerCase(),
                                        "content": content.trim()
                                    };
                                    vCount ++;
                                }
                            }
                        });

                        
                         $("#tags_types option").each(function(e){
                          
                            if(entries.length > 2){
                                if($(this).html().toLowerCase() === entries[2].toLowerCase() ){
                                    var content = entries[2];
                                    vDetail[vCount] = {
                                        "itemType": headings[2].toLowerCase(),
                                        "content": $(this).val()
                                    };
                                    vCount ++;
                                }
                            }
                        });
                        
                        mainJSON[j - 1] = {
                            "name": vName,
                            "itemType": vType,
                            "details" : vDetail
                        };
                        document.getElementById("fileContents").innerHTML = "Adding line " + j;
                    }else{
                        document.getElementById("fileContents").innerHTML = "Error in line" + j;
                        error_occured = true;
                        return;
                    }

                    if(j % 10 === 0 || j >= allTextLines.length - 1){
                        if(!error_occured){
                            batch ++;
                            document.getElementById("fileContents").innerHTML = "Submitted batch " + batch + "...";
                            var batch_no = j >= allTextLines.length? "last":batch;
                            MANAGE_PROCESSOR.backend_call(MANAGE_CONSTANTS.function.add_entity_bulk, {"batch_no":batch_no ,"entity": mainJSON});
                            mainJSON = {};
                        }else{
                            return;
                        }
                    }
                }
            }
            reader.onerror = function () {
                document.getElementById("fileContents").innerHTML = "error reading file";
            };
        }
    }
});