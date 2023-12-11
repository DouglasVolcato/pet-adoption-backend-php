<?php

namespace PetAdoptionTest\infra\databases\mysql\repositories;

use PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository;
use PetAdoptionTest\utils\stubs\infra\database\pdo\PDOStatementStub;
use PetAdoptionTest\utils\stubs\infra\database\pdo\PDOStub;
use PetAdoptionTest\utils\FakeData;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;
use PDOStatement;
use Exception;
use Mockery;
use PDO;

class UserMySQLRepositoryTest extends TestCase
{
    private FakeData $fakeData;
    private UserMySQLRepository $sut;
    private PDO|LegacyMockInterface $pdoMock;
    private PDOStatement|LegacyMockInterface $pdoStatementMock;

    protected function setUp(): void
    {
        $this->fakeData = FakeData::getInstance();
        $this->pdoMock = Mockery::mock(PDOStub::class);
        $this->pdoStatementMock = Mockery::mock(PDOStatementStub::class);
        $this->sut = new UserMySQLRepository($this->pdoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    // *********************** create ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::create
     */
    public function testCreateShouldCallPrepareWithCorrectSql()
    {
        $sql = "INSERT INTO users (email, password, admin) VALUES (:email, :password, :admin)";
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
        $this->sut->create($this->fakeData->userData());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::create
     */
    public function testCreateShouldCallExecuteWithCorrectValues()
    {
        $userData = $this->fakeData->userData();
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->once()->with(
            Mockery::on(function ($arg) use ($userData) {
                $this->assertEquals([
                    'email' => $userData->email,
                    'password' => $userData->password,
                    'admin' => $userData->admin,
                ], $arg);
                return true;
            })
        )->andReturn(true);
        $this->sut->create($userData);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::create
     */
    public function testCreateShouldReturnTheUserData()
    {
        $userData = $this->fakeData->userData();
        $this->pdoMock->shouldReceive('prepare')->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->andReturn(true);
        $result = $this->sut->create($userData);
        $this->assertEquals($userData, $result);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::create
     */
    public function testCreateShouldThrowIfPrepareThrows()
    {
        $this->pdoMock->shouldReceive('prepare')->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')->andReturn(true);
        $this->expectException(Exception::class);
        $this->sut->create($this->fakeData->userData());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::create
     */
    public function testCreateShouldThrowIfExecuteThrows()
    {
        $this->pdoMock->shouldReceive('prepare')->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->create($this->fakeData->userData());
    }

    // *********************** getByEmail ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldCallPrepareWithCorrectSql()
    {
        $sql = "SELECT * FROM users WHERE email = :email";
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
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->andReturn(null);
        $this->sut->getByEmail($this->fakeData->email());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldCallExecuteWithCorrectValues()
    {
        $email = $this->fakeData->email();
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->with(
                Mockery::on(function ($arg) use ($email) {
                    $this->assertEquals(['email' => $email], $arg);
                    return true;
                })
            )->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->andReturn(null);
        $this->sut->getByEmail($email);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldCallFetchWithCorrectValues()
    {
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->with(
                Mockery::on(function ($arg) {
                    $this->assertEquals(PDO::FETCH_ASSOC, $arg);
                    return true;
                })
            )->andReturn(null);
        $this->sut->getByEmail($this->fakeData->email());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldReturnTheFoundUser()
    {
        $userData = $this->fakeData->userData();
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andReturn($userData);
        $result = $this->sut->getByEmail($userData->email);
        $this->assertEquals($userData, $result);
    }


    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldThrowIfPrepareThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andReturn(null);
        $this->expectException(Exception::class);
        $this->sut->getByEmail($this->fakeData->email());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldThrowIfExecuteThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andReturn(null);
        $this->expectException(Exception::class);
        $this->sut->getByEmail($this->fakeData->email());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByEmailShouldThrowIfFetchThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->getByEmail($this->fakeData->email());
    }

    // *********************** getById ************************** \\

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getByEmail
     */
    public function testGetByIdShouldCallPrepareWithCorrectSql()
    {
        $sql = "SELECT * FROM users WHERE id = :id";
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
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->andReturn(null);
        $this->sut->getById($this->fakeData->email());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getById
     */
    public function testGetByIdShouldCallExecuteWithCorrectValues()
    {
        $id = $this->fakeData->id();
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->with(
                Mockery::on(function ($arg) use ($id) {
                    $this->assertEquals(['id' => $id], $arg);
                    return true;
                })
            )->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->andReturn(null);
        $this->sut->getById($id);
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getById
     */
    public function testGetByIdShouldCallFetchWithCorrectValues()
    {
        $this->pdoMock->shouldReceive('prepare')->once()
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->once()->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->once()->with(
                Mockery::on(function ($arg) {
                    $this->assertEquals(PDO::FETCH_ASSOC, $arg);
                    return true;
                })
            )->andReturn(null);
        $this->sut->getById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getById
     */
    public function testGetByIdShouldReturnTheFoundUser()
    {
        $userData = $this->fakeData->userData();
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andReturn($userData);
        $result = $this->sut->getById($userData->id);
        $this->assertEquals($userData, $result);
    }


    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getById
     */
    public function testGetByIdShouldThrowIfPrepareThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('execute')
            ->andReturn(true);
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andReturn(null);
        $this->expectException(Exception::class);
        $this->sut->getById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getById
     */
    public function testGetByIdShouldThrowIfExecuteThrows()
    {
        $this->pdoMock->shouldReceive('prepare')
            ->andReturn($this->pdoStatementMock);
        $this->pdoStatementMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->pdoStatementMock->shouldReceive('fetch')
            ->andReturn(null);
        $this->expectException(Exception::class);
        $this->sut->getById($this->fakeData->id());
    }

    /**
     * @covers \PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository::getById
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
        $this->sut->getById($this->fakeData->id());
    }
}
