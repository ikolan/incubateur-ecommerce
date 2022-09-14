<?php

namespace App\Controller;

use App\Entity\LineCart;
use App\Repository\LineCartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LineCartCreationController extends AbstractController
{
    public function __invoke(Request $request, LineCartRepository $lineCartRepository, UserRepository $userRepository, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $inputs = json_decode($request->getContent(), true);
        $product = $productRepository->findByIdOrReference($inputs['product']);
        $user = $userRepository->findByIdOrEmail($inputs['cartUser']);
        $lineCart = $lineCartRepository->findCartByUserAndProduct($product, $user)[0] ?? null;
        if (!$lineCart) {
            $lineCart = new LineCart();
            $lineCart->setQuantity($inputs['quantity']);
            $lineCart->setProduct($product);
            $lineCart->setCartUser($user);
        } else {
            $quantity = ($inputs['add'] ? $lineCart->getQuantity() + $inputs['quantity'] : $inputs['quantity']);
            $lineCart->setQuantity($quantity);
        }

        $em->persist($lineCart);
        $em->flush();

        return $lineCart;
    }
}
