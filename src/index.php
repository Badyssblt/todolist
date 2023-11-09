<?php
use Controllers\Friend;
use Controllers\Login;
use Controllers\ProjectPage;
use Controllers\Register;
use Controllers\homePage;
use Controllers\Router\Router;
use Controllers\Post;
use Controllers\Projects;

require("../vendor/autoload.php");

// Instance du router
$router = new Router();

// Récupère l'URL 
$path = $_SERVER["REQUEST_URI"];

// Ajoute les routes au Router
// FRONTEND
$router->addRoutes('/home', [new homePage(), 'home']);
$router->addRoutes('/register', [new Register(), 'index']);
$router->addRoutes('/login', [new Login(), "index"]);
$router->addRoutes('/project', [new Projects(), "index"]);
$router->addRoutes('/', [new homePage(), 'index']);
$router->addRoutes('/project/{id}', [new ProjectPage(), "index"]);


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
$router->addRoutes('/logout', [new Login(), "logout"]);
$router->addRoutes('/searchFriend', [new Friend(), 'searchFriends']);
$router->addRoutes('/getFriends', [new Friend(), 'getFriend']);
$router->addRoutes('/addFriend', [new Friend(), 'addFriend']);
$router->addRoutes('/getFriendsWaiting', [new Friend(), 'getFriendWaiting']);
$router->addRoutes('/acceptFriend', [new Friend(), 'acceptFriend']);
$router->addRoutes('/getProject', [new Projects(), "getProject"]);
$router->addRoutes('/homeProject', [new Projects(), "getTodoProject"]);
$router->addRoutes('/postEventProject', [new Projects(), "postEventProject"]);
$router->addRoutes('/checkEventProject', [new Projects(), "checkEvent"]);
$router->addRoutes('/uncheckEventProject', [new Projects(), "uncheckEvent"]);
$router->addRoutes('/createProject', [new Projects(), "createProject"]);
$router->addRoutes('/getUserInProject', [new Projects(), "getUserInProject"]);
$router->addRoutes('/getProjectByID', [new Projects(), "getProjectNameById"]);
$router->addRoutes('/addUserInProject', [new Projects(), 'addUserInProject']);
$router->addRoutes('/getProjectWaitingByUser', [new Projects(), 'getProjectWaitingByUser']);
$router->addRoutes('/updateProjectWaiting', [new Projects(), "updateInvit"]);


// Gère l'URL avec $path et applique la méthode handleRequest
$router->handleRequest($path);
