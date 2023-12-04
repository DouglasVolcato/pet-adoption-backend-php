<?php

interface CreateUserRepositoryInterface
{
  public function create(UserEntityType $userEntity): UserEntityType;
}
