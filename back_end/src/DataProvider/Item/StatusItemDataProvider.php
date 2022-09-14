<?php

namespace App\DataProvider\Item;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Data provider for get a Status from its ID or Reference.
 */
class StatusItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var Request */
    private $request;

    /** @var StatusRepository */
    private $StatusRepository;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->StatusRepository = $managerRegistry->getManagerForClass(Status::class)->getRepository(Status::class);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Status::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $status, string $operationName = null, array $context = [])
    {
        return $this->StatusRepository->findOneBy(['label' => $status]) ?? $this->StatusRepository->findOneBy(['id' => $status]);
    }
}
