<?php

namespace App\Controller;

use App\Repository\LineCartRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LineCartDeleteCollectionController extends AbstractController
{
    public function __invoke(Request $request, LineCartRepository $lineCartRepository, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findByIdOrEmail($request->query->get('email'));
        $lineCarts = $lineCartRepository->findBy(['cartUser' => $user->getId()]);

        foreach ($lineCarts as $lineCart) {
            $lineCartRepository->remove($lineCart, false);
        }

        $em->flush();
    }
}
