<?php

namespace PetAdoption\domain\protocols\repositories\pet;

interface CreatePetsRepositoryIntereface
{
    public function createPets(array $pets): void;
}
