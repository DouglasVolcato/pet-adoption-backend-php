<?php

/** @return RouteDtoType[] */
function LoginRoutes()
{
  return [
    (object)[
      'url' => "/login",
      'type' => RouteEnumType::POST,
      'controller' => function () {
        return makeLoginControllerFactory();
      },
    ]
  ];
};
