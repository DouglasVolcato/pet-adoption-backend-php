<?php

namespace PetAdoption\presentation\helpers\responses;

use PetAdoption\main\protocols\controllers\ControllerOutputType;

function ok(object|array $data): ControllerOutputType
{
    $output = new ControllerOutputType();
    $output->statusCode = 200;
    $output->data = $data;
    return $output;
};
