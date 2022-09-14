<?php

namespace App\Controller;

use App\Dto\UserActivationInput;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Controller for activate a user.
 */
class UserActivationController extends AbstractController
{
    public function __invoke(Request $request, SerializerInterface $serializer)
    {
        $user = null;
        try {
            $key = $serializer->deserialize($request->getContent(), UserActivationInput::class, 'json')->activationKey;
            $user = $this->userRepository->findOneBy(['activationKey' => $key]);

            if (!$user instanceof User) {
                throw new \Exception();
            }
        } catch (\Exception $exception) {
            throw new UnprocessableEntityHttpException('The activation key is not valid.');
        }

        $user->setIsActivated(true);
        $user->setActivationKey(null);

        return $user;
    }
}
