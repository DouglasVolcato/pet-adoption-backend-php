<?php

namespace PetAdoption\domain\protocols\entities;

use PetAdoption\domain\protocols\enums\PetCategoryEnum;
use PetAdoption\domain\protocols\enums\PetStatusEnum;

class PetEntityType
{
    public string $id;
    public string $name;
    public string $description;
    public string $image;
    public string $createdAt;
    public PetCategoryEnum $category;
    public PetStatusEnum $status;
}
