<?php

namespace App\Dto;

use App\DtoHelper\AddressDtoHelperTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO for creating an address.
 */
final class AddressCreationInput
{
    use AddressDtoHelperTrait;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $title;

    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public $number;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $road;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $zipcode;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $city;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^([+]?[\s0-9]+)?(\d{3}|[(]?[0-9]+[)])?([-]?[\s]?[0-9])+$/",
     *     message="This is not a phone number."
     * )
     */
    public $phone;
}
