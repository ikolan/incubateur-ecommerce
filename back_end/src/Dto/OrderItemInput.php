<?php

namespace App\Dto;

use App\Entity\Product;

final class OrderItemInput
{
    /**
     * @var Product
     */
    public $product;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var int
     */
    public $unitPrice;
}
