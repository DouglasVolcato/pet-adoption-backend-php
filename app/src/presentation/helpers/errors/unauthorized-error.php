<?php

class UnauthorizedError extends Error
{
  public function __construct()
  {
    parent::__construct('Unauthorized');
  }
}
