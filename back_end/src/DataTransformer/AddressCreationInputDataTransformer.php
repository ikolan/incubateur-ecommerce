<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\AddressCreationInput;
use App\Entity\Address;

final class AddressCreationInputDataTransformer implements DataTransformerInterface
{
    /** @var ValidatorInterface */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        return $object->toAddress();
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Address) {
            return false;
        }

        return Address::class === $to && AddressCreationInput::class === $context['input']['class'];
    }
}
