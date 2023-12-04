<?php

class PetStatusEnum
{
  const FREE = "free";
  const ADOPTED = "adopted";
}

class PetCategoryEnum
{
  const CATS = "cats";
  const DOGS = "dogs";
}

class PetEntityType
{
  public string $id;
  public string $name;
  public string $description;
  public string $image;
  public string $createdAt;
  public string $category;
  public string $status;
}
