<?php

namespace App\Controller;

use App\Dto\OrderPaymentLinkOutput;
use App\Entity\Order;
use App\Entity\User;
use App\Services\Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OrderCreationController extends AbstractController
{
    public function __invoke(Order $data, Stripe $stripe)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (true !== $user->getIsActivated()) {
            throw new UnauthorizedHttpException('', 'Not activated user');
        }

        if (null === $data->getOrderUser()) {
            $data->setOrderUser($user);
        }

        if (null === $user->getStripeId()) {
            $user = $stripe->registerUser($user);
        }

        foreach ($data->getOrderItems() as $item) {
            $this->em->persist($item);
        }

        [$data, $url] = $stripe->createSession($data, $user);
        $this->em->persist($data);
        $this->em->flush();

        $link = new OrderPaymentLinkOutput();
        $link->link = $url;

        return new JsonResponse($link);
    }
}
