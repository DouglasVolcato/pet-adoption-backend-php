<?php

namespace PetAdoption\infra\databases\mysql\repositories;

use PetAdoption\domain\protocols\repositories\pet\UpdatePetStatusRepositoryIntereface;
use PetAdoption\domain\protocols\repositories\pet\DeleteAllPetsRepositoryIntereface;
use PetAdoption\domain\protocols\repositories\pet\CreatePetsRepositoryIntereface;
use PetAdoption\domain\protocols\repositories\pet\GetPetsRepositoryInterface;
use PetAdoption\domain\usecases\SearchPetsUseCase\SearchPetsUseCaseInput;
use PetAdoption\infra\databases\mysql\connection\MySQLConnectorSingleton;
use PetAdoption\domain\protocols\entities\PetEntityType;
use PDO;

class PetMySQLRepository implements
    CreatePetsRepositoryIntereface,
    UpdatePetStatusRepositoryIntereface,
    DeleteAllPetsRepositoryIntereface,
    GetPetsRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO|null $pdo = null)
    {
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
            $this->pdo = (MySQLConnectorSingleton::getInstance())->getPdo();
        }
    }

    /**
     * @param PetEntityType[] $pets
     */
    public function createPets(array $pets): void
    {
        $sql = "INSERT INTO pets (id, name, description, image, category, status, createdAt) VALUES (:id, :name, :description, :image, :category, :status, :createdAt)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($pets as $pet) {
            $stmt->execute([
                'id' => $pet->id,
                'name' => $pet->name,
                'description' => $pet->description,
                'image', $pet->image,
                'category' => $pet->category,
                'status' => $pet->status,
                'createdAt' => $pet->createdAt,
            ]);
        }
    }

    public function deleteAllPets(): void
    {
        $sql = "DELETE FROM pets";
        $this->pdo->exec($sql);
    }

    public function getPets(SearchPetsUseCaseInput $searchParams): array
    {
        $query = $this->buildPetSearchParams($searchParams);
        $sql = "SELECT * FROM pets WHERE 1 = 1 " . implode(" ", $query['conditions']);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($query['params']);
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $pets;
    }

    public function updateStatus(string $petId, string $newStatus): ?PetEntityType
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

    public function getPetById(string $petId): ?PetEntityType
    {
        $sql = "SELECT * FROM pets WHERE id = :petId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['petId' => $petId]);
        $pet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $pet ? $pet : null;
    }

    public function buildPetSearchParams(SearchPetsUseCaseInput $searchParams): array
    {
        $query = ['conditions' => [], 'params' => []];
        if (
            property_exists($searchParams, 'term')
            && !empty($searchParams->term)
        ) {
            $query['conditions'][] = "and name LIKE :term% OR description LIKE :term%";
            $query['params']['term'] = "%" . $searchParams->term . "%";
        }
        if (
            property_exists($searchParams, 'category')
            && !empty($searchParams->category)
        ) {
            $query['conditions'][] = "and category = :category";
            $query['params']['category'] = $searchParams->category;
        }
        if (
            property_exists($searchParams, 'status')
            && !empty($searchParams->status)
        ) {
            $query['conditions'][] = "and status = :status";
            $query['params']['status'] = $searchParams->status;
        }
        if (
            property_exists($searchParams, 'createdAt')
            && !empty($searchParams->createdAt)
        ) {
            $query['conditions'][] = "and createdAt = :createdAt";
            $query['params']['createdAt'] = $searchParams->createdAt;
        }
        if (
            property_exists($searchParams, 'limit')
            && !empty($searchParams->limit)
        ) {
            $query['conditions'][] = "and LIMIT :limit";
            $query['params']['limit'] = $searchParams->limit;
        }
        if (
            property_exists($searchParams, 'offset')
            && !empty($searchParams->offset)
        ) {
            $query['conditions'][] = "and OFFSET = :offset";
            $query['params']['offset'] = $searchParams->offset;
        }
        return $query;
    }
}
