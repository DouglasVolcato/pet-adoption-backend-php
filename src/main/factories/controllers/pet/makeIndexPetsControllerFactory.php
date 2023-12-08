<?php

namespace PetAdoption\main\factories\controllers\pet;

use PetAdoption\apis\composites\PetSearchGatewayComposite;
use PetAdoption\apis\gateways\CatsApiGateway;
use PetAdoption\apis\gateways\DogsApiGateway;
use PetAdoption\domain\usecases\IndexPetsUseCase\IndexPetsUseCase;
use PetAdoption\infra\adapters\GuzzleHttpAdapter;
use PetAdoption\infra\adapters\UuidAdapter;
use PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\presentation\controllers\IndexPetsController\IndexPetsController;

use function PetAdoption\main\factories\decorators\makeDbTransactionControllerDecoratorFactory;

function makeIndexPetsControllerFactory(): ControllerInterface
{
    $petsRepository = new PetMySQLRepository();
    $clientGetRequestSender = new GuzzleHttpAdapter();
    $catsApiGateway = new CatsApiGateway($clientGetRequestSender);
    $dogsApiGateway = new DogsApiGateway($clientGetRequestSender);
    $petSearcher = new PetSearchGatewayComposite([
        $catsApiGateway,
        $dogsApiGateway,
    ]);
    $idGenerator = new UuidAdapter();
    $indexPetsService = new IndexPetsUseCase(
        $petsRepository,
        $petSearcher,
        $petsRepository,
        $idGenerator
    );
    $controller = new IndexPetsController($indexPetsService);
    return makeDbTransactionControllerDecoratorFactory($controller);
}
