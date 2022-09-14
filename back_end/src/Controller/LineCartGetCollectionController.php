<?php

namespace App\Controller;

use App\DataTransformer\ProductOutputDataTransformer;
use App\Dto\ProductOutput;
use App\Repository\LineCartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LineCartGetCollectionController extends AbstractController
{
    public function __invoke(Request $request, LineCartRepository $lineCartRepository, UserRepository $userRepository, ProductRepository $productRepository)
    {
        $user = $userRepository->findByIdOrEmail($request->query->get('email'));
        $lineCarts = $lineCartRepository->findBy(['cartUser' => $user->getId()]);
        $response = [];
        foreach ($lineCarts as $lineCart) {
            $response[] = [
                'lineCart' => $lineCart,
                'product' => (new ProductOutputDataTransformer())->transform($lineCart->getProduct(), ProductOutput::class, []),
            ];
        }

        return $response;
    }
}
