<?php

namespace App\DataProvider\Item;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Data provider for get a product from its ID or Reference.
 */
class ProductItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var Request */
    private $request;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->productRepository = $managerRegistry->getManagerForClass(Product::class)->getRepository(Product::class);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Product::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->productRepository->findByIdOrReference(0 === $id ? $this->request->get('id') : $id);
    }
}
