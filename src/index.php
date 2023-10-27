<?php
use Controllers\Login;
use Controllers\Register;
use Controllers\homePage;
use Controllers\Router\Router;
use Controllers\Post;

require("../vendor/autoload.php");


$router = new Router();

$path = $_SERVER["REQUEST_URI"];

$router->addRoutes('/', [new homePage(), 'index']);
$router->addRoutes('/register', [new Register(), 'index']);
$router->addRoutes('/login', [new Login(), "index"]);
$router->addRoutes('/postEvent', [new Post(), 'index']);
$router->handleRequest($path);