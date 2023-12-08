<?php

namespace PetAdoption\presentation\helpers\errors;

use Error;

class UnauthorizedError extends Error
{
    public function __construct()
    {
        parent::__construct('Unauthorized');
    }
}
