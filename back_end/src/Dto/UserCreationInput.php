<?php

namespace App\Dto;

use App\Validator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Input DTO for the creation of Users.
 */
final class UserCreationInput
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     * @Validator\NotRegisteredEmail
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank
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
    public $password;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $lastName;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Date
     */
    public $birthDate;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^([+]?[\s0-9]+)?(\d{3}|[(]?[0-9]+[)])?([-]?[\s]?[0-9])+$/",
     *     message="This is not a phone number."
     * )
     */
    public $phone;
}
