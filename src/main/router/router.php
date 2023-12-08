<?php

namespace PetAdoption\main\router;

use PetAdoption\main\protocols\routes\RouteEnumType;

use function PetAdoption\main\factories\controllers\login\makeLoginControllerFactory;
use function PetAdoption\main\factories\controllers\pet\makeChangePetStatusControllerFactory;
use function PetAdoption\main\factories\controllers\pet\makeIndexPetsControllerFactory;
use function PetAdoption\main\factories\controllers\pet\makeSearchPetsControllerFactory;
use function PetAdoption\main\factories\controllers\user\makeCreateUserControllerFactory;
use function PetAdoption\main\factories\middlewares\auth\makeUserAuthMiddleware;

class Router
{
    private array $routes = [];

    public function setRoutes(array $routes)
    {
        $this->routes = [
            (object)[
                'url' => "/login",
                'type' => RouteEnumType::POST,
                'controller' => function () {
                    return makeLoginControllerFactory();
                },
            ],    (object)[
                'url' => "/pet",
                'type' => RouteEnumType::POST,
                'controller' => function () {
                    return makeIndexPetsControllerFactory();
                },
                'middleware' => function () {
                    return makeUserAuthMiddleware();
                },
            ],
            (object)[
                'url' => "/pet",
                'type' => RouteEnumType::PUT,
                'controller' => function () {
                    return makeChangePetStatusControllerFactory();
                },
                'middleware' => function () {
                    return makeUserAuthMiddleware();
                },
            ],
            (object)[
                'url' => "/pet",
                'type' => RouteEnumType::GET,
                'controller' => function () {
                    return makeSearchPetsControllerFactory();
                },
            ], (object)[
                'url' => "/user",
                'type' => RouteEnumType::POST,
                'controller' => function () {
                    return makeCreateUserControllerFactory();
                },
            ]

        ];
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
