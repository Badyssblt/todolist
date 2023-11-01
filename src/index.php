<?php
use Controllers\Login;
use Controllers\Register;
use Controllers\homePage;
use Controllers\Router\Router;
use Controllers\Post;
use Controllers\Category;

require("../vendor/autoload.php");


$router = new Router();

$path = $_SERVER["REQUEST_URI"];

// FRONTEND

$router->addRoutes('/', [new homePage(), 'index']);
$router->addRoutes('/register', [new Register(), 'index']);
$router->addRoutes('/login', [new Login(), "index"]);
$router->addRoutes('/category', [new Category(), 'index']);


// BACKEND
$router->addRoutes('/postEvent', [new Post(), 'index']);
$router->addRoutes('/checkEvent', [new Post(), "check"]);
$router->addRoutes('/uncheckEvent', [new Post(), "uncheck"]);
$router->addRoutes('/addCategory', [new Post(), "createCategory"]);
$router->addRoutes('/getCategory', [new Post(), "getCategory"]);
$router->addRoutes('/updateTodoOrder', [new Post(), "changeOrder"]);

$router->handleRequest($path);