<?php

namespace App\Dto;

use App\DtoHelper\AddressDtoHelperTrait;

/**
 * Dto to return an address.
 */
final class AddressOutput
{
    use AddressDtoHelperTrait;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $number;

    /**
     * @var string
     */
    public $road;

    /**
     * @var string
     */
    public $zipcode;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $phone;
}
