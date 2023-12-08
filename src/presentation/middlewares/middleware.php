<?php

namespace PetAdoption\presentation\middlewares;

use Error;
use PetAdoption\main\protocols\middlewares\MiddlewareInterface;
use PetAdoption\presentation\helpers\errors\ServerError;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;

abstract class Middleware implements MiddlewareInterface
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = $this->getValidation();
    }

    abstract protected function perform(
        object $request
    ): object;

    abstract protected function getValidation(): ValidatorInterface;

    public function execute(
        object $request
    ): object {
        try {
            $error = $this->validator->validate($request);
            if ($error !== null) {
                return $error;
            }
            return $this->perform($request);
        } catch (Error $error) {
            return new ServerError($error->getMessage());
        }
    }
}
