<?php

namespace PetAdoption\domain\protocols\repositories\pet;

interface GetPetsRepositoryInterface
{
    public function getPets(object $searchParams): array;
}
