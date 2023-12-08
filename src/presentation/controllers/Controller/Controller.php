<?php
namespace PetAdoption\presentation\controllers\Controller;


use Error;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;

use function PetAdoption\presentation\helpers\responses\badRequest;
use function PetAdoption\presentation\helpers\responses\serverError;

abstract class Controller implements ControllerInterface
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = $this->getValidation();
    }

    abstract protected function perform(object $request): ControllerOutputType;

    abstract protected function getValidation(): ValidatorInterface;

    public function execute(
        object $request
    ): ControllerOutputType {
        try {
            $error = $this->validator->validate($request);
            if ($error !== null) {
                return badRequest($error);
            }
            return $this->perform($request);
        } catch (Error $error) {
            return serverError($error);
        }
    }
}
