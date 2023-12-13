<?php

namespace PetAdoptionTest\utils\stubs\infra\adapters;

use PetAdoption\infra\protocols\utils\ClientGetRequestSenderInterface;
use PetAdoptionTest\utils\FakeData;

class ClientGetRequestSenderStub implements ClientGetRequestSenderInterface
{
    public function get(string $url, array $headers = []): object|array
    {
        $fakeData = FakeData::getInstance();
        return $fakeData->randomData();
    }
}
