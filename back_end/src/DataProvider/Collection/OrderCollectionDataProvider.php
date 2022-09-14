<?php

namespace App\DataProvider\Collection;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Data provider for get a product from its ID or Reference.
 */
class OrderCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var Request */
    private $request;

    /** @var OrderRepository */
    private $orderRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->orderRepository = $managerRegistry->getManagerForClass(Order::class)->getRepository(Order::class);
        $this->userRepository = $managerRegistry->getManagerForClass(User::class)->getRepository(User::class);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Order::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, ?string $operationName = null)
    {
        $user = $this->userRepository->findByIdOrEmail($this->request->query->get('email'));

        if ($user->hasRole(['ROLE_SELLER']) && $this->request->query->get('own') != "true") {
            return $this->orderRepository->findBy([],['createdAt' => 'DESC']);
        }

        return $this->orderRepository->findBy(['orderUser' => $user->getId()], ['createdAt' => 'DESC']);
    }
}
