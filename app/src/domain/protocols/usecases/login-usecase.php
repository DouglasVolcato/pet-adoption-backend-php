<?php

class LoginUseCaseInput
{
  public string $email;
  public string $password;
}

class LoginUseCaseOutput
{
  public UserEntityType $user;
  public string $token;
}

class LoginUseCase
{
  private GetUserByEmailRepositoryInterface $getUserByEmailRepository;
  private PasswordHashCheckerInterface $passwordHashChecker;
  private TokenGeneratorInterface $tokenGenerator;

  public function __construct(
    GetUserByEmailRepositoryInterface $getUserByEmailRepository,
    PasswordHashCheckerInterface $passwordHashChecker,
    TokenGeneratorInterface $tokenGenerator
  ) {
    $this->getUserByEmailRepository = $getUserByEmailRepository;
    $this->passwordHashChecker = $passwordHashChecker;
    $this->tokenGenerator = $tokenGenerator;
  }

  public function execute(LoginUseCaseinput $input): LoginUseCaseOutput | Error
  {
    $foundUser =  $this->getUserByEmailRepository->getByEmail(
      $input->email
    );
    if (!$foundUser) return new InvalidFieldError("email");
    $passwordValid = $this->passwordHashChecker->validate(
      $input->password,
      $foundUser->password
    );
    if (!$passwordValid) return new InvalidFieldError("password");
    $token = $this->tokenGenerator->generateToken(
      (object)['id' => $foundUser->id],
      $_ENV['SECRET']
    );
    return (object)['token' => $token, 'user' => $foundUser];
  }
}
