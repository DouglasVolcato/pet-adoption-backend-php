<?php

class GetUserByTokenUserUseCaseInput
{
  public ?string $token;
}

class GetUserByTokenUserUseCase
{
  private  TokenDecrypterInterface $tokenDecrypter;
  private  GetUserByIdRepositoryInterface $getUserByIdRepository;

  public function __construct(
    TokenDecrypterInterface $tokenDecrypter,
    GetUserByIdRepositoryInterface $getUserByIdRepository
  ) {
    $this->$tokenDecrypter = $tokenDecrypter;
    $this->$getUserByIdRepository = $getUserByIdRepository;
  }

  public function execute(GetUserByTokenUserUseCaseInput $input): UserEntityType | Error
  {
    $decryptedToken = $this->tokenDecrypter->decryptToken(
      $input->token,
      $_ENV['SECRET']
    );
    if (!$decryptedToken) return new InvalidFieldError("token");
    $userId = $decryptedToken->id;
    $foundUser = $this->getUserByIdRepository->getById($userId);
    if (!$foundUser) return new InvalidFieldError("token");
    return $foundUser;
  }
}
