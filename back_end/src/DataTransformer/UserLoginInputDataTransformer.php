<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserLoginInputDataTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->userRepository = $em->getRepository(User::class);
        $this->hasher = $hasher;
    }

    public function transform($data, string $to, array $context = [])
    {
        /**
         * @var User|null
         */
        $user = $this->userRepository->findOneBy(['email' => $data->email]);

        if (null == $user) {
            throw new UnprocessableEntityHttpException('This email is not registered or the account is not activated.');
        }

        if (!$this->hasher->isPasswordValid($user, $data->password)) {
            throw new UnprocessableEntityHttpException('The password is invalid.');
        }

        return $user;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && 'UserLoginInput' === $context['input']['name'];
    }
}
