<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for address creation.
 */
class AddressCreationController extends AbstractController
{
    public function __invoke(Address $data, Request $request)
    {
        /** @var User */
        $user = $this->getUser();

        $clone = $this->addressRepository->identicalTo($data);
        if (null !== $clone) {
            $data = $clone;
        } else {
            $this->em->persist($data);
        }

        if (null === $user->getMainAddress()) {
            $user->setMainAddress($data);
        }

        $user->addAddress($data);
        $this->em->persist($user);
        $this->em->flush();

        return $data;
    }
}
