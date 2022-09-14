<?php

namespace App\Services\Stripe;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Stripe\StripeClient;

class Stripe
{
    /** @var StripeClient|null */
    private $stripe;

    public function __construct()
    {
        $secretKey = $_ENV['STRIPE_SECRET_KEY'];
        $this->stripe = '' === $secretKey ? null : new StripeClient($secretKey);
    }

    public function registerUser(User $user): User
    {
        if (null === $this->stripe) {
            return $user;
        }

        $stripeCustomer = $this->stripe->customers->create([
            'name' => $user->getFirstName().' '.$user->getLastName(),
            'email' => $user->getEmail(),
        ]);

        $user->setStripeId($stripeCustomer->id);

        return $user;
    }

    public function createSession(Order $order, User $user): array
    {
        if (null === $this->stripe) {
            return [$order, ''];
        }

        $lineItems = array_map(function (OrderItem $item) {
            return [
                'price_data' => [
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                    ],
                    'unit_amount' => $item->getUnitPrice(),
                    'currency' => 'EUR',
                ],
                'quantity' => $item->getQuantity(),
            ];
        }, $order->getOrderItems()->toArray());

        $stripeSession = $this->stripe->checkout->sessions->create([
            'customer' => $user->getStripeId(),
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => str_replace('{orderReference}', $order->getOrderReference(), $_ENV['STRIPE_SUCCESS_URL']),
            'cancel_url' => str_replace('{orderReference}', $order->getOrderReference(), $_ENV['STRIPE_CANCEL_URL']),
        ]);

        $order->setStripeId($stripeSession->id);

        return [
            $order,
            $stripeSession->url,
        ];
    }

    public function sessionComplete(Order $order): bool
    {
        if (null === $this->stripe) {
            return false;
        }

        return 'complete' === $this->stripe->checkout->sessions->retrieve($order->getStripeId())->status;
    }

    public function getPaymentLink(Order $order)
    {
        if (null === $this->stripe) {
            return false;
        }

        return $this->stripe->checkout->sessions->retrieve($order->getStripeId())->url;
    }
}
