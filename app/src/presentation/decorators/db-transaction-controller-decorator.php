<?php

class DbTransactionControllerDecorator implements ControllerInterface
{
  private ControllerInterface $controller;
  private DatabaseConnectorInterface $databaseConnector;

  public function __construct(
    ControllerInterface $controller,
    DatabaseConnectorInterface $databaseConnector
  ) {
    $this->controller = $controller;
    $this->databaseConnector = $databaseConnector;
  }

  public function execute(object $request): ControllerOutputType
  {
    $this->databaseConnector->startTransaction();
    $output = $this->controller->execute($request);
    if ($output->statusCode === 500) {
      $this->databaseConnector->rollbackTransaction();
    } else {
      $this->databaseConnector->commitTransaction();
    }
    return $output;
  }
}
