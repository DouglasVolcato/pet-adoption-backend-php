<?php

namespace PetAdoptionTest\infra\databases\mysql\repositories;

use PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository;
use PetAdoptionTest\utils\stubs\infra\database\pdo\PDOStatementStub;
use PetAdoptionTest\utils\stubs\infra\database\pdo\PDOStub;
use PetAdoptionTest\utils\FakeData;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;
use PDOStatement;
use Exception;
use Mockery;
use PDO;

class PetMySQLRepositoryTest extends TestCase
{
    private FakeData $fakeData;
    private PetMySQLRepository $sut;
    private PDO|LegacyMockInterface $pdoMock;
    private PDOStatement|LegacyMockInterface $pdoStatementMock;

    protected function setUp(): void
    {
        $this->fakeData = FakeData::getInstance();
        $this->pdoMock = Mockery::mock(PDOStub::class);
        $this->pdoStatementMock = Mockery::mock(PDOStatementStub::class);
        $this->sut = new PetMySQLRepository($this->pdoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    // *********************** create ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::createPets
     */
    public function testCreateShouldCallPrepareWithCorrectSql()
    {
        $sql = "INSERT INTO pets (id, name, description, image, category, status, createdAt) VALUES (:id, :name, :description, :image, :category, :status, :createdAt)";
        $this->pdoMock->shouldReceive('prepare')->once()->with(
            Mockery::on(
                function ($arg) use ($sql) {
                    $this->assertEquals($sql, $arg);
                    return true;
                }
            )
        )->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->andReturn(true);
        $this->sut->createPets([$this->fakeData->petData()]);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::createPets
     */
    public function testCreateShouldCallExecuteWithCorrectValues()
    {
        $petsData = [$this->fakeData->petData()];
        $this->pdoMock->shouldReceive('prepare')->once()->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->with(
                Mockery::on(function ($arg) use ($petsData) {
                    $this->assertEquals([
                        'id' => $petsData[0]->id,
                        'name' => $petsData[0]->name,
                        'description' => $petsData[0]->description,
                        'image', $petsData[0]->image,
                        'category' => $petsData[0]->category,
                        'status' => $petsData[0]->status,
                        'createdAt' => $petsData[0]->createdAt,
                    ], $arg);
                    return true;
                })
            )->andReturn(true);
        $this->sut->createPets($petsData);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::createPets
     */
    public function testCreateShouldReturnVoid()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $output = $this->sut->createPets([$this->fakeData->petData()]);
        $this->assertEmpty($output);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::createPets
     */
    public function testCreateShouldThrowIfPrepareThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->expectException(Exception::class);
        $this->sut->createPets([$this->fakeData->petData()]);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::createPets
     */
    public function testCreateShouldThrowIfExecuteThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->createPets([$this->fakeData->petData()]);
    }

    // *********************** deleteAllPets ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::deleteAllPets
     */
    public function testDeleteShouldCallExecWithCorrectSql()
    {
        $sql = "DELETE FROM pets";
        $this->pdoMock->shouldReceive('exec')->once()->with(
            Mockery::on(
                function ($arg) use ($sql) {
                    $this->assertEquals($sql, $arg);
                    return true;
                }
            )
        )->andReturn(1);
        $this->sut->deleteAllPets();
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::deleteAllPets
     */
    public function testDeleteShouldReturnVoid()
    {
        $this->pdoMock->shouldReceive('exec')->andReturn(1);
        $output = $this->sut->deleteAllPets();
        $this->assertEmpty($output);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::deleteAllPets
     */
    public function testDeleteShouldThrowIfExecThrows()
    {
        $this->pdoMock->shouldReceive('exec')->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->deleteAllPets();
    }


    // *********************** getPets ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldCallBuildPetSearchParamsWithCorrectValues()
    {
        $searchParams = $this->fakeData->petSearchParams();
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')->once()
            ->with(
                Mockery::on(
                    function ($arg) use ($searchParams) {
                        $this->assertEquals($searchParams, $arg);
                        return true;
                    }
                )
            )->andReturn(['conditions' => [], 'params' => []]);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')->once()
            ->once()->andReturn([]);
        $sutMock->getPets($searchParams);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldCallPrepareWithCorrectSql()
    {
        $searchParams = $this->fakeData->petSearchParams();
        $query = ['conditions' => [], 'params' => []];
        $query['conditions'][] = "and LIMIT :limit";
        $query['params']['limit'] = $searchParams->limit;
        $query['conditions'][] = "and OFFSET = :offset";
        $query['params']['offset'] = $searchParams->offset;
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')->once()
            ->andReturn($query);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->with(Mockery::on(function ($arg) {
                $sql = "SELECT * FROM pets WHERE 1 = 1 and LIMIT :limit and OFFSET = :offset";
                $this->assertEquals($sql, $arg);
                return true;
            }))
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')->once()
            ->once()->andReturn([]);
        $sutMock->getPets($searchParams);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldCallExecuteWithCorrectValues()
    {
        $searchParams = $this->fakeData->petSearchParams();
        $query = ['conditions' => [], 'params' => []];
        $query['conditions'][] = "and LIMIT :limit";
        $query['params']['limit'] = $searchParams->limit;
        $query['conditions'][] = "and OFFSET = :offset";
        $query['params']['offset'] = $searchParams->offset;
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')->once()
            ->andReturn($query);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->with(Mockery::on(function ($arg) use ($query) {
                $this->assertEquals($query['params'], $arg);
                return true;
            }))
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')->once()
            ->once()->andReturn([]);
        $sutMock->getPets($searchParams);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldCallFetchAllWithCorrectValues()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')->once()
            ->andReturn(['conditions' => [], 'params' => []]);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')->once()
            ->with(Mockery::on(function ($arg) {
                $this->assertEquals(PDO::FETCH_ASSOC, $arg);
                return true;
            }))
            ->once()->andReturn([]);
        $sutMock->getPets($this->fakeData->petSearchParams());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldReturnTheFoundPets()
    {
        $foundPets = [
            $this->fakeData->petData(),
            $this->fakeData->petData(),
            $this->fakeData->petData()
        ];
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')
            ->andReturn(['conditions' => [], 'params' => []]);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->andReturn($foundPets);
        $result = $sutMock->getPets($this->fakeData->petSearchParams());
        $this->assertEquals($foundPets, $result);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldThrowIfBuildPetSearchParamsThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')
            ->andThrow(new Exception());
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->andReturn([]);
        $this->expectException(Exception::class);
        $sutMock->getPets($this->fakeData->petSearchParams());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldThrowIfPrepareThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')
            ->andReturn(['conditions' => [], 'params' => []]);
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->andReturn([]);
        $this->expectException(Exception::class);
        $sutMock->getPets($this->fakeData->petSearchParams());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldThrowIfExecuteThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')
            ->andReturn(['conditions' => [], 'params' => []]);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->andReturn([]);
        $this->expectException(Exception::class);
        $sutMock->getPets($this->fakeData->petSearchParams());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPets
     */
    public function testGetPetsShouldThrowIfFetchAllThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $sutMock->shouldReceive('buildPetSearchParams')
            ->andReturn(['conditions' => [], 'params' => []]);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetchAll')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $sutMock->getPets($this->fakeData->petSearchParams());
    }

    // *********************** updateStatus ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldCallPrepareWithCorrectSql()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->with(
                Mockery::on(function ($arg) {
                    $this->assertEquals("UPDATE pets SET status = :newStatus WHERE id = :petId", $arg);
                    return true;
                })
            )
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')->once()
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')->once()
            ->andReturn($this->fakeData->petData());
        $sutMock->updateStatus($this->fakeData->id(), $this->fakeData->word());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldCallExecuteWithCorrectSql()
    {
        $petId = $this->fakeData->id();
        $newStatus = $this->fakeData->word();
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->with(Mockery::on(
                function ($arg) use ($newStatus, $petId) {
                    $this->assertEquals(
                        [
                            'newStatus' => $newStatus,
                            'petId' => $petId
                        ],
                        $arg
                    );
                    return true;
                }
            ))
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')->once()
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')->once()
            ->andReturn($this->fakeData->petData());
        $sutMock->updateStatus($petId, $newStatus);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldCallGetByIdWithCorrectSql()
    {
        $petId = $this->fakeData->id();
        $newStatus = $this->fakeData->word();
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')->once()
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')->once()
            ->with(Mockery::on(
                function ($arg) use ($petId) {
                    $this->assertEquals($petId, $arg);
                    return true;
                }
            ))
            ->andReturn($this->fakeData->petData());
        $sutMock->updateStatus($petId, $newStatus);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldReturnNullIfRowCountReturnsZero()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')
            ->andReturn(0);
        $result = $sutMock->updateStatus(
            $this->fakeData->id(),
            $this->fakeData->word()
        );
        $this->assertNull($result);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldReturnThePet()
    {
        $petData = $this->fakeData->petData();
        $petId = $this->fakeData->id();
        $newStatus = $this->fakeData->word();
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')
            ->andReturn($petData);
        $result =  $sutMock->updateStatus($petId, $newStatus);
        $this->assertEquals($petData, $result);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldThrowIfPrepareThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')
            ->andReturn($this->fakeData->petData());
        $this->expectException(Exception::class);
        $sutMock->updateStatus(
            $this->fakeData->id(),
            $this->fakeData->word()
        );
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldThrowIfExecuteThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('rowCount')
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')
            ->andReturn($this->fakeData->petData());
        $this->expectException(Exception::class);
        $sutMock->updateStatus(
            $this->fakeData->id(),
            $this->fakeData->word()
        );
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldThrowIfRowCountThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')
            ->andThrow(new Exception());
        $sutMock->shouldReceive('getPetById')
            ->andReturn($this->fakeData->petData());
        $this->expectException(Exception::class);
        $sutMock->updateStatus(
            $this->fakeData->id(),
            $this->fakeData->word()
        );
    }


    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::updateStatus
     */
    public function testUpdateStatusShouldThrowIfGetByIdThrows()
    {
        $sutMock = Mockery::mock(PetMySQLRepository::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $sutMock->__construct($this->pdoMock);
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('rowCount')
            ->andReturn(1);
        $sutMock->shouldReceive('getPetById')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $sutMock->updateStatus(
            $this->fakeData->id(),
            $this->fakeData->word()
        );
    }

    // *********************** getPetById ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldCallPrepareWithCorrectSql()
    {
        $sql = "SELECT * FROM pets WHERE id = :petId";
        $this->pdoMock->shouldReceive('prepare')->once()
            ->with(
                Mockery::on(
                    function ($arg) use ($sql) {
                        $this->assertEquals($sql, $arg);
                        return true;
                    }
                )
            )->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->andReturn($this->fakeData->petData());
        $this->sut->getPetById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldCallExecuteWithCorrectValues()
    {
        $petId = $this->fakeData->id();
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()
            ->with(
                Mockery::on(
                    function ($arg) use ($petId) {
                        $this->assertEquals(['petId' => $petId], $arg);
                        return true;
                    }
                )
            )
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->andReturn($this->fakeData->petData());
        $this->sut->getPetById($petId);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldCallFetchWithCorrectValue()
    {
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()
            ->with(
                Mockery::on(
                    function ($arg) {
                        $this->assertEquals(PDO::FETCH_ASSOC, $arg);
                        return true;
                    }
                )
            )
            ->andReturn($this->fakeData->petData());
        $this->sut->getPetById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldReturnTheFoundPet()
    {
        $petData = $this->fakeData->petData();
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')

            ->andReturn($petData);
        $result = $this->sut->getPetById($petData->id);
        $this->assertEquals($petData, $result);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldThrowIfPrepareThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')

            ->andReturn($this->fakeData->petData());
        $this->expectException(Exception::class);
        $this->sut->getPetById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldThrowIfExecuteThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('fetch')

            ->andReturn($this->fakeData->petData());
        $this->expectException(Exception::class);
        $this->sut->getPetById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository::getPetById
     */
    public function testGetByIdShouldThrowIfFetchThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')

            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->getPetById($this->fakeData->id());
    }
}
