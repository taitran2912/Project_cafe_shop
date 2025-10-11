<?php
require_once "./config/config.php";
require_once "./app/core/Router.php";
require_once "./app/core/Controller.php";
require_once "./app/core/Model.php";

$router = new Router();
$router->run();
?>