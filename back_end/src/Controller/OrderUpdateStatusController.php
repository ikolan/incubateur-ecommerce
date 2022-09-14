<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Stripe\Stripe;

class OrderUpdateStatusController extends AbstractController
{
    public function __invoke(Order $data, Stripe $stripe)
    {
        if ($stripe->sessionComplete($data)) {
            $data->setStatus($this->statusRepository->findOneBy(['label' => 'Payé']));
        }

        return $data;
    }
}
