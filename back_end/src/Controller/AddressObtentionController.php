<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AddressObtentionController extends AbstractController
{
    public function __invoke(Address $data, Request $request)
    {
        /** @var User */
        $user = $this->getUser();

        if (!$user->hasRole(['ROLE_ADMIN']) && !$user->isLinkedToAddressId($request->get('id'))) {
            throw new UnauthorizedHttpException('', 'You cannot access to this address.');
        }

        return $data;
    }
}
