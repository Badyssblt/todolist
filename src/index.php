<?php

use Router\Router;
use Exceptions\RouteNotFoundException;

require '../vendor/autoload.php';

define("BASE_VIEW_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR);

$router = new Router();

$router->register('/register', function () {
    return "Register";
});

$router->register("/", ['Controllers\HomeControllers', "index"]);

try {
    echo $router->resolve($_SERVER["REQUEST_URI"]);
} catch (RouteNotFoundException $e) {
    echo $e;
}


?>