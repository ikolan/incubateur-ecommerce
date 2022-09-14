<?php

namespace App\Dto;

use App\DtoHelper\ContactDtoHelperTrait;
use Symfony\Component\Validator\Constraints as Assert;

class ContactCreationInput
{
    use ContactDtoHelperTrait;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $lastName;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $subject;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $message;
}
