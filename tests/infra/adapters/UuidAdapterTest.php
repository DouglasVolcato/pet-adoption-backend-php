<?php

use Mockery\LegacyMockInterface;
use PetAdoption\infra\adapters\UuidAdapter;
use PetAdoptionTest\utils\FakeData;
use PHPUnit\Framework\TestCase;

class UuidAdapterTest extends TestCase
{
    private UuidAdapter $sut;
    private FakeData $fakeData;
    private LegacyMockInterface $uuidMock;

    protected function setUp(): void
    {
        $this->fakeData = FakeData::getInstance();
        $this->uuidMock = Mockery::mock('overload:Ramsey\Uuid\Uuid');
        $this->sut = new UuidAdapter();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @covers \PetAdoption\infra\adapters\UuidAdapter::generateId
     */
    public function testShouldReturnWhatUuidReturns()
    {
        $id = $this->fakeData->word();
        $this->uuidMock->shouldReceive('uuid4')->once()->andReturn($id);
        $result = $this->sut->generateId();
        $this->assertEquals($id, $result);
    }

    /**
     * @covers \PetAdoption\infra\adapters\UuidAdapter::generateId
     */
    public function testShouldThrowIfUuidThrows()
    {
        $this->uuidMock->shouldReceive('uuid4')->once()->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->generateId();
    }
}
