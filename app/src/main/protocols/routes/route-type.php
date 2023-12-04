<?php

class RouteEnumType
{
  const GET = "get";
  const POST = "post";
  const DELETE = "delete";
  const PUT = "put";
  const PATCH = "patch";
}

class RouteDtoType
{
  public RouteEnumType $type;
  public string $url;
  /** @var callable(): ControllerInterface */
  public callable $controller;
  /** @var callable(): MiddlewareInterface */
  public ?MiddlewareInterface $middleware;
};
