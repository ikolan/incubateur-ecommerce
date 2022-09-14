<?php

namespace App\DataTransformer;

use App\Entity\Order;
use App\Dto\OrderOutput;
use App\Dto\ProductOutput;
use App\Dto\OrderItem\OrderItemOutput;
use App\DataTransformer\ProductOutputDataTransformer;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;

class OrderOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): OrderOutput
    {
        $orderOutput = new OrderOutput();
        $orderOutput->price = $object->getPrice();
        $orderOutput->isReturned = $object->getIsReturned();
        $orderOutput->orderReference = $object->getOrderReference();
        $orderOutput->orderUser = $object->getOrderUser();
        $orderOutput->createdAt = $object->getCreatedAt();
        $orderOutput->status = $object->getStatus();
        $orderOutput->address = $object->getAddress();
        $orderOutput->orderItems = array_map(function ($orderItem) {
            $output = new OrderItemOutput();
            $output->quantity = $orderItem->getQuantity();
            $output->unitPrice = $orderItem->getUnitPrice();
            $output->product = (new ProductOutputDataTransformer())->transform($orderItem->getProduct(), ProductOutput::class, []);

            return $output;
        }, $object->getOrderItems()->toArray());

        return $orderOutput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return OrderOutput::class === $to && $data instanceof Order;
    }
}
