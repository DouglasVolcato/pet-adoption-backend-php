<?php

require_once('../../vendor/autoload.php');

use PetAdoption\infra\databases\mysql\connection\MySQLConnectorSingleton;
use PetAdoption\main\router\Router;

$database = MySQLConnectorSingleton::getInstance();
$database->connect();

$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router = new Router();
$router->handleRequest($method, $url);
