<?php

namespace App\Controller;

use App\Entity\Order;
use App\Mailer\EmailManager;

class AdminOrderStatusController extends AbstractController
{
    public function __invoke(Order $data, EmailManager $emailManager)
    {
        $emailManager->sendEmail($data->getOrderUser()->getEmail(), 'Changement du statut d\'une commande', 'OrderStatusNotification.html.twig', [
            'order' => $data,
        ]);

        return $data;
    }
}
