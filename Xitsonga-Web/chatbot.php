<div id ="EBUSalesOrderDetailsDialogDiv"></div>
<script type="text/javascript">
    $(document).ready(function() {
        var iframe = $("<iframe frameborder='0' marginwidth='0' marginheight='0' allowfullscreen></iframe>"); 
        var dialog = $('<div></div>').append(iframe).appendTo('#EBUSalesOrderDetailsDialogDiv').dialog({ 
            autoOpen: false, 
            modal: false, 
            resizable: true, 
            width: 'auto', 
            height: 'auto'
        }); 
        
            $('#EBUSalesOrderDetailsDialogLink').on('click', function (e) {
                e.preventDefault(); 
                var src = 'https://nextqa.vodacom.corp/siebel/app/iframe/enu?SWECmd=InvokeMethod&SWEService=VSFA+Toolkit+for+iFrame&SWEMethod=GotoView&ViewName=Sales+Order&BCField=Order+Number&BCFieldValue=ERER3234&SWERF=1&Key=A199GfdT68Dc22&IsPortlet=1&KeepAlive=1'; 
                var title = 'EBU Sales Order Details'; 
                var width = '700'; 
                var height = '400'; 
                iframe.attr({
                    width: width, 
                    height: height, 
                    src: src
                }); 
            }); 
           dialog.dialog('option', 'title', 'EBU Sales Order Details').dialog('open'); 
        });
</script>
<a id ="EBUSalesOrderDetailsDialogLink">Sales Order Details</a>