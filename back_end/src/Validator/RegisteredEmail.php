<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for checking if an email address is already use in the database.
 *
 * @Annotation
 */
class RegisteredEmail extends Constraint
{
    public function validatedBy()
    {
        return static::class.'Validator';
    }
}
