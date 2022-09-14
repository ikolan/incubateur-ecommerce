<?php

namespace App\DataProvider\Item;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Data provider for get a Order from its ID or Reference.
 */
class OrderItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var Request */
    private $request;

    /** @var OrderRepository */
    private $orderRepository;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->orderRepository = $managerRegistry->getManagerForClass(Order::class)->getRepository(Order::class);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Order::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $orderReference, string $operationName = null, array $context = [])
    {
        return $this->orderRepository->findOneBy(['orderReference' => $orderReference]);
    }
}
