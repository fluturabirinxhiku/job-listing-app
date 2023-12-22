<?php
require __DIR__ . "/../vendor/autoload.php";

use Framework\Session;
use Framework\Router;

Session::start();

require '../helpers.php';

$router = new Router();

$routes = require basePath('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->route($uri);