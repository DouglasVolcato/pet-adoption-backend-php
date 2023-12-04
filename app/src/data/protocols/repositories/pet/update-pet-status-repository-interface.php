

<?php

interface UpdatePetStatusRepositoryIntereface
{
  public function updateStatus(
    string $petId,
    PetStatusEnum $newStatus,
  ): PetEntityType | null;
}
