<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Uid\Uuid;

class UserDeactivationController extends AbstractController
{
    public function __invoke($id, Request $request, UserRepository $userRepository)
    {
        $user = $userRepository->findByIdOrEmail($id);
        if (!$user) {
            throw new UnprocessableEntityHttpException('Aucun utilisateur retrouvé.');
        } else {
            $user->setActivationKey(Uuid::v4());
            $user->setIsActivated(false);

            $this->em->persist($user);
            $this->em->flush();
        }
    }
}
