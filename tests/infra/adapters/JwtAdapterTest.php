<?php

namespace PetAdoptionTest\infra\adapters;

use PetAdoption\infra\adapters\JwtAdapter;
use PetAdoptionTest\utils\FakeData;
use PHPUnit\Framework\TestCase;
use Mockery\LegacyMockInterface;
use Exception;
use Mockery;

class JwtAdapterTest extends TestCase
{
    private JwtAdapter $sut;
    private FakeData $fakeData;
    private LegacyMockInterface $jwtMock;

    protected function setUp(): void
    {
        $this->jwtMock = Mockery::mock('overload:Firebase\JWT\JWT');
        $this->fakeData = FakeData::getInstance();
        $this->sut = new JwtAdapter();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @covers \PetAdoption\infra\adapters\JwtAdapter::generateToken
     */
    public function testShouldCallEncodeWithCorrectValues()
    {
        $content = (object)['id' => $this->fakeData->id()];
        $secret = $this->fakeData->word();

        $this->jwtMock->shouldReceive('encode')
            ->once()
            ->with(
                Mockery::on(function ($arg) use ($content) {
                    $this->assertEquals((array)$content, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($secret) {
                    $this->assertEquals($secret, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($secret) {
                    $this->assertEquals('HS256', $arg);
                    return true;
                })
            )->andReturn($this->fakeData->word(12));
        $this->sut->generateToken($content, $secret);
    }

    /**
     * @covers \PetAdoption\infra\adapters\JwtAdapter::generateToken
     */
    public function testShouldReturnATokenOnSuccess()
    {
        $content = (object)['id' => $this->fakeData->id()];
        $secret = $this->fakeData->word();
        $token = $this->fakeData->word(12);
        $this->jwtMock->shouldReceive('encode')
            ->andReturn($token);
        $result = $this->sut->generateToken($content, $secret);
        $this->assertEquals($token, $result);
    }

    /**
     * @covers \PetAdoption\infra\adapters\JwtAdapter::generateToken
     */
    public function testShouldThrowIfEncodeThrows()
    {
        $content = (object)['id' => $this->fakeData->id()];
        $secret = $this->fakeData->word();
        $this->jwtMock->shouldReceive('encode')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->generateToken($content, $secret);
    }

    /**
     * @covers \PetAdoption\infra\adapters\JwtAdapter::decryptToken
     */
    public function testShouldCallDecodeWithCorrectValues()
    {
        $token = $this->fakeData->word();
        $secret = $this->fakeData->word();
        $headers = (object)['algorithms' => ['HS256']];
        $this->jwtMock->shouldReceive('decode')
            ->once()
            ->with(
                Mockery::on(function ($arg) use ($token) {
                    $this->assertEquals($token, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($secret) {
                    $this->assertEquals($secret, $arg);
                    return true;
                }),
                Mockery::on(function ($arg) use ($headers) {
                    $this->assertEquals($headers, $arg);
                    return true;
                }),
            )
            ->andReturn(null);
        $this->sut->decryptToken($token, $secret);
    }

    /**
     * @covers \PetAdoption\infra\adapters\JwtAdapter::decryptToken
     */
    public function testShouldReturnTheDecodedObject()
    {
        $token = $this->fakeData->word();
        $secret = $this->fakeData->word();
        $result = (object)['id' => $this->fakeData->id()];

        $this->jwtMock->shouldReceive('decode')
            ->andReturn($result);
        $output = $this->sut->decryptToken($token, $secret);
        $this->assertEquals($result, $output);
    }

    /**
     * @covers \PetAdoption\infra\adapters\JwtAdapter::decryptToken
     */
    public function testShouldThrowIfDecodeThrows()
    {
        $token = $this->fakeData->word();
        $secret = $this->fakeData->word();
        $this->jwtMock->shouldReceive('decode')
            ->andThrow(new Exception());
        $this->expectException(Exception::class);
        $this->sut->decryptToken($token, $secret);
    }
}
