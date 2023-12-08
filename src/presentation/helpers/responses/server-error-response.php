<?php

namespace PetAdoption\presentation\helpers\responses;

use Error;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\helpers\errors\ServerError;

function serverError(
    Error $error = new ServerError()
): ControllerOutputType {
    $output = new ControllerOutputType();
    $output->statusCode = 500;
    $output->data = $error;
    return $output;
};
