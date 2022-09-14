<?php

namespace App\Dto;

use App\Validator;
use Symfony\Component\Validator\Constraints as Assert;

final class ImageInput
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Validator\NotAlreadyExistImageName
     */
    public $name;
}
