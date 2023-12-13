<?php

namespace PetAdoptionTest\apis\composites;

use PetAdoption\apis\composites\PetSearchGatewayComposite;
use PetAdoption\apis\protocols\gateways\GatewayInterface;
use PetAdoptionTest\utils\FakeData;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;
use GatewayStub;
use Exception;
use Generator;
use Mockery;

class PetSearchGatewayCompositeTest extends TestCase
{
    private PetSearchGatewayComposite $sut;
    private GatewayInterface|LegacyMockInterface $gateway1;
    private GatewayInterface|LegacyMockInterface $gateway2;
    private FakeData $fakeData;

    protected function setUp(): void
    {
        $this->gateway1 = Mockery::mock(GatewayStub::class);
        $this->gateway2 = Mockery::mock(GatewayStub::class);
        $this->sut = new PetSearchGatewayComposite([$this->gateway1, $this->gateway2]);
        $this->fakeData = FakeData::getInstance();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @covers \PetAdoption\apis\composites\PetSearchGatewayComposite::request
     */
    public function testShouldCallTheGateways()
    {
        $this->gateway1->shouldReceive('request')->once()
            ->withNoArgs()
            ->andReturn($this->createGenerator([$this->fakeData->randomData()]));
        $this->gateway2->shouldReceive('request')->once()
            ->withNoArgs()
            ->andReturn($this->createGenerator([$this->fakeData->randomData()]));
        foreach ($this->sut->request() as $response) {
        }
        $this->assertTrue(true);
    }

    /**
     * @covers \PetAdoption\apis\composites\PetSearchGatewayComposite::request
     */
    public function testShouldReturnWhatTheGatewaysReturn()
    {
        $return = [
            [$this->fakeData->randomData()],
            [$this->fakeData->randomData()]
        ];
        $this->gateway1->shouldReceive('request')->andReturn($this->createGenerator([$return[0]]));
        $this->gateway2->shouldReceive('request')->andReturn($this->createGenerator([$return[1]]));
        $i = 0;
        foreach ($this->sut->request() as $response) {
            $this->assertEquals($return[$i], $response);
            $i++;
        }
    }

    /**
     * @covers \PetAdoption\apis\composites\PetSearchGatewayComposite::request
     */
    public function testShouldThrowIfAGatewaysThrows()
    {
        $this->gateway1->shouldReceive('request')->andThrow(new Exception());
        $this->gateway2->shouldReceive('request')
            ->andReturn($this->createGenerator([$this->fakeData->randomData()]));
        $this->expectException(Exception::class);
        foreach ($this->sut->request() as $response) {
        }
    }

    private function createGenerator(array $data): Generator
    {
        foreach ($data as $item) {
            yield $item;
        }
    }
}
