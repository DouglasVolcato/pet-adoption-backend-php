<?php

namespace PetAdoption\presentation\helpers\errors;

use Error;

class ServerError extends Error
{
    public function __construct(string $message = "Server error")
    {
        parent::__construct($message);
    }
}
