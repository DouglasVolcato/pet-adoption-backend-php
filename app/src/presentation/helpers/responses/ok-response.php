<?php

function ok(object|array $data): ControllerOutputType
{
  $output = new ControllerOutputType();
  $output->statusCode = 200;
  $output->data = $data;
  return $output;
};
