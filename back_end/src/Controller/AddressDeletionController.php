<?php

namespace App\Controller;

use App\Dto\AddressDeletionInput;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AddressDeletionController extends AbstractController
{
    public function __invoke(Address $data, Request $request)
    {
        /** @var User */
        $user = $this->getUser();

        $requestRawContent = '' === $request->getContent() ? '{}' : $request->getContent();
        /** @var AddressInput */
        $requestContent = $this->serializer->deserialize($requestRawContent, AddressDeletionInput::class, 'json');
        $requestedId = $request->get('id');

        if ($user->hasRole(['ROLE_ADMIN'])) {
            if (null !== $requestContent->asUser) {
                $user = $this->userRepository->findOneBy(['email' => $requestContent->asUser]);
            }
        } elseif (!$user->isLinkedToAddressId($requestedId)) {
            throw new UnauthorizedHttpException('', "You can't delete other's addresses.");
        }

        if ($data === $user->getMainAddress()) {
            $user->setMainAddress(null);
        }

        $user->removeAddress($data);
        $this->em->flush();
    }
}
