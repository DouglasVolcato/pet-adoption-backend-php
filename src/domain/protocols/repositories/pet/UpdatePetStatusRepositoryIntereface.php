<?php

namespace PetAdoption\domain\protocols\repositories\pet;

use PetAdoption\domain\protocols\entities\PetEntityType;
use PetAdoption\domain\protocols\enums\PetStatusEnum;

interface UpdatePetStatusRepositoryIntereface
{
    public function updateStatus(
        string $petId,
        string $newStatus,
    ): PetEntityType | null;
}
