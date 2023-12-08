<?php

namespace PetAdoption\apis\composites;

use Generator;
use PetAdoption\apis\protocols\gateways\GatewayInterface;
use PetAdoption\domain\protocols\utils\PetSearcherInterface;

class PetSearchGatewayComposite implements GatewayInterface, PetSearcherInterface
{
    private $gateways = [];

    public function __construct(array $gateways)
    {
        $this->gateways = $gateways;
    }

    public function request(): Generator
    {
        foreach ($this->gateways as $gateway) {
            foreach ($gateway->request() as $pets) {
                yield $pets;
            }
        }
    }
}
