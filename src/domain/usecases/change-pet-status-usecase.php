<?php

namespace PetAdoption\domain\usecases;

use Error;
use PetAdoption\domain\protocols\entities\PetEntityType;
use PetAdoption\domain\protocols\entities\PetStatusEnum;
use PetAdoption\domain\protocols\repositories\pet\UpdatePetStatusRepositoryIntereface;
use PetAdoption\presentation\helpers\errors\InvalidFieldError;

class ChangePetStatusUseCaseInput
{
    public string $petId;
    public PetStatusEnum $newStatus;
}

class ChangePetStatusUseCase
{
    private UpdatePetStatusRepositoryIntereface $updatePetStatusRepository;

    public function __construct(
        UpdatePetStatusRepositoryIntereface $updatePetStatusRepository
    ) {
        $this->updatePetStatusRepository = $updatePetStatusRepository;
    }

    public function execute(ChangePetStatusUseCaseInput $input): PetEntityType | Error
    {
        $updatedPet =  $this->updatePetStatusRepository->updateStatus(
            $input->petId,
            $input->newStatus
        );
        if (!$updatedPet) {
            return new InvalidFieldError("id");
        }
        return $updatedPet;
    }
}
