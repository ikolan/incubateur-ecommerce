<?php

namespace App\Controller;

use App\Entity\User;
use App\Mailer\EmailManager;

/**
 * Controller for reactivate a user.
 */
class UserReactivationRequestController extends AbstractController
{
    public function __invoke($id, EmailManager $emailManager)
    {
        /** @var User */
        $user = $this->userRepository->findByIdOrEmail($id);

        $emailManager->sendEmail($user->getEmail(), 'Réactivation de votre compte', 'reactivation.html.twig', [
            'firstName' => $user->getFirstName(),
            'activationLink' => $emailManager->getActivationLinkFromUser($user),
        ]);

        return $user;
    }
}
