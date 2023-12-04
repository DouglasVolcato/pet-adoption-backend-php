<?php

abstract class Controller implements ControllerInterface
{
  private ValidatorInterface $validator;

  public function __construct()
  {
    $this->validator = $this->getValidation();
  }

  protected abstract function perform(object $request): ControllerOutputType;

  protected abstract function getValidation(): ValidatorInterface;

  public function execute(
    object $request
  ): ControllerOutputType {
    try {
      $error = $this->validator->validate($request);
      if ($error !== null) return badRequest($error);
      return $this->perform($request);
    } catch (Error $error) {
      return serverError($error);
    }
  }
}
