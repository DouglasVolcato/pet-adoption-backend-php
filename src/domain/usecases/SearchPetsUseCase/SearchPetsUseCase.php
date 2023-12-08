<?php
namespace PetAdoption\domain\usecases\SearchPetsUseCase;

use PetAdoption\domain\protocols\repositories\pet\GetPetsRepositoryInterface;

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
