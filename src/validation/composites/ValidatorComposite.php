<?php

namespace PetAdoption\validation\composites;

use Error;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;

class ValidatorComposite implements ValidatorInterface
{
    /** @var ValidatorInterface[] */
    private array $validators;

    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    public function validate(object $request): Error | null
    {
        foreach ($this->validators as $validator) {
            $error = $validator->validate($request);
            if ($error) {
                return $error;
            }
        }
        return null;
    }
}
