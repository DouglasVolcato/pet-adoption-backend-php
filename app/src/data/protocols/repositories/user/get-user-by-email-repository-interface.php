<?php

interface GetUserByEmailRepositoryInterface
{
  public function getByEmail(string $email): UserEntityType | null;
}
