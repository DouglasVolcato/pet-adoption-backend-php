<?php

namespace PetAdoption\main\protocols\controllers;

interface ControllerInterface
{
    public function execute(
        object $request
    ): ControllerOutputType;
}
