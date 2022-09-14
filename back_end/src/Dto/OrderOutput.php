<?php

namespace App\Dto;

use App\Entity\Address;
use App\Entity\OrderItem;
use App\Entity\OrderReturn;
use App\Entity\Status;
use App\Entity\User;

final class OrderOutput
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
     * @var string
     */
    public $orderReference;

    public $createdAt;

    /**
     * @var Status
     */
    public $status;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var OrderItem
     */
    public $orderItems;

    /**
     * @var User
     */
    public $orderUser;

    /**
     * @var OrderReturn
     */
    public $orderReturn;
}
