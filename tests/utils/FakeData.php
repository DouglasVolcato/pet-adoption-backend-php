<?php

namespace PetAdoptionTest\utils;

use PetAdoption\domain\protocols\entities\UserEntityType;
use PetAdoption\domain\protocols\entities\PetEntityType;
use PetAdoption\domain\protocols\enums\PetCategoryEnum;
use PetAdoption\domain\protocols\enums\PetStatusEnum;
use Faker\Generator;
use Faker\Factory;

class FakeData
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public static function getInstance()
    {
        return new self();
    }

    public function email(): string
    {
        return $this->faker->email;
    }

    public function password(): string
    {
        return $this->faker->password;
    }

    public function word($length = 10): string
    {
        return $this->faker->regexify('[A-Za-z0-9]{' . $length . '}');
    }

    public function id(): string
    {
        return $this->faker->uuid;
    }

    public function numberInteger(): int
    {
        return $this->faker->numberBetween();
    }

    public function phrase(): string
    {
        return $this->faker->words(3, true);
    }

    public function url(): string
    {
        return $this->faker->url;
    }

    public function bool(): bool
    {
        return $this->faker->boolean;
    }

    public function date(): string
    {
        return $this->faker->date('Y-m-d');
    }

    public function userData(): UserEntityType
    {
        $data = new UserEntityType();
        $data->id = $this->id();
        $data->name = $this->word();
        $data->email = $this->email();
        $data->password = $this->password();
        $data->admin = $this->bool();
        return $data;
    }


    public function petData(): PetEntityType
    {
        $data = new PetEntityType();
        $data->id = $this->id();
        $data->name = $this->word();
        $data->description = $this->phrase();
        $data->image = $this->word(100);
        $data->createdAt = $this->date();
        $data->category = PetCategoryEnum::CATS;
        $data->status = PetStatusEnum::FREE;
        return $data;
    }
}
