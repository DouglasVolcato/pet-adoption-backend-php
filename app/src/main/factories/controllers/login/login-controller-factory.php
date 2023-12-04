<?php

 function makeLoginControllerFactory(): ControllerInterface {
  $getUserByEmailRepository = new UserMySQLRepository();
  $passwordHashChecker = new BcryptAdapter(10);
  $tokenGenerator = new JwtAdapter();
  $loginService = new LoginUseCase(
    $getUserByEmailRepository,
    $passwordHashChecker,
    $tokenGenerator
  );
  $controller = new LoginController($loginService);
  return makeDbTransactionControllerDecoratorFactory($controller);
}
