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
    public function testShouldCallJwtEncodeWithCorrectValues()
    {
        $content = (object)['id' => $this->fakeData->id()];
        $secret = $this->fakeData->word();

        $this->jwtMock->shouldReceive('encode')
            ->once()
            ->with(
                (array)$content,
                $secret,
                'HS256'
            )->andReturn($this->fakeData->word(12));
        $this->sut->generateToken($content, $secret);
        $this->assertTrue(true);
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
            ->andThrow(new Exception('Something went wrong'));
        $this->expectException(Exception::class);
        $this->sut->generateToken($content, $secret);
    }
}
