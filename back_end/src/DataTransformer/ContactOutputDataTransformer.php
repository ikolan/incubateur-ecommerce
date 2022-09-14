<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ContactOutput;
use App\Entity\Contact;

class ContactOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        return ContactOutput::fromContact($object);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ContactOutput::class === $to && $data instanceof Contact;
    }
}
