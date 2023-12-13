<?php

namespace PetAdoption\infra\protocols\utils;

interface ClientGetRequestSenderInterface
{
    public function get(string $url, array $headers = []): object|array;
}
