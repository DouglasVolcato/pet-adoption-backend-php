<?php

use PetAdoption\apis\protocols\gateways\GatewayInterface;
use PetAdoptionTest\utils\FakeData;

class GatewayStub implements GatewayInterface
{
    public function request(): Generator
    {
        $fakeData = FakeData::getInstance();
        yield $fakeData->randomData();
    }
}
