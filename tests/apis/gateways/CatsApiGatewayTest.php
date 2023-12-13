<?php

namespace PetAdoptionTest\apis\gateways;

use PetAdoption\infra\protocols\utils\ClientGetRequestSenderInterface;
use PetAdoptionTest\utils\stubs\infra\adapters\ClientGetRequestSenderStub;
use PetAdoption\domain\protocols\enums\PetCategoryEnum;
use PetAdoption\domain\protocols\enums\PetStatusEnum;
use PetAdoption\apis\gateways\CatsApiGateway;
use PetAdoptionTest\utils\FakeData;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Exception;
use Mockery;

class CatsApiGatewayTest extends TestCase
{
    private CatsApiGateway $sut;
    private ClientGetRequestSenderInterface|LegacyMockInterface $clientGetRequestSender;
    private FakeData $fakeData;

    protected function setUp(): void
    {
        $this->clientGetRequestSender = Mockery::mock(ClientGetRequestSenderStub::class);
        $this->sut = new CatsApiGateway($this->clientGetRequestSender);
        $this->fakeData = FakeData::getInstance();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @covers \PetAdoption\apis\gateways\CatsApiGateway::__construct
     */
    public function testShouldSetUpTheRightApiCredentialsOnClassConstruct()
    {
        $reflectionClass = new ReflectionClass(CatsApiGateway::class);
        $urlProperty = $reflectionClass->getProperty('url');
        $urlProperty->setAccessible(true);
        $headersProperty = $reflectionClass->getProperty('headers');
        $headersProperty->setAccessible(true);
        $clientGetRequestSenderProperty = $reflectionClass->getProperty('clientGetRequestSender');
        $clientGetRequestSenderProperty->setAccessible(true);
        $pageProperty = $reflectionClass->getProperty('page');
        $pageProperty->setAccessible(true);
        $this->assertEquals($urlProperty->getValue($this->sut), "https://api.thecatapi.com/v1/images/search");
        $this->assertEquals($headersProperty->getValue($this->sut), ['x-api-key' => getenv()['CATS_API_TOKEN']]);
        $this->assertEquals($clientGetRequestSenderProperty->getValue($this->sut), $this->clientGetRequestSender);
        $this->assertEquals($pageProperty->getValue($this->sut), 0);
    }

    /**
     * @covers \PetAdoption\apis\gateways\CatsApiGateway::request
     */
    public function testShouldCallClientGetRequestSenderWithCorrectValues()
    {
        $apiLink = "https://api.thecatapi.com/v1/images/search?limit=80&order=Asc&page=";
        $headers = ['x-api-key' => getenv()['CATS_API_TOKEN']];
        $apiReturn = [
            (object)[
                'url' => $this->fakeData->url(),
                'breeds' => [
                    (object)[
                        'name' => $this->fakeData->word(),
                        'description' => $this->fakeData->phrase()
                    ]
                ]
            ]
        ];
        $this->clientGetRequestSender->shouldReceive('get')->once()
            ->withArgs([
                Mockery::on(function ($arg) use ($apiLink) {
                    $this->assertEquals("{$apiLink}0", $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($headers) {
                    $this->assertEquals($headers, $arg);
                    return true;
                }),
            ])->andReturn($apiReturn);
        $this->clientGetRequestSender->shouldReceive('get')->once()
            ->withArgs([
                Mockery::on(function ($arg) use ($apiLink) {
                    $this->assertEquals("{$apiLink}1", $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($headers) {
                    $this->assertEquals($headers, $arg);
                    return true;
                }),
            ])->andReturn($apiReturn);
        foreach ($this->sut->request() as $response) {
        }
    }

    /**
     * @covers \PetAdoption\apis\gateways\CatsApiGateway::request
     */
    public function testShouldReturnTheMappedPets()
    {
        $apiReturn = [
            (object)[
                'url' => $this->fakeData->url(),
                'breeds' => [
                    (object)[
                        'name' => $this->fakeData->word(),
                        'description' => $this->fakeData->phrase()
                    ]
                ]
            ]
        ];
        $mappedPets = [
            (object)[
                'id' => '',
                'createdAt' => '',
                'image' => $apiReturn[0]->url,
                'name' => $apiReturn[0]->breeds[0]->name,
                'description' => $apiReturn[0]->breeds[0]->description,
                'category' => PetCategoryEnum::CATS,
                'status' => PetStatusEnum::FREE,
            ]
        ];
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        foreach ($this->sut->request() as $response) {
            $this->assertEquals($mappedPets, $response);
        }
    }

    /**
     * @covers \PetAdoption\apis\gateways\CatsApiGateway::request
     */
    public function testShouldReturnDefaultValuesIfBreedIsEmpty()
    {
        $apiReturn = [
            (object)[
                'url' => $this->fakeData->url(),
                'breeds' => []
            ]
        ];
        $mappedPets = [
            (object)[
                'id' => '',
                'createdAt' => '',
                'image' => $apiReturn[0]->url,
                'name' => 'Cat',
                'description' => '',
                'category' => PetCategoryEnum::CATS,
                'status' => PetStatusEnum::FREE,
            ]
        ];
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        foreach ($this->sut->request() as $response) {
            $this->assertEquals($mappedPets, $response);
        }
    }

    /**
     * @covers \PetAdoption\apis\gateways\CatsApiGateway::request
     */
    public function testShouldReturnDefaultValuesIfBreedIsUndefined()
    {
        $apiReturn = [
            (object)[
                'url' => $this->fakeData->url()
            ]
        ];
        $mappedPets = [
            (object)[
                'id' => '',
                'createdAt' => '',
                'image' => $apiReturn[0]->url,
                'name' => 'Cat',
                'description' => '',
                'category' => PetCategoryEnum::CATS,
                'status' => PetStatusEnum::FREE,
            ]
        ];
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        foreach ($this->sut->request() as $response) {
            $this->assertEquals($mappedPets, $response);
        }
    }

    /**
     * @covers \PetAdoption\apis\gateways\CatsApiGateway::request
     */
    public function testShouldThrowIfGetRequestSenderThrows()
    {
        $apiReturn = [
            (object)[
                'url' => $this->fakeData->url(),
                'breeds' => [
                    (object)[
                        'name' => $this->fakeData->word(),
                        'description' => $this->fakeData->phrase()
                    ]
                ]
            ]
        ];
        $this->clientGetRequestSender->shouldReceive('get')->andThrow(new Exception());
        $this->clientGetRequestSender->shouldReceive('get')->andReturn($apiReturn);
        $this->expectException(Exception::class);
        foreach ($this->sut->request() as $response) {
        }
    }
}
