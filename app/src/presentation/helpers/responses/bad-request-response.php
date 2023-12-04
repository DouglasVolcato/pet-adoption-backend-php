<?php

function badRequest(Error $error): ControllerOutputType
{
  $output = new ControllerOutputType();
  $output->statusCode = 400;
  $output->data = $error;
  return $output;
};
