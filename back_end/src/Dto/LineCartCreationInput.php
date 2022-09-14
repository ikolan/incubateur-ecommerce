<?php

namespace App\Dto;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

final class LineCartCreationInput
{
    /**
     * @var int
     * @Assert\NotBlank
     */
    public $quantity;

    /**
     * @var Product
     * @Assert\NotBlank
     */
    public $product;

    /**
     * @var User
     * @Assert\NotBlank
     */
    public $cartUser;
}
