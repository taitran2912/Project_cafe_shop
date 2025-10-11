<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/config.php';
require_once './app/core/Router.php';

$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];

Router::route($url);
