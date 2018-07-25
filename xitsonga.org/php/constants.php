<?php

    /*Status message codes*/
    define("OPERATION_SUCCESS", 999);
    define("OPERATION_WARNING", 998);
    define("OPERATION_FAILED", -999);
    
    
    /* Error Messages*/
    define("BACKEND_PHP_ERROR_SESSION", "We were unable to start your session at this time");
    define("BACKEND_PHP_ERROR_USER_REG", "We were unable to register you with the system at this time");
    
    /*Info Messages*/
    define("BACKEND_PHP_INFO_VERIFY_EMAIL", "Please verify email to continue");
    define("BACKEND_PHP_INFO_SIGN_OUT", "Please wait...");
    
    /*Database fields*/
    /*
    define("DATABASE_HOST", "localhost");
    define("DATABASE_NAME", "maltopbr_db");
    define("DATABASE_USER", "maltopbr_sneidon");
    define("DATABASE_PASSWORD", "FsGVn1zJK(%W");
    */
    if($_SERVER['HTTP_HOST'] == "192.168.0.100" or $_SERVER['HTTP_HOST'] == "localhost"){
       
        define("DATABASE_HOST", "localhost");
        define("DATABASE_NAME", "xitsorkh_db");
        define("DATABASE_USER", "root");
        define("DATABASE_PASSWORD", "");
    }else{
    
        define("DATABASE_HOST", "localhost");
        define("DATABASE_NAME", "xitsorkh_db");
        define("DATABASE_USER", "root");
        define("DATABASE_PASSWORD", "Snv@big-Pass2");
        
    }
    
    
    /*tables*/
    define("TABLE_USERS", "users");
    define("TABLE_ACTIVATIONS", "activations");
    define("TABLE_ITEM_TYPE", "item_type");
    define("TABLE_ENTITY", "entity");
    define("TABLE_ENTITY_DETAILS", "entity_details");
    define("TABLE_PASSWORDS", "passwords");
     
    define("MY_DEFAULT_MCRYPT_IV_SIZE", 24);
?>