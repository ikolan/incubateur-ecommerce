<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserGetByResetKeyController extends AbstractController
{
    public function __invoke($resetKey)
    {
        $user = $this->userRepository->findByPasswordResetKey($resetKey);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }
}
