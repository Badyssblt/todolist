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


// BACKEND
$router->addRoutes('/postEvent', [new Post(), 'index']);
$router->addRoutes('/checkEvent', [new Post(), "check"]);
$router->addRoutes('/uncheckEvent', [new Post(), "uncheck"]);
$router->addRoutes('/addCategory', [new Post(), "createCategory"]);
$router->addRoutes('/getCategory', [new Post(), "getCategory"]);
$router->addRoutes('/updateTodoOrder', [new Post(), "changeOrder"]);
$router->addRoutes('/editTodo', [new Post(), "editTodo"]);
$router->addRoutes("/deleteTodo", [new Post(), 'deleteTodo']);
$router->addRoutes("/getTodo", [new Post(), "getTodo"]);


$router->handleRequest($path);