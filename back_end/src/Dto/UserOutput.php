<?php

namespace App\Dto;

/**
 * Generic Output DTO for a User.
 */
final class UserOutput
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    public $birthDate;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var AddressOutput|null
     */
    public $mainAddress = null;

    /**
     * @var AddressOutput[]
     */
    public $addresses = [];

    /**
     * @var bool
     */
    public $isActivated = false;

    /**
     * @var string
     */
    public $activationKey;

    /**
     * @var OrderOutput[]
     */
    public $orders = [];
}
