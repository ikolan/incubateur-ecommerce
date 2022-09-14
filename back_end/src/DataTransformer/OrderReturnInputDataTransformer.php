<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\OrderReturn;

class OrderReturnInputDataTransformer implements DataTransformerInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        $orderReturn = new OrderReturn();
        $orderReturn->setReason($object->reason);
        $orderReturn->setDescription($object->description);
        $orderReturn->setCreatedAt(new \DateTimeImmutable());

        return $orderReturn;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof OrderReturn) {
            return false;
        }

        return OrderReturn::class === $to && 'OrderReturnInput' == $context['input']['name'];
    }
}
