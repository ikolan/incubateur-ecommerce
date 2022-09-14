<?php

namespace App\DataProvider\Item;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Data Provider for get a user from its ID or its email encoded in base64.
 */
class UserItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var UserRepository */
    private $userRepository;

    /** @var Request */
    private $request;

    public function __construct(ManagerRegistry $registry, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->userRepository = $registry->getManagerForClass(User::class)->getRepository(User::class);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->userRepository->findByIdOrEmail(0 === $id ? $this->request->get('id') : $id);
    }
}
