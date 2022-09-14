<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Tags;

final class TagsInputDataTransformer implements DataTransformerInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $this->validator->validate($data);

        if (isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $existingTag = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
            $existingTag->setLabel($data->label);

            return $existingTag;
        } else {
            $tag = new Tags();
            $tag->setLabel($data->label);

            return $tag;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Tags) {
            return false;
        }

        return Tags::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
