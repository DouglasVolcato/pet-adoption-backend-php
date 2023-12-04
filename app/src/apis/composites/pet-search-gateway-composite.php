<?php

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
