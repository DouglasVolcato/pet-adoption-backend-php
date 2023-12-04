<?php

function makeCreateUserControllerFactory(): ControllerInterface
{
  $userRepository = new UserMySQLRepository();
  $idGenerator = new UuidAdapter();
  $passwordHasher = new BcryptAdapter(10);
  $createUserService = new CreateUserUseCase(
    $userRepository,
    $userRepository,
    $idGenerator,
    $passwordHasher
  );
  $controller = new CreateUserController($createUserService);
  return makeDbTransactionControllerDecoratorFactory($controller);
}
