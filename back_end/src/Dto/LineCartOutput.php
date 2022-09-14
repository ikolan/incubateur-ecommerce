<?php

namespace App\Dto;

use App\Entity\Product;
use App\Entity\User;

final class LineCartOutput
{
    /**
     * @var string
     */
    public $quantity;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var User
     */
    public $cartUser;
}
