<?php

interface ControllerInterface
{
  public function execute(
    object $request
  ): ControllerOutputType;
}
