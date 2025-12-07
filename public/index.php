<?php
ini_set('session.gc_maxlifetime', 86400); 
ini_set('session.cookie_lifetime', 86400);

session_set_cookie_params(86400);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';


$app = new App();
?>