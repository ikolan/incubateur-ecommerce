<?php

// src/DataTransformer/UserInputDataTransformerInitializer.php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\UserInput;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserInputDataTransformerInitializer implements DataTransformerInitializerInterface
{
    /** @var ValidatorInterface */
    public $validator;

    /** @var UserPasswordHasherInterface */
    public $hasher;

    /** @var UserRepository */
    public $userRepository;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $hasher)
    {
        $this->validator = $validator;
        $this->hasher = $hasher;
        $this->userRepository = $em->getRepository(User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $this->validator->validate($data);

        /** @var User */
        $existingUser = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if ($data->lastName && strlen($data->lastName) > 0 && $existingUser->getLastName() != $data->lastName) {
            $existingUser->setLastName($data->lastName);
        }

        if ($data->firstName && strlen($data->firstName) > 0 && $existingUser->getFirstName() != $data->firstName) {
            $existingUser->setFirstName($data->firstName);
        }

        if ($data->birthDate && $existingUser->getBirthDate() != $data->birthDate) {
            $existingUser->setBirthDate(new \DateTime($data->birthDate));
        }

        if ($data->email && $existingUser->getEmail() != $data->email) {
            if (null === $this->userRepository->findByEmail($data->email)) {
                $existingUser->setEmail($data->email);
            } else {
                throw new UnprocessableEntityHttpException('This email address is already use.');
            }
        }

        if (null !== $data->newpassword) {
            if (null !== $data->oldpassword) {
                if (!$this->hasher->isPasswordValid($existingUser, $data->oldpassword)) {
                    throw new UnprocessableEntityHttpException("L'ancien mot de passe ne correspond pas.");
                } else {
                    $existingUser->setPassword($this->hasher->hashPassword($existingUser, $data->newpassword));
                }
            }
        } elseif (null !== $data->passwordResetKey) {
            if ($existingUser->getResetKey() !== $data->passwordResetKey) {
                throw new UnprocessableEntityHttpException('Reset key is not valid.');
            } else {
                $existingUser->setPassword($this->hasher->hashPassword($existingUser, $data->newpassword));
            }
        }

        return $existingUser;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingUser = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingUser) {
            return new UserInput();
        }

        $input = new UserInput();
        $input->email = $existingUser->getEmail();
        $input->password = $existingUser->getPassword();
        $input->firstName = $existingUser->getFirstName();
        $input->lastName = $existingUser->getLastName();
        $input->birthDate = $existingUser->getBirthDate()->format('Y-m-d');

        return $input;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && 'UserInput' === $context['input']['name'];
    }
}
