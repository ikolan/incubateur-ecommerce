<?php

namespace App\Validator;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotAlreadyExistImageNameValidator extends ConstraintValidator
{
    /** @var ImageRepository */
    private $imageRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->imageRepository = $em->getRepository(Image::class);
    }

    public function validate($value, Constraint $constraint)
    {
        if ([] !== $this->imageRepository->findBy(['name' => $value])) {
            $this->context->buildViolation('This name is already use.')->setCode('AlreadyUseImageName')->addViolation();
        }
    }
}
