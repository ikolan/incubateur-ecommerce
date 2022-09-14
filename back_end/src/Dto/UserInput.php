<?php

namespace App\Dto;

use App\Validator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Input DTO for modify a user.
 */
final class UserInput
{
    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     */
    public $lastName;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Email
     * @Validator\NotRegisteredEmail
     */
    public $email;

    /**
     * @var string
     * @Assert\Date
     */
    public $birthDate;

    /**
     * @var ?string
     * @Assert\NotBlank(allowNull=true)
     */
    public $oldpassword;

    /**
     * @var ?string
     */
    public $passwordResetKey;

    /**
     * @var string
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(min=8)
     * @Assert\Regex(
     *     pattern="/^(.*[a-z].*)$/",
     *     message="This value has no lower case character."
     * )
     * @Assert\Regex(
     *     pattern="/^(.*[A-Z].*)$/",
     *     message="This value has no upper case character."
     * )
     * @Assert\Regex(
     *     pattern="/^(.*\d.*)$/",
     *     message="This value has no numeric character."
     * )
     * @Assert\Regex(
     *     pattern="/^(.*\W.*)$/",
     *     message="This value has no special character."
     * )
     */
    public $newpassword;
}
