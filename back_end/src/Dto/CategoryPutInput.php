<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CategoryPutInput
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $label;
}
