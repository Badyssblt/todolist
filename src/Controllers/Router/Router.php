<?php

namespace Controllers\Router;

class Router
{
    private $routes = [];
    public function addRoutes(string $path, callable $callback)
    {
        $this->routes[$path] = $callback;
        return $this->routes;
    }

    public function handleRequest($path)
    {
        foreach ($this->routes as $route => $callback) {
            $matches = [];
            if (preg_match("#^$route$#", $path, $matches)) {
                array_shift($matches); // Retire la première correspondance (le chemin complet)
                call_user_func_array($callback, $matches);
                return;
            }
        }
        echo "Erreur 404";
    }
}