<?php

namespace App\Dto;

use App\Entity\Category;
use App\Entity\Tags;
use Symfony\Component\Validator\Constraints as Assert;

final class ProductCreationInput
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $reference;

    /**
     * @var int
     * @Assert\NotBlank
     */
    public $price;

    /**
     * @var int
     * @Assert\NotBlank
     */
    public $tax;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $description;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $detailedDescription;

    /**
     * @var int
     * @Assert\NotBlank
     */
    public $weight;

    /**
     * @var int
     * @Assert\NotBlank
     */
    public $stock;

    /**
     * @var bool
     */
    public $frontPage = false;

    /**
     * @var Category
     * @Assert\NotBlank
     */
    public $category;

    /**
     * @var List<int,Tags>
     * @Assert\NotBlank(allowNull=true)
     */
    public $tags;
}
