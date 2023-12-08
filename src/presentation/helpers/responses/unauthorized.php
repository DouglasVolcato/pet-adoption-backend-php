<?php

namespace PetAdoption\presentation\helpers\responses;

use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\helpers\errors\UnauthorizedError;

function unauthorized(): ControllerOutputType
{
    $output = new ControllerOutputType();
    $output->statusCode = 400;
    $output->data = new UnauthorizedError();
    return $output;
};
