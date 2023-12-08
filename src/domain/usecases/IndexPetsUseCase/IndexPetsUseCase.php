<?php
namespace PetAdoption\domain\usecases\IndexPetsUseCase;

use DateTime;
use PetAdoption\domain\protocols\repositories\pet\CreatePetsRepositoryIntereface;
use PetAdoption\domain\protocols\repositories\pet\DeleteAllPetsRepositoryIntereface;
use PetAdoption\domain\protocols\utils\IdGeneratorInterface;
use PetAdoption\domain\protocols\utils\PetSearcherInterface;

class IndexPetsUseCase
{
    private DeleteAllPetsRepositoryIntereface $deleteAllPetsRepository;
    private PetSearcherInterface $petSearcher;
    private CreatePetsRepositoryIntereface $createPetsRepository;
    private IdGeneratorInterface $idGenerator;

    public function __construct(
        DeleteAllPetsRepositoryIntereface $deleteAllPetsRepository,
        PetSearcherInterface $petSearcher,
        CreatePetsRepositoryIntereface $createPetsRepository,
        IdGeneratorInterface $idGenerator
    ) {
        $this->deleteAllPetsRepository = $deleteAllPetsRepository;
        $this->petSearcher = $petSearcher;
        $this->createPetsRepository = $createPetsRepository;
        $this->idGenerator = $idGenerator;
    }

    public function execute(): void
    {
        $this->deleteAllPetsRepository->deleteAllPets();
        $this->idGenerator->generateId();
        foreach ($this->petSearcher->request() as $pets) {
            $petsWithIds = array_map(function ($pet) {
                $petWithId = $pet;
                $petWithId->id = $this->idGenerator->generateId();
                $petWithId->createdAt = (new DateTime('now'))->format('Y-m-d');
                $petWithId->description = isset($petWithId->description) ? $petWithId->description : "";
                return $petWithId;
            }, $pets);

            $this->createPetsRepository->createPets($petsWithIds);
        }
    }
}
