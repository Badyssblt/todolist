<?php

namespace Controllers\Router;

class Router
{
    private $routes = [];

    public function addRoutes(string $path, callable $callback)
    {
        $path = preg_replace_callback('#\{([^/]+)\}#', function ($matches) {
            return '([^/]+)';
        }, $path);

        $this->routes["#^$path$#u"] = $callback;

        return $this->routes;
    }

    public function handleRequest($path)
    {
        foreach ($this->routes as $route => $callback) {
            $matches = [];
            if (preg_match($route, $path, $matches)) {
                array_shift($matches);
                call_user_func_array($callback, $matches);
                return;
            }
        }
        echo "Erreur 404";
    }
}
