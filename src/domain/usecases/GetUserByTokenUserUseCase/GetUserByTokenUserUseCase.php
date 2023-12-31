<?php
namespace PetAdoption\domain\usecases\GetUserByTokenUserUseCase;

use Error;
use PetAdoption\domain\protocols\entities\UserEntityType;
use PetAdoption\domain\protocols\repositories\user\GetUserByIdRepositoryInterface;
use PetAdoption\domain\protocols\utils\TokenDecrypterInterface;
use PetAdoption\presentation\helpers\errors\InvalidFieldError;

class GetUserByTokenUserUseCase
{
    private TokenDecrypterInterface $tokenDecrypter;
    private GetUserByIdRepositoryInterface $getUserByIdRepository;

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
            getenv()['SECRET']
        );
        if (!$decryptedToken) {
            return new InvalidFieldError("token");
        }
        $userId = $decryptedToken->id;
        $foundUser = $this->getUserByIdRepository->getById($userId);
        if (!$foundUser) {
            return new InvalidFieldError("token");
        }
        return $foundUser;
    }
}
