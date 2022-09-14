<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Contact;

class ContactCreationInputDataTransformer implements DataTransformerInterface
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

        return $object->toContact();
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Contact) {
            return false;
        }

        return Contact::class === $to && 'ContactCreationInput' === $context['input']['name'];
    }
}
