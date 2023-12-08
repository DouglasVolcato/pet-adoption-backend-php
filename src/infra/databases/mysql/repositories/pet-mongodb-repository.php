<?php

namespace PetAdoption\infra\databases\mysql\repositories;

use PDO;
use PetAdoption\domain\protocols\entities\PetEntityType;
use PetAdoption\domain\protocols\entities\PetStatusEnum;
use PetAdoption\domain\protocols\repositories\pet\CreatePetsRepositoryIntereface;
use PetAdoption\domain\protocols\repositories\pet\DeleteAllPetsRepositoryIntereface;
use PetAdoption\domain\protocols\repositories\pet\GetPetsRepositoryInterface;
use PetAdoption\domain\protocols\repositories\pet\UpdatePetStatusRepositoryIntereface;
use PetAdoption\domain\usecases\SearchPetsUseCaseInput;
use PetAdoption\infra\databases\mysql\connection\MySQLConnectorSingleton;

class PetMySQLRepository implements
    CreatePetsRepositoryIntereface,
    UpdatePetStatusRepositoryIntereface,
    DeleteAllPetsRepositoryIntereface,
    GetPetsRepositoryInterface
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = (MySQLConnectorSingleton::getInstance())->getPdo();
    }

    public function createPets(array $pets): void
    {
        $sql = "INSERT INTO pets (name, description, category, status, createdAt) VALUES (:name, :description, :category, :status, :createdAt)";

        $stmt = $this->pdo->prepare($sql);

        foreach ($pets as $pet) {
            $stmt->execute([
                'name' => $pet->name,
                'description' => $pet->description,
                'category' => $pet->category,
                'status' => $pet->status,
                'createdAt' => $pet->createdAt,
            ]);
        }
    }

    public function updateStatus(string $petId, PetStatusEnum $newStatus): ?PetEntityType
    {
        $sql = "UPDATE pets SET status = :newStatus WHERE id = :petId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['newStatus' => $newStatus, 'petId' => $petId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        $updatedPet = $this->getPetById($petId);
        return $updatedPet;
    }

    public function deleteAllPets(): void
    {
        $sql = "DELETE FROM pets";
        $this->pdo->exec($sql);
    }

    public function getPets(SearchPetsUseCaseInput $searchParams): array
    {
        $query = $this->buildPetSearchParams($searchParams);

        $sql = "SELECT * FROM pets WHERE " . implode(" AND ", $query['conditions']);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($query['params']);
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'map'], $pets);
    }

    private function buildPetSearchParams(SearchPetsUseCaseInput $searchParams): array
    {
        $query = ['conditions' => [], 'params' => []];
        if ($searchParams->term) {
            $query['conditions'][] = "name LIKE :term OR description LIKE :term";
            $query['params']['term'] = "%" . $searchParams->term . "%";
        }
        if ($searchParams->category) {
            $query['conditions'][] = "category = :category";
            $query['params']['category'] = $searchParams->category;
        }
        if ($searchParams->status) {
            $query['conditions'][] = "status = :status";
            $query['params']['status'] = $searchParams->status;
        }
        if ($searchParams->createdAt) {
            $query['conditions'][] = "createdAt = :createdAt";
            $query['params']['createdAt'] = $searchParams->createdAt;
        }
        return $query;
    }

    private function getPetById(string $petId): ?PetEntityType
    {
        $sql = "SELECT * FROM pets WHERE id = :petId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['petId' => $petId]);
        $pet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $pet ? $pet : null;
    }
}
