<?php

namespace PetAdoption\main\protocols\routes;

use PetAdoption\main\protocols\middlewares\MiddlewareInterface;

class RouteDtoType
{
    public RouteEnumType $type;
    public string $url;
  /** @var callable(): ControllerInterface */
    public \Closure $controller;
  /** @var callable(): MiddlewareInterface */
    public ?\Closure $middleware;
};
