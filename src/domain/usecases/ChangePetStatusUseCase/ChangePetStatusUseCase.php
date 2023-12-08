<?php
namespace PetAdoption\domain\usecases\ChangePetStatusUseCase;

use Error;
use PetAdoption\domain\protocols\entities\PetEntityType;
use PetAdoption\domain\protocols\repositories\pet\UpdatePetStatusRepositoryIntereface;
use PetAdoption\presentation\helpers\errors\InvalidFieldError;

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
