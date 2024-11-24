<?php
require_once "config/routes.php";
require_once "config/pdo.php"; 

session_start();

// Initialize the router and route the request
$router = new Router();
$router->direct();
?>