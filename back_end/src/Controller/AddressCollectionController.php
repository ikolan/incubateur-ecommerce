<?php

namespace App\Controller;

use App\Entity\User;

/**
 * Controller for getting addresses.
 */
class AddressCollectionController extends AbstractController
{
    public function __invoke()
    {
        /** @var User */
        $user = $this->getUser();

        return $user->getAddresses();
    }
}
