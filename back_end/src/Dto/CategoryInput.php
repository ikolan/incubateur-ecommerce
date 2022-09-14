<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CategoryInput
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $label;
}
