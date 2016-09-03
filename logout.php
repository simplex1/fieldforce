<?php
/*error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);*/
 require_once "shared/dbmani.php";
 require_once "shared/utilities.php";  
 $db = dbmani::connect();
 $utils = new utilities();
 @session_start();
     $utils->storeLogin($db,$_SESSION['user_id'],"Logout");
     if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
     }
 @session_destroy(); 
 header("Location: index.php");
?>