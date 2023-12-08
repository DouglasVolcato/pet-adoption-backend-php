<?php

namespace PetAdoption\apis\protocols\gateways;

use Generator;

interface GatewayInterface
{
    public function request(): Generator;
}
