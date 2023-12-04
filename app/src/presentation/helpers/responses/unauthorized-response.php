<?php

function unauthorized(): ControllerOutputType
{
  $output = new ControllerOutputType();
  $output->statusCode = 400;
  $output->data = new UnauthorizedError();
  return $output;
};
