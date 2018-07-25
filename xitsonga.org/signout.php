<?php session_start();
    unset($_SESSION["USER"]); 

   header("Location: kaya");;
?>