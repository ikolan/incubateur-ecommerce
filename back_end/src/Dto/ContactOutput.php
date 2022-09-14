<?php

namespace App\Dto;

use App\DtoHelper\ContactDtoHelperTrait;
use DateTimeImmutable;

final class ContactOutput
{
    use ContactDtoHelperTrait;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $message;

    /**
     * @var DateTimeImmutable
     */
    public $sentAt;
}
