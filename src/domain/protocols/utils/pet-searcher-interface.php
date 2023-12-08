<?php

namespace PetAdoption\domain\protocols\utils;

use Generator;

interface PetSearcherInterface
{
    public function request(): Generator;
}
