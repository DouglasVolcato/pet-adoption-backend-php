<?php

class Router
{
    private array $routes = [];

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    public function handleRequest($method, $url)
    {
        foreach ($this->routes as $route) {
            if ($route->type == $method && $route->url == $url) {
                $middlewareOutput = $route->middleware ? $route->middleware((object)$_REQUEST) : null;

                $controller = $route->controller;
                $response = $controller((object)$_REQUEST, $middlewareOutput);

                http_response_code($response->statusCode);
                echo json_encode($response->data);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}

$database = MySQLConnectorSingleton::getInstance();
$database->connect();

$router = new Router();
$router->setRoutes(array_merge(LoginRoutes(), PetRoutes(), UserRoutes()));

$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->handleRequest($method, $url);