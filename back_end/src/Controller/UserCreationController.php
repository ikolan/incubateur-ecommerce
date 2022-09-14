<?php

namespace App\Controller;

use App\Entity\User;
use App\Mailer\EmailManager;

class UserCreationController extends AbstractController
{
    public function __invoke(User $data, EmailManager $emailManager)
    {
        $emailManager->sendEmail($data->getEmail(), 'Bienvenue Chez Craft Computing', 'activation.html.twig', [
            'firstName' => $data->getFirstName(),
            'activationLink' => $emailManager->getActivationLinkFromUser($data),
        ]);

        return $data;
    }
}
