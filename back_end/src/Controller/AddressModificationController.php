<?php

namespace App\Controller;

use App\Dto\AddressInput;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AddressModificationController extends AbstractController
{
    public function __invoke(Address $data, Request $request)
    {
        /** @var User */
        $user = $this->getUser();

        /** @var AddressInput */
        $requestContent = $this->serializer->deserialize($request->getContent(), AddressInput::class, 'json');
        $requestedId = $request->get('id');

        if ($user->hasRole(['ROLE_ADMIN'])) {
            if (null !== $requestContent->asUser) {
                $user = $this->userRepository->findOneBy(['email' => $requestContent->asUser]);
            }
        } elseif (!$user->isLinkedToAddressId($requestedId)) {
            throw new UnauthorizedHttpException('', "You can't edit other's addresses.");
        }

        if (null === $data->getId()) {
            $user->removeAddress($this->addressRepository->find($requestedId));
            $user->addAddress($data);
        }

        return $data;
    }
}
