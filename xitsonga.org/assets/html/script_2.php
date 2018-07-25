<?php/*  * To change this license header, choose License Headers in Project Properties. * To change this template file, choose Tools | Templates * and open the template in the editor. */?>
    <!-- JavaScript libs are placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jqueryui.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/headroom.min.js"></script>
    <script src="assets/js/jQuery.headroom.min.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/scroll.js"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/modernizr.js"></script>
    <!-- Modernizr -->
    <script src="assets/js/jquery.smartbanner.js"></script>
    <script src="assets/js/rating.js"></script>
    <script src="assets/js/jquery.ui.chatbox.js"></script>
    <script type="text/javascript" src="assets/js/jquery.noty.packaged.js"></script>
    <script type="text/javascript" src="assets/js/ion.sound.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
	 	var audio = new Audio("");
	        $(".playNumber").click(function (e){
	            var audio_urls = $(this).attr("id").split(" ");
	        
	            if(audio_urls.length == 1) {
		            var url = "audio/" + $(this).attr("id") + ".m4a";
		            audio.pause();
		            audio.currentTime = 0;
		            audio = new Audio(url);
		            audio.play();
		    } else if(audio_urls.length == 2) {
		            var url = "audio/" + audio_urls[0] + ".m4a";
		            /*
		            audio.addEventListener('ended', function(){
				 var url = "audio/" + audio_urls[1] + ".m4a";
				 audio.pause();
			            audio.currentTime = 0;
			            audio = new Audio(url);
			            audio.play();
			    });*/
		            audio.pause();
		            audio.currentTime = 0;
		            audio = new Audio(url);
		            audio.play();
		            
		           audio.onended = function() {
				    var url = "audio/" + audio_urls[1] + ".m4a";
				 audio.pause();
			            audio.currentTime = 0;
			            audio = new Audio(url);
			            audio.play();
			};
		    }
	        });
        });
    </script>
    <script type="text/javascript">
        if (true) {
            $(function() {
                $.smartbanner({
                    daysHidden: 0,
                    daysReminder: 0,
                    author: "Sneidon Dumela",
                    button: 'Download',
                    daysHidden: 15, // Duration to hide the banner after being closed (0 = always show banner)
  		    daysReminder: 90, // Duration to hide the banner after "VIEW" is clicked *separate from when the close button is clicked* (0 = always show banner)
                    title: 'Xitsonga Dictionary'
                })
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() { /*        var box = $("#chat_div")            .chatbox({                id:"chat_div",                 user:{key : "value"},                title : "Quick Help",                messageSent : function(id, user, msg) {                    $("#log").append(id + " said: " + msg + "<br/>");                    $("#chat_div").chatbox("option", "boxManager").addMsg(id, msg);                }            })            .toggleBox();        box.toggleBox();        */ });
    </script>