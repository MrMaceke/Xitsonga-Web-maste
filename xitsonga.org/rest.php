<?php
    $pageName = 'api';
    require_once 'webBackend.php';

    $aWebbackend = new WebBackend();
        
    if(!$aWebbackend->hasAccess($pageName)){
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        require_once './assets/html/metadata.php';
        require_once './assets/html/script.php';
        require_once './assets/html/script_2.php';
    ?>
    <style>
        pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; background: white}
        .string { color: green; }
        .number { color: darkorange; }
        .boolean { color: blue; }
        .null { color: magenta; }
        .key { color: red; }
     </style>
    <script type ="text/javascript">
        $(document).ready(function(){
            /*
            var box = $("#chat_div")
                .chatbox({
                    id:"chat_div", 
                    user:{key : "value"},
                    title : "Quick Help",
                    messageSent : function(id, user, msg) {
                        $("#log").append(id + " said: " + msg + "<br/>");
                        $("#chat_div").chatbox("option", "boxManager").addMsg(id, msg);
                    }
                })
                .toggleBox();
            box.toggleBox();
            */
            $( ".result" ).hide();
            $( ".get_request" ).hide();
           $("#callAPI").click(function(){
                var word = $("#word").val();
                var format = $("#format").val();
                var lang = $("#lang").val();
                var url = "https://www.xitsonga.org/api?method=translate&word=" + word +"&format=" + format + "&language=" + lang;
                 $( ".get_request" ).show();
                $( ".result" ).show();
                $( ".get_request" ).html("GET " + url);
               
                $( ".result" ).html("Processing...");
                
                if(format === "Invalid"){
                    format = "JSON";
                }
                $.get( url,{dataType:"\"" + format + "\""},function( data ) {
                    if(format === "XML"){
                        $( ".result" ).empty();
                        $( ".result" ).append("<xmp>" + formatXml(xmlToString(data)) + "</xmp>"); 
                    }else{
                       $( ".result" ).html( syntaxHighlight(JSON.parse(data)) );
                    }
                });
           })
            function formatXml(xml) {
                var formatted = '';
                var reg = /(>)(<)(\/*)/g;
                xml = xml.replace(reg, '$1\r\n$2$3');
                var pad = 0;
                jQuery.each(xml.split('\r\n'), function(index, node) {
                    var indent = 0;
                    if (node.match( /.+<\/\w[^>]*>$/ )) {
                        indent = 0;
                    } else if (node.match( /^<\/\w/ )) {
                        if (pad != 0) {
                            pad -= 1;
                        }
                    } else if (node.match( /^<\w[^>]*[^\/]>.*$/ )) {
                        indent = 1;
                    } else {
                        indent = 0;
                    }

                    var padding = '';
                    for (var i = 0; i < pad; i++) {
                        padding += '  ';
                    }

                    formatted += padding + node + '\r\n';
                    pad += indent;
                });

                return formatted;
            }
            function xmlToString(xmlData) { 
                var xmlString;
                //IE
                if (window.ActiveXObject){
                    xmlString = xmlData.xml;
                }
                // code for Mozilla, Firefox, Opera, etc.
                else{
                    xmlString = (new XMLSerializer()).serializeToString(xmlData);
                }
                return xmlString;
            }   
            
            function syntaxHighlight(json) {
                if (typeof json != 'string') {
                     json = JSON.stringify(json, undefined, 2);
                }
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    var cls = 'number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'key';
                        } else {
                            cls = 'string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }
        });
    </script>
</head>

<body class="home">
    
    <?php
        require_once './assets/html/nav_api.php';
    ?>

        <header id="head">
            <div class="container">
                <div class="row">
                    <br/><br/>
                    <h4 style ="color:#424242;font-size: 25px">Xitsonga Dictionary API</h4>
                    <p style ="color:#424242">Simple RESTful API for dictionary translation of Xitsonga words to English and Xitsonga word lists.</p>
                   <br/>
                </div>
                <hr>
                <div class="row" style ="color:#424242">
                    <div class="col-sm-2">
                        a word to translate: 
                    </div>
                     <div class="col-sm-2">
                        <select style ="margin-right: 10px" id ="lang" class ='form-control'>
                            <option>Xitsonga</option>
                            <option>English</option>
                            <option>Invalid</option>
                        </select>
                     </div>
                    <div class="col-sm-2">
                        <input id ="word" style ="margin-right: 10px" class ='form-control' placeholder="Speficy word">
                    </div>
                    <div class="col-sm-2">
                        <select id ="format" style ="margin-right: 10px" class ='form-control'>
                            <option>JSON</option>
                            <option>XML</option>
                            <option>Invalid</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-action" id = "callAPI" type="submit">Call the API</button>
                    </div>
                </div>
                <BR/>
            </div>
            <hr>
        </header>

	<div class="container">
            <div class="row">
                <pre class ='get_request'></pre><br/>
                <pre class ='result'></pre>
                <div class="col-sm-6">
                    <h3>How it works</h3>
                    <p>The Xitsonga dictionary API is free dictionary API with thousands of words translated from <a href="dictionary/xitsonga">Xitsonga to English</a> and <a href="dictionary/english">English to Xitsonga</a>. 
                        <br/><br/>
                        You can request word translation in XML or JSON formats. Try an example above.</p>
                </div>
                <div class="col-sm-6">
                    <h3>Disclaimer</h3>
                    <p>
                        The information on the website is for general use only. Every effort is made to keep the website up and running smoothly. <br/><br/>
                        However, <a href ="about/">Xitsonga.org</a> takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.
                    </p>
                </div>
            </div> 
        </div>
		
    </div>
    <?php
        require_once './assets/html/footer.php';
    ?>
</body>
</html>