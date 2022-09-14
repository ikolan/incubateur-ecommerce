<?php

namespace App\Dto;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Tags;

final class ProductOutput
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var int
     */
    public $price;

    /**
     * @var int
     */
    public $tax;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $detailedDescription;

    /**
     * @var int
     */
    public $weight;

    /**
     * @var int
     */
    public $stock;

    /**
     * @var bool
     */
    public $frontPage = false;

    /**
     * @var Category
     */
    public $category;

    /**
     * @var Tags
     */
    public $tags;

    /**
     * @var Image
     */
    public $image;

    /**
     * @var bool
     */
    public $isDeleted;
}
