<?php

namespace App\Dto;

use App\DtoHelper\AddressDtoHelperTrait;
use App\Validator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Dto for modify an address.
 */
class AddressInput
{
    use AddressDtoHelperTrait;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     */
    public $title;

    /**
     * @var int
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Positive
     */
    public $number;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     */
    public $road;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     */
    public $zipcode;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     */
    public $city;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Regex(
     *     pattern="/^([+]?[\s0-9]+)?(\d{3}|[(]?[0-9]+[)])?([-]?[\s]?[0-9])+$/",
     *     message="This is not a phone number."
     * )
     */
    public $phone;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Email
     * @Validator\RegisteredEmail
     */
    public $asUser;
}
