<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;

class MainAddressSetterController extends AbstractController
{
    public function __invoke(Address $data)
    {
        /** @var User */
        $user = $this->getUser();
        $user->setMainAddress($data);

        return $data;
    }
}
