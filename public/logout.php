<?php
session_start();
if(isset($_SESSION["username"])){
    $_SESSION = array();    
    session_destroy();  
    $cookie_name = "User";
    $cookie_value = $_COOKIE[$cookie_name];
    setcookie($cookie_name, $cookie_value, time() - 3600, "/"); 
    header("Location: index.php");

}else header("Location: login.php");
?>