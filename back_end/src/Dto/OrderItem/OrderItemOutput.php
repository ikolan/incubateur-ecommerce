<?php

namespace App\Dto\OrderItem;

use App\Entity\Product;

final class OrderItemOutput
{
    /**
     * @var int
     */
    public $quantity;

    /**
     * @var int
     */
    public $unitPrice;

    /**
     * @var Product
     */
    public $product;
}
