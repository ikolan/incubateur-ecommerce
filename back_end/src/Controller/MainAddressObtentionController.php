<?php

namespace App\Controller;

use App\Entity\User;

class MainAddressObtentionController extends AbstractController
{
    public function __invoke()
    {
        /** @var User */
        $user = $this->getUser();

        return $user->getMainAddress();
    }
}
