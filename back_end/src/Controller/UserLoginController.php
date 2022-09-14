<?php

namespace App\Controller;

use App\Dto\UserLoginOutput;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for make a jwt from a `User`.
 */
class UserLoginController extends AbstractController
{
    public function __invoke(User $data, JWTTokenManagerInterface $jwt)
    {
        $output = new UserLoginOutput();
        $output->token = $jwt->create($data);

        return new JsonResponse($output);
    }
}
