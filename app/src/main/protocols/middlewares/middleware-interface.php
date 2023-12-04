<?php

interface MiddlewareInterface
{
  public function execute(object $request): object;
}
