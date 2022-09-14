<?php

namespace App\Dto;

use App\Entity\Address;
use App\Entity\Status;
use App\Entity\User;

final class OrderInput
{
    /**
     * @var int
     */
    public $price;

    /**
     * @var bool
     */
    public $isReturned;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var User|null
     */
    public $user;

    /**
     * @var OrderItemInput[]
     */
    public $items;

    /**
     * @var Status|null
     */
    public $status;
}
