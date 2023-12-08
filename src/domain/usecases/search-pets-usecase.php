<?php

namespace PetAdoption\domain\usecases;

use PetAdoption\domain\protocols\entities\PetCategoryEnum;
use PetAdoption\domain\protocols\entities\PetStatusEnum;
use PetAdoption\domain\protocols\repositories\pet\GetPetsRepositoryInterface;

class SearchPetsUseCaseInput
{
    public int $limit;
    public int $offset;
    public ?string $term;
    public ?string $name;
    public ?string $description;
    public ?PetCategoryEnum $category;
    public ?PetStatusEnum $status;
    public ?string $createdAt;
}

class SearchPetsUseCase
{
    private GetPetsRepositoryInterface $getPetsRepository;

    public function __construct(GetPetsRepositoryInterface $getPetsRepository)
    {
        $this->getPetsRepository = $getPetsRepository;
    }

    public function execute(SearchPetsUseCaseInput $input): array
    {
        return $this->getPetsRepository->getPets($input);
    }
}
