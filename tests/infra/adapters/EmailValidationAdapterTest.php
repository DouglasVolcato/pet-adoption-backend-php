<?php

namespace PetAdoptionTest\infra\adapters;

use PetAdoption\infra\adapters\EmailValidationAdapter;
use Egulias\EmailValidator\Validation\RFCValidation;
use PetAdoptionTest\utils\FakeData;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;
use Exception;
use Mockery;

class EmailValidationAdapterTest extends TestCase
{
    private EmailValidationAdapter $sut;
    private FakeData $fakeData;
    private LegacyMockInterface $emailValidatorMock;

    protected function setUp(): void
    {
        $this->emailValidatorMock = Mockery::mock('overload:Egulias\EmailValidator\EmailValidator');
        $this->fakeData = FakeData::getInstance();
        $this->sut = new EmailValidationAdapter();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @covers \PetAdoption\infra\adapters\EmailValidationAdapter::isEmail
     */
    public function testShouldCallEmailValidatorWithCorrectValues()
    {
        $email = $this->fakeData->email();
        $this->emailValidatorMock->shouldReceive('isValid')->with(
            Mockery::on(
                function ($arg) use ($email) {
                    $this->assertEquals($email, $arg);
                    return true;
                }
            ),
            Mockery::on(
                function ($arg) {
                    $this->assertEquals(new RFCValidation(), $arg);
                    return true;
                }
            )
        )->andReturn(true);
        $this->sut->isEmail($email);
    }

    /**
     * @covers \PetAdoption\infra\adapters\EmailValidationAdapter::isEmail
     */
    public function testShouldReturnTrueIfEmailValidatorReturnsTrue()
    {
        $this->emailValidatorMock->shouldReceive('isValid')->andReturn(true);
        $result = $this->sut->isEmail($this->fakeData->email());
        $this->assertTrue($result);
    }

    /**
     * @covers \PetAdoption\infra\adapters\EmailValidationAdapter::isEmail
     */
    public function testShouldReturnFalseIfEmailValidatorReturnsFalse()
    {
        $this->emailValidatorMock->shouldReceive('isValid')->andReturn(false);
        $result = $this->sut->isEmail($this->fakeData->email());
        $this->assertFalse($result);
    }

    /**
     * @covers \PetAdoption\infra\adapters\EmailValidationAdapter::isEmail
     */
    public function testShouldThrowIfEmailValidatorThrows()
    {
        $this->emailValidatorMock->shouldReceive('isValid')->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->isEmail($this->fakeData->email());
    }
}
