<?php

interface DatabaseConnectorInterface
{
    public function connect(): Void;
    public function disconnect(): Void;
    public function startTransaction(): Void;
    public function commitTransaction(): Void;
    public function rollbackTransaction(): Void;
}
