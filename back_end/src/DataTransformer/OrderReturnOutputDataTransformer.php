<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\OrderReturnOutput;
use App\Entity\OrderReturn;

class OrderReturnOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        $output = new OrderReturnOutput();
        $output->id = $object->getId();
        $output->reason = $object->getReason();
        $output->description = $object->getDescription();
        $output->createdAt = $object->getCreatedAt();
        $output->order = $object->getOrderR();

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return OrderReturnOutput::class === $to && $data instanceof OrderReturn;
    }
}
