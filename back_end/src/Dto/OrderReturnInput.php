<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderReturnInput
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $reason;

    /**
     * @var string
     */
    public $description;
}
