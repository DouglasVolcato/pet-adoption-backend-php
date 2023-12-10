<?php

namespace PetAdoptionTest\infra\adapters;

use PetAdoption\infra\adapters\GuzzleHttpAdapter;
use PetAdoptionTest\utils\FakeData;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\ClientInterface;
use ReflectionProperty;
use Exception;
use Mockery;

class GuzzleFakeResponse
{
    private readonly object $body;

    public function __construct()
    {
        $fakeData = FakeData::getInstance();
        $this->body = (object)[$fakeData->word() => $fakeData->word()];
    }
    public function getBody(): string
    {
        return json_encode($this->body);
    }
}

class GuzzleHttpAdapterTest extends TestCase
{
    private GuzzleHttpAdapter $sut;
    private FakeData $fakeData;
    private LegacyMockInterface $guzzleMock;

    protected function setUp(): void
    {
        $this->fakeData = FakeData::getInstance();
        $this->guzzleMock = Mockery::mock(ClientInterface::class);
        $this->sut = new GuzzleHttpAdapter();
        $reflection = new ReflectionProperty(
            GuzzleHttpAdapter::class,
            'httpClient'
        );
        $reflection->setAccessible(true);
        $reflection->setValue($this->sut, $this->guzzleMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @covers \PetAdoption\infra\adapters\GuzzleHttpAdapter::get
     */
    public function testShouldCallGetWithCorrectValues()
    {
        $url = $this->fakeData->url();
        $headers = ['key' => $this->fakeData->word()];
        $this->guzzleMock
            ->shouldReceive('get')
            ->once()
            ->with(
                Mockery::on(function ($arg) use ($url) {
                    $this->assertEquals($url, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($headers) {
                    $this->assertEquals(
                        [
                            'headers' => $headers,
                            'http_errors' => false,
                        ],
                        $arg
                    );
                    return true;
                })
            )
            ->andReturn(new GuzzleFakeResponse());
        $this->sut->get($url, $headers);
    }

    /**
     * @covers \PetAdoption\infra\adapters\GuzzleHttpAdapter::get
     */
    public function testShouldReturnTheDecodedGetResponseBody()
    {
        $url = $this->fakeData->url();
        $headers = ['key' => $this->fakeData->word()];
        $response = new GuzzleFakeResponse();
        $this->guzzleMock
            ->shouldReceive('get')
            ->andReturn($response);
        $output = $this->sut->get($url, $headers);
        $this->assertEquals(json_decode($response->getBody()), $output);
    }

    /**
     * @covers \PetAdoption\infra\adapters\GuzzleHttpAdapter::get
     */
    public function testShouldThrowIfGetThrows()
    {
        $this->guzzleMock
            ->shouldReceive('get')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->get($this->fakeData->url(), []);
    }
}
