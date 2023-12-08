<?php
namespace PetAdoption\domain\usecases\ChangePetStatusUseCase;

use PetAdoption\domain\protocols\enums\PetStatusEnum;

class ChangePetStatusUseCaseInput
{
    public string $petId;
    public PetStatusEnum $newStatus;
}