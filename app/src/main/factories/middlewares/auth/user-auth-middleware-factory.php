<?php

function makeUserAuthMiddleware(): UserAuthMiddleware
{
  $tokenDecrypter = new JwtAdapter();
  $getUserByIdRepository = new UserMySQLRepository();
  $getUserByTokenService = new GetUserByTokenUserUseCase(
    $tokenDecrypter,
    $getUserByIdRepository
  );
  return new UserAuthMiddleware($getUserByTokenService);
}
