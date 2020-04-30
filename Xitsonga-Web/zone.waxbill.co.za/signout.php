<?php 
    $some_name = session_name("some_name");
    session_set_cookie_params(0, '/', '.waxbill.co.za');
    session_start();
    session_destroy();    
    $_SESSION = array();
    
    header("Location: login/");
?>