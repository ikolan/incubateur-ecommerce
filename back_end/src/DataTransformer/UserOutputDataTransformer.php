<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\AddressOutput;
use App\Dto\OrderOutput;
use App\Dto\UserOutput;
use App\Entity\User;

/**
 * Transform User to UserOutput.
 */
final class UserOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new UserOutput();
        $output->email = $data->getEmail();
        $output->firstName = $data->getFirstName();
        $output->lastName = $data->getLastName();
        $output->phone = $data->getPhone();
        $output->birthDate = $data->getbirthDate();
        $output->mainAddress = $data->getMainAddress();
        $output->addresses = array_map(function ($address) {
            return AddressOutput::fromAddress($address);
        }, $data->getAddresses()->toArray());

        $output->orders = array_map(function ($order) {
            $output = new OrderOutput();

            $output->price = $order->getPrice();
            $output->isReturned = $order->getIsReturned();
            $output->orderReference = $order->getOrderReference();
            $output->stripeId = $order->getStripeId();
            $output->createdAt = $order->getCreatedAt();
            $output->status = $order->getStatus();
            $output->address = $order->getAddress();
            $output->orderItems = $order->getOrderItems();

            return $output;
        }, $data->getOrders()->toArray());

        $output->isActivated = $data->getIsActivated();
        if (false == $data->getIsActivated()) {
            $output->activationKey = $data->getActivationKey();
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserOutput::class === $to && $data instanceof User;
    }
}
