<?php
namespace PetAdoption\domain\usecases\LoginUseCase;

use Error;
use PetAdoption\domain\protocols\repositories\user\GetUserByEmailRepositoryInterface;
use PetAdoption\domain\protocols\utils\PasswordHashCheckerInterface;
use PetAdoption\domain\protocols\utils\TokenGeneratorInterface;
use PetAdoption\presentation\helpers\errors\InvalidFieldError;

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

    public function execute(LoginUseCaseInput $input): LoginUseCaseOutput | Error
    {
        $foundUser =  $this->getUserByEmailRepository->getByEmail(
            $input->email
        );
        if (!$foundUser) {
            return new InvalidFieldError("email");
        }
        $passwordValid = $this->passwordHashChecker->validate(
            $input->password,
            $foundUser->password
        );
        if (!$passwordValid) {
            return new InvalidFieldError("password");
        }
        $token = $this->tokenGenerator->generateToken(
            (object)['id' => $foundUser->id],
            getenv()['SECRET']
        );
        return (object)['token' => $token, 'user' => $foundUser];
    }
}
