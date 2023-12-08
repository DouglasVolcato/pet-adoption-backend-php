<?php

namespace PetAdoption\domain\protocols\repositories\pet;

use PetAdoption\domain\protocols\entities\PetEntityType;
use PetAdoption\domain\protocols\entities\PetStatusEnum;

interface UpdatePetStatusRepositoryIntereface
{
    public function updateStatus(
        string $petId,
        PetStatusEnum $newStatus,
    ): PetEntityType | null;
}
