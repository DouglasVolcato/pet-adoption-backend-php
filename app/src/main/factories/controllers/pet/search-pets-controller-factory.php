<?php

function makeSearchPetsControllerFactory(): ControllerInterface
{
  $getPetsRepository = new PetMySQLRepository();
  $searchPetsService = new SearchPetsUseCase($getPetsRepository);
  $controller = new SearchPetsController($searchPetsService);
  return makeDbTransactionControllerDecoratorFactory($controller);
}
