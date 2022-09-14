<?php

namespace App\Dto;

use App\Entity\Order;

final class OrderReturnOutput
{
    /**
     * var integer.
     */
    public $id;

    /**
     * @var string
     */
    public $reason;

    /**
     * @var string
     */
    public $description;

    /**
     * @var \DateTimeImmutable
     */
    public $createdAt;

    /**
     * @var Order
     */
    public $order;
}
