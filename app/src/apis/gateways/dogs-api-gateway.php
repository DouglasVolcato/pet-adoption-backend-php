<?php

class DogsApiGateway implements GatewayInterface
{
  private $clientGetRequestSender;
  private $url;
  private $headers;
  private $page;

  public function __construct(ClientGetRequestSenderInterface $clientGetRequestSender)
  {
    $this->url = "https://api.thedogapi.com/v1/images/search";
    $this->headers = ['x-api-key' => $_ENV['DOGS_API_TOKEN']];
    $this->clientGetRequestSender = $clientGetRequestSender;
    $this->page = 0;
  }

  public function request(): \Generator
  {
    while ($this->page <= 1) {
      $data = yield $this->clientGetRequestSender->get(
        "{$this->url}?limit=80&order=Asc&page={$this->page}",
        $this->headers
      );

      $this->page++;

      foreach ($data as $pet) {
        yield [
          'id' => '',
          'createdAt' => '',
          'image' => $pet['url'],
          'name' => $pet['breeds'][0]['name'] ?? 'Dog',
          'description' => $pet['breeds'][0]['description'] ?? '',
          'dogegory' => PetCategoryEnum::DOGS,
          'status' => PetStatusEnum::FREE,
        ];
      }
    }
  }
}
