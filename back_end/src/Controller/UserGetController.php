<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserGetController extends AbstractController
{
    public function __invoke($id, UserRepository $userRepository)
    {
        $user = $userRepository->findByIdOrEmail($id);

        if (!$user) {
            throw new UnprocessableEntityHttpException('Aucun utilisateur retrouvé.');
        }

        return $user;
    }
}
