<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Transform UserCreationInput to a User.
 */
final class UserCreationInputDataTransformer implements DataTransformerInterface
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $hasher)
    {
        $this->roleRepository = $em->getRepository(Role::class);
        $this->validator = $validator;
        $this->hasher = $hasher;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $this->validator->validate($data);

        $user = new User();
        $user->setEmail($data->email);
        $user->setPassword($this->hasher->hashPassword($user, $data->password));
        $user->setFirstName($data->firstName);
        $user->setLastName($data->lastName);
        $user->setBirthDate(new \DateTime($data->birthDate));
        $user->setPhone($data->phone);
        $user->setIsActivated(false);
        $user->setActivationKey(Uuid::v4());
        $user->addRole($this->roleRepository->findBy(['label' => 'ROLE_USER'])[0]);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && 'UserCreationInput' === $context['input']['name'];
    }
}
