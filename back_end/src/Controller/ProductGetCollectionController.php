<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductGetCollectionController extends AbstractController
{
    public function __invoke(Request $request, ProductRepository $productRepository)
    {
        if ($request->query->get('frontProducts')) {
            return $productRepository->findBy(['frontPage' => true, 'isDeleted' => false], ['addedAt' => 'DESC'], 5);
        }
        $filters = [];
        foreach ($request->query as $key => $value) {
            if ('page' != $key && 'search' != $key && 'frontProducts' != $key) {
                $filters[$key] = $value;
            }
        }

        return $productRepository->findProducts($filters, $request->query->get('page') ?? 1, $request->query->get('search') ?? '');
    }
}
