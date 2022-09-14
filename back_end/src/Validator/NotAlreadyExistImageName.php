<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for checking if a string is already use as a image name.
 *
 * @Annotation
 */
class NotAlreadyExistImageName extends Constraint
{
    public function validatedBy()
    {
        return static::class.'Validator';
    }
}
