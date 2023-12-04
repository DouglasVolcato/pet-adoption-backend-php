<?php

class CreateUserUseCaseInput
{
  public string $name;
  public string $email;
  public string $password;
}

class CreateUserUseCase
{
  private  CreateUserRepositoryInterface $createUserRepository;
  private  GetUserByEmailRepositoryInterface $getUserByEmailRepository;
  private  IdGeneratorInterface $idGenerator;
  private  PasswordHasherInterface $passwordHasher;

  public function __construct(
    CreateUserRepositoryInterface $createUserRepository,
    GetUserByEmailRepositoryInterface $getUserByEmailRepository,
    IdGeneratorInterface $idGenerator,
    PasswordHasherInterface $passwordHasher
  ) {
    $this->createUserRepository = $createUserRepository;
    $this->getUserByEmailRepository = $getUserByEmailRepository;
    $this->idGenerator = $idGenerator;
    $this->passwordHasher = $passwordHasher;
  }

  public function execute(CreateUserUseCaseInput $input): UserEntityType | Error
  {
    $foundUser =  $this->getUserByEmailRepository->getByEmail(
      $input->email
    );
    if ($foundUser) return new InvalidFieldError("email");
    $newUser = new UserEntityType();
    $newUser->name = $input->name;
    $newUser->email = $input->email;
    $newUser->password = $input->password;
    $newUser->password = $this->passwordHasher->hash($input->password);
    $newUser->id = $this->idGenerator->generateId();
    $newUser->admin = false;
    return  $this->createUserRepository->create($newUser);
  }
}
