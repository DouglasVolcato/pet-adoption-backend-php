<?php

namespace PetAdoption\infra\adapters;

use GuzzleHttp\Client;
use PetAdoption\infra\protocols\utils\ClientGetRequestSenderInterface;

class GuzzleHttpAdapter implements ClientGetRequestSenderInterface
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function get($url, $headers = []): object|array
    {
        $response = $this->httpClient->get($url, [
        'headers' => $headers,
        'http_errors' => false,
        ]);

        return json_decode($response->getBody());
    }
}
