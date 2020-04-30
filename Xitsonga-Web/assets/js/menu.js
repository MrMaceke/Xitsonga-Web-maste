jQuery(document).ready(function($){
    var path = "";
    path = window.location.pathname;
    
    
    path = path.replace("/","");
    path = path.split("&")[0];
    if(path.indexOf("manage") > -1){
        parameter = path + window.location.search + window.location.hash;

        if(parameter.indexOf("exercise") > -1){
            $(".sub_links").not(':has(a[href="' + path +'"])').hide();
        }else{
            path = path + window.location.search + window.location.hash;

            $(".sub_links").not(':has(a[href="' + path +'"])').hide();
        }
    }else if(path.indexOf("tenses") > -1){
         path = path + window.location.search + window.location.hash;
         
       
    }else{
        $(".sub_links").not(':has(a[href="' + path +'"])').hide(); 
    }
    split_path = path.split("&page=");

    $('.sub_links a[href="' + split_path[0]  +'"]').css("color","#F34E4E");
    $(".sub_links").not(':has(a[href="' + split_path[0] +'"])').hide();

    $(".sub_heading h4").css("width","80%");
    $(".sub_heading h4").css("float","left");
    $("<img src ='assets/images/Arrow_Down.svg.png' style ='float:left;width:10px;margin-top:10px;margin-right:10px'/>").prependTo(".sub_heading div");
    
     $(".sub_heading").click(function(){
         $(this).next(".sub_links").toggle('slow');
     });
    
});
