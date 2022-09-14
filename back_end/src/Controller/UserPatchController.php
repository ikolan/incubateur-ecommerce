<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\UserInput;
use App\Dto\UserLoginOutput;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserPatchController extends AbstractController
{
    /** @var JWTTokenManagerInterface|mixed */
    public $jwt;

    /** @var EntityManagerInterface|mixed */
    public $em;

    /** @var ValidatorInterface|mixed */
    public $validator;

    /** @var UserPasswordHasherInterface|mixed */
    public $hasher;

    public function __construct(ValidatorInterface $validator, UserPasswordHasherInterface $hasher, JWTTokenManagerInterface $jwt, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->hasher = $hasher;
        $this->jwt = $jwt;
        $this->em = $em;
    }

    public function __invoke($id, Request $request, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $data = $serializer->deserialize($request->getContent(), UserInput::class, 'jsonld');
        $user = $userRepository->findByIdOrEmail($id);

        if (!$user instanceof User) {
            throw new UnprocessableEntityHttpException('Aucun utilisateur retrouvé.');
        } else {
            //Validation
            $this->validator->validate($data);

            $this->setNewData($data, $user);

            $this->em->persist($user);
            $this->em->flush();
        }

        $output = new UserLoginOutput();
        $output->token = $this->jwt->create($user);

        return new JsonResponse($output);
    }

    public function setNewData($data, $user)
    {
        if ($data->lastName && strlen($data->lastName) > 0 && $user->getLastName() != $data->lastName) {
            $user->setLastName($data->lastName);
        }

        if ($data->firstName && strlen($data->firstName) > 0 && $user->getFirstName() != $data->firstName) {
            $user->setFirstName($data->firstName);
        }

        if ($data->birthDate && $user->getBirthDate() != $data->birthDate) {
            $user->setBirthDate(new \DateTime($data->birthDate));
        }

        if ($data->email && $user->getEmail() != $data->email) {
            $user->setEmail($data->email);
        }

        if (null !== $data->newpassword) {
            if (null !== $data->oldpassword) {
                if (!$this->hasher->isPasswordValid($user, $data->oldpassword)) {
                    throw new UnprocessableEntityHttpException("L'ancien mot de passe ne correspond pas.");
                } else {
                    $user->setPassword($this->hasher->hashPassword($user, $data->newpassword));
                }
            } elseif (null !== $data->passwordResetKey) {
                if ($user->getResetKey() !== $data->passwordResetKey) {
                    throw new UnprocessableEntityHttpException('Reset key is not valid.');
                } else {
                    $user->setPassword($this->hasher->hashPassword($user, $data->newpassword));
                    $user->setResetKey(null);
                }
            }
        }
    }
}
