<?php

namespace PetAdoption\domain\protocols\repositories\pet;

use PetAdoption\domain\usecases\SearchPetsUseCase\SearchPetsUseCaseInput;

interface GetPetsRepositoryInterface
{
    public function getPets(SearchPetsUseCaseInput $searchParams): array;
}
