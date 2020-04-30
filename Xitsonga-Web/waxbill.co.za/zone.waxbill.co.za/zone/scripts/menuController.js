jQuery(document).ready(function($){
    var path = window.location.pathname.toLowerCase();
    path = path.replace("/maple/clientzone/","");
    
    split_path = path;
    
    //$('.ts-sidebar-menu a[href="' + split_path  +'"]').css("color","orange");
    $('.ts-sidebar-menu a[href="' + split_path  +'"]').parent().addClass('open');
    $('.ts-sidebar-menu a[href="' + split_path  +'"]').parent().parent().parent().addClass('open');
});

