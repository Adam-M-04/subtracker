<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch(string $uri, string $method): void
    {
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $controllerName = $route['controller'];
            $action = $route['action'];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        $this->handleNotFound();
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        $errorController = new \Controllers\ErrorController();
        $errorController->notFound();
    }
}