<?php

namespace App\Dto;

use App\Validator;
use Symfony\Component\Validator\Constraints as Assert;

class AddressDeletionInput
{
    /**
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Email
     * @Validator\RegisteredEmail
     */
    public $asUser;
}
