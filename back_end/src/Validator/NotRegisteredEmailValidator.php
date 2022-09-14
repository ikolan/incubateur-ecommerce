<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotRegisteredEmailValidator extends ConstraintValidator
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
        if ([] !== $this->userRepository->findBy(['email' => $value])) {
            $this->context->buildViolation('The email address is already use.')->setCode('AlreadyRegisteredEmail')->addViolation();
        }
    }
}
