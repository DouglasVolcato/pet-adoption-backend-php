<?php

namespace PetAdoption\main\protocols\middlewares;

interface MiddlewareInterface
{
    public function execute(object $request): object;
}
