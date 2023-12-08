<?php

namespace PetAdoption\presentation\helpers\responses;

use Error;
use PetAdoption\main\protocols\controllers\ControllerOutputType;

function badRequest(Error $error): ControllerOutputType
{
    $output = new ControllerOutputType();
    $output->statusCode = 400;
    $output->data = $error;
    return $output;
};
