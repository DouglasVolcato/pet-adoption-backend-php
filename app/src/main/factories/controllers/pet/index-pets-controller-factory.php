<?php

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
