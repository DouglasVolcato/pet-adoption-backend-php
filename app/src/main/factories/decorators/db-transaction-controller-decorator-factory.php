<?php

function makeDbTransactionControllerDecoratorFactory(
  ControllerInterface $controller
): DbTransactionControllerDecorator {
  $databaseConnector = MySQLConnectorSingleton::getInstance();
  return new DbTransactionControllerDecorator($controller, $databaseConnector);
}
