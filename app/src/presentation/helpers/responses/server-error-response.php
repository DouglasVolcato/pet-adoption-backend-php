<?php

function serverError(
  Error $error = new ServerError()
): ControllerOutputType {
  $output = new ControllerOutputType();
  $output->statusCode = 500;
  $output->data = $error;
  return $output;
};
