<?php

interface ValidatorInterface
{
  public function validate(object $data): Error | null;
}
