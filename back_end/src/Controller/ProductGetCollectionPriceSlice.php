<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ProductGetCollectionPriceSlice extends AbstractController
{
    public function __invoke()
    {
        return new JsonResponse($this->productRepository->getPriceSlice());
    }
}
