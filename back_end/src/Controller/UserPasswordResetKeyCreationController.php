<?php

namespace App\Controller;

use App\Entity\User;
use App\Mailer\EmailManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class UserPasswordResetKeyCreationController extends AbstractController
{
    /**
     * @param int|string $id
     */
    public function __invoke($id, EmailManager $emailManager)
    {
        $user = $this->userRepository->findByIdOrEmail($id);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found.');
        }

        $user->setResetKey(Uuid::v4());
        $this->em->flush();

        $emailManager->sendEmail($user->getEmail(), 'Restauration de votre mot de passe', 'forgotPassword.html.twig', [
            'firstName' => $user->getFirstName(),
            'resetLink' => $emailManager->getForgotPasswordLinkFromUser($user),
        ]);
    }
}
