<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RegisteredEmailValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->userRepository = $em->getRepository(User::class);
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof string && [] === $this->userRepository->findBy(['email' => $value])) {
            $this->context->buildViolation('The email address is not used.')->setCode('NotRegisteredEmail')->addViolation();
        }
    }
}
