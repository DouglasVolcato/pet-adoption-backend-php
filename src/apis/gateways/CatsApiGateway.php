<?php

namespace PetAdoption\apis\gateways;

use Generator;
use PetAdoption\apis\protocols\gateways\GatewayInterface;
use PetAdoption\domain\protocols\enums\PetCategoryEnum;
use PetAdoption\domain\protocols\enums\PetStatusEnum;
use PetAdoption\infra\protocols\utils\ClientGetRequestSenderInterface;

class CatsApiGateway implements GatewayInterface
{
    private $clientGetRequestSender;
    private $url;
    private $headers;
    private $page;

    public function __construct(ClientGetRequestSenderInterface $clientGetRequestSender)
    {
        $this->url = "https://api.thecatapi.com/v1/images/search";
        $this->headers = ['x-api-key' => getenv()['CATS_API_TOKEN']];
        $this->clientGetRequestSender = $clientGetRequestSender;
        $this->page = 0;
    }

    public function request(): Generator
    {
        while ($this->page <= 1) {
            $data = $this->clientGetRequestSender->get(
                "{$this->url}?limit=80&order=Asc&page={$this->page}",
                $this->headers
            );
            $this->page++;
            yield array_map(function ($pet) { {
                    $name = "Cat";
                    $description = '';
                    if (
                        property_exists($pet, 'breeds')
                        && array_key_exists(0, $pet->breeds)
                    ) {
                        $name = ($pet->breeds[0])->name;
                        $description = ($pet->breeds[0])->description;
                    }
                    return (object)[
                        'id' => '',
                        'createdAt' => '',
                        'image' => $pet->url,
                        'name' => $name,
                        'description' => $description,
                        'category' => PetCategoryEnum::CATS,
                        'status' => PetStatusEnum::FREE,
                    ];
                }
            }, $data);
        }
    }
}
