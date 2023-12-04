<?php

/** @return RouteDtoType[] */
function PetRoutes()
{
  return [
    (object)[
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
    ],
  ];
};
