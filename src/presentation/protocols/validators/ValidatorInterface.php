<?php

namespace PetAdoption\presentation\protocols\validators;

use Error;

interface ValidatorInterface
{
    public function validate(object $data): Error | null;
}
