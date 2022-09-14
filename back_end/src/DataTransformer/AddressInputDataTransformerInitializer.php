<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\AddressInput;
use App\Entity\Address;

final class AddressInputDataTransformerInitializer implements DataTransformerInitializerInterface
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

        /** @var Address */
        $address = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];

        if ($address->dependOnSomething(1)) {
            $address = $address->newCopy();
        }

        return $object->toAddress($address);
    }

    public function initialize(string $inputClass, array $context = [])
    {
        $existingAddress = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingAddress) {
            return new AddressInput();
        }

        return AddressInput::fromAddress($existingAddress);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Address) {
            return false;
        }

        return Address::class === $to && AddressInput::class === $context['input']['class'];
    }
}
