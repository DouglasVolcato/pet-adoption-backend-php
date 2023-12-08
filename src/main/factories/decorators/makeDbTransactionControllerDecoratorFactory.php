<?php

namespace PetAdoption\main\factories\decorators;

use PetAdoption\infra\databases\mysql\connection\MySQLConnectorSingleton;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\presentation\decorators\DbTransactionControllerDecorator;

function makeDbTransactionControllerDecoratorFactory(
    ControllerInterface $controller
): DbTransactionControllerDecorator {
    $databaseConnector = MySQLConnectorSingleton::getInstance();
    return new DbTransactionControllerDecorator($controller, $databaseConnector);
}
