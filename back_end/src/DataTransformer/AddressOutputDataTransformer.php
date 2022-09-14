<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\AddressOutput;
use App\Entity\Address;

final class AddressOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        return AddressOutput::fromAddress($object);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return AddressOutput::class === $to && $data instanceof Address;
    }
}
