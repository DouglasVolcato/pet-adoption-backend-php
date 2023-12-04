<?php

interface GetPetsRepositoryInterface
{
  public function getPets(object $searchParams): array;
}
