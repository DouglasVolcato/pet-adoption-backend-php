<?php
namespace PetAdoption\domain\usecases\SearchPetsUseCase;

use PetAdoption\domain\protocols\enums\PetCategoryEnum;
use PetAdoption\domain\protocols\enums\PetStatusEnum;

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