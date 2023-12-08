<?php

namespace PetAdoption\domain\protocols\utils;

interface IdGeneratorInterface
{
    public function generateId(): string;
}
