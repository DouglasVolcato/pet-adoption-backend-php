<?php

namespace PetAdoption\presentation\protocols\utils;

interface DatabaseConnectorInterface
{
    public function connect(): void;
    public function disconnect(): void;
    public function startTransaction(): void;
    public function commitTransaction(): void;
    public function rollbackTransaction(): void;
}
