<?php

interface GetUserByIdRepositoryInterface
{
  public function getById(string $id): UserEntityType | null;
}
