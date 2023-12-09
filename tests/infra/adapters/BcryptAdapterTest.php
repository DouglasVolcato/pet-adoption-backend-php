<?php

namespace PetAdoptionTest\infra\adapters;

use Mockery;
use Exception;
use PetAdoption\infra\adapters\BcryptAdapter;
use PetAdoptionTest\utils\FakeData;
use PHPUnit\Framework\TestCase;

class BcryptAdapterTest extends TestCase
{
    private FakeData $fakeData;

    protected function setUp(): void
    {
        $this->fakeData = FakeData::getInstance();
    }

    /**
     * @covers \PetAdoption\infra\adapters\BcryptAdapter::hash
     */
    public function testShouldCallHashWithCorrectValues()
    {
        $password = $this->fakeData->password();

        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordHash')
            ->once()
            ->with(
                Mockery::on(function ($arg) use ($password) {
                    $this->assertEquals($password, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) {
                    $this->assertEquals(PASSWORD_BCRYPT, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) {
                    $this->assertEquals(['cost' => 10], $arg);
                    return true;
                })
            )
            ->andReturn($this->fakeData->password());
        $bcryptAdapterMock->hash($password);
    }


    /**
     * @covers \PetAdoptionTest\infra\adapters\BcryptAdapter::hash
     */
    public function testShouldReturnTheHashedPassword()
    {
        $password = $this->fakeData->password();
        $hashedPassword = $this->fakeData->password();
        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordHash')
            ->andReturn($hashedPassword);
        $output = $bcryptAdapterMock->hash($password);
        $this->assertEquals($hashedPassword, $output);
    }

    /**
     * @covers \PetAdoptionTest\infra\adapters\BcryptAdapter::hash
     */
    public function testShouldThrowIfPasswordHashThrows()
    {
        $password = $this->fakeData->password();
        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordHash')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $bcryptAdapterMock->hash($password);
    }

    /**
     * @covers \PetAdoptionTest\infra\adapters\BcryptAdapter::validate
     */
    public function testShouldCallValidateWithCorrectValues()
    {
        $password = $this->fakeData->password();
        $hashedPassword = $this->fakeData->password();

        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordVerify')
            ->with(
                Mockery::on(function ($arg) use ($password) {
                    $this->assertEquals($password, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($hashedPassword) {
                    $this->assertEquals($hashedPassword, $arg);
                    return true;
                })
            )->andReturn(true);
        $bcryptAdapterMock->validate($password, $hashedPassword);
    }

    /**
     * @covers \PetAdoptionTest\infra\adapters\BcryptAdapter::validate
     */
    public function testShouldReturnTrueIfValidateReturnsTrue()
    {
        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordVerify')->andReturn(true);
        $output = $bcryptAdapterMock->validate(
            $this->fakeData->password(),
            $this->fakeData->password()
        );
        $this->assertTrue($output);
    }

    /**
     * @covers \PetAdoptionTest\infra\adapters\BcryptAdapter::validate
     */
    public function testShouldReturnFalseIfValidateReturnsFalse()
    {
        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordVerify')->andReturn(false);
        $output = $bcryptAdapterMock->validate(
            $this->fakeData->password(),
            $this->fakeData->password()
        );
        $this->assertFalse($output);
    }

    /**
     * @covers \PetAdoptionTest\infra\adapters\BcryptAdapter::validate
     */
    public function testShouldThrowIfValidateThrows()
    {
        $bcryptAdapterMock = Mockery::mock(BcryptAdapter::class)
            ->makePartial();
        $bcryptAdapterMock->shouldReceive('passwordVerify')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $bcryptAdapterMock->validate(
            $this->fakeData->password(),
            $this->fakeData->password()
        );
    }
}
