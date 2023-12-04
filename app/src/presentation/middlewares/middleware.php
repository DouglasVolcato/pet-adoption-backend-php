<?php

abstract class Middleware implements MiddlewareInterface
{
  private  ValidatorInterface $validator;

  public function __construct()
  {
    $this->validator = $this->getValidation();
  }

  protected abstract function perform(
    object $request
  ): object;

  protected abstract function getValidation(): ValidatorInterface;

  public function execute(
    object $request
  ): object {
    try {
      $error = $this->validator->validate($request);
      if ($error !== null) return $error;
      return $this->perform($request);
    } catch (Error $error) {
      return new ServerError($error->getMessage());
    }
  }
}
