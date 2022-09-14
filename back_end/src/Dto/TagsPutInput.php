<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class TagsPutInput
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $label;
}
