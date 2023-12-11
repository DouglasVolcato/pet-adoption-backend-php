<?php
namespace PetAdoption\domain\usecases\SearchPetsUseCase;

class SearchPetsUseCaseInput
{
    public int $limit;
    public int $offset;
    public ?string $term;
    public ?string $name;
    public ?string $description;
    public ?string $category;
    public ?string $status;
    public ?string $createdAt;
}