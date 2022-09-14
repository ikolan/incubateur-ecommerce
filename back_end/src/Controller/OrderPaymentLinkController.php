<?php

namespace App\Controller;

use App\Dto\OrderPaymentLinkOutput;
use App\Entity\Order;
use App\Services\Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderPaymentLinkController extends AbstractController
{
    public function __invoke(Order $data, Stripe $stripe)
    {
        $link = new OrderPaymentLinkOutput();
        $link->link = $stripe->getPaymentLink($data);

        return new JsonResponse($link);
    }
}
