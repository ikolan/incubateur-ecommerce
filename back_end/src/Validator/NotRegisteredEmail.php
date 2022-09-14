<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for checking if an email address is already use in the database.
 *
 * @Annotation
 */
class NotRegisteredEmail extends Constraint
{
    public function validatedBy()
    {
        return static::class.'Validator';
    }
}
