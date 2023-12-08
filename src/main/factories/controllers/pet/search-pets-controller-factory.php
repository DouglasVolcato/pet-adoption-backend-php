<?php

namespace PetAdoption\main\factories\controllers\pet;

use PetAdoption\domain\usecases\SearchPetsUseCase;
use PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\presentation\controllers\SearchPetsController;

use function PetAdoption\main\factories\decorators\makeDbTransactionControllerDecoratorFactory;

function makeSearchPetsControllerFactory(): ControllerInterface
{
    $getPetsRepository = new PetMySQLRepository();
    $searchPetsService = new SearchPetsUseCase($getPetsRepository);
    $controller = new SearchPetsController($searchPetsService);
    return makeDbTransactionControllerDecoratorFactory($controller);
}
