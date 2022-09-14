<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Role;
use App\Mailer\EmailManager;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class ContactCreationController extends AbstractController
{
    public function __invoke(Contact $data, EmailManager $emailManager)
    {
        $role = $this->roleRepository->findOneBy([
            'label' => 'ROLE_SELLER',
        ]);

        if (!$role instanceof Role) {
            throw new InternalErrorException('Role not found.');
        }

        foreach ($role->getUsers() as $user) {
            $emailManager->sendEmail($user->getEmail(), '[Craft Computing] '.$data->getSubject(), 'contact.html.twig', [
                'firstName' => $data->getFirstName(),
                'lastName' => $data->getLastName(),
                'senderEmail' => $data->getEmail(),
                'subject' => $data->getSubject(),
                'message' => $data->getMessage(),
                'date' => $data->getSentAt()->format('Y/m/d H:i:s'),
            ]);
        }

        return $data;
    }
}
