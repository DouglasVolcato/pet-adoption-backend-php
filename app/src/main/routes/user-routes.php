<?php

/** @return RouteDtoType[] */
function UserRoutes()
{
  return [
    (object)[
      'url' => "/user",
      'type' => RouteEnumType::POST,
      'controller' => function () {
        return makeCreateUserControllerFactory();
      },
    ],
  ];
}
