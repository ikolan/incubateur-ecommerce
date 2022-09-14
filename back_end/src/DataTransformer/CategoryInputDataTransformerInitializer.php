<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\CategoryInput;
use App\Entity\Category;

final class CategoryInputDataTransformerInitializer implements DataTransformerInterface
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
            $existingCategory = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
            $existingCategory->setLabel($data->label);

            return $existingCategory;
        } else {
            $category = new Category();
            $category->setLabel($data->label);

            return $category;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingCategory = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingCategory) {
            return new CategoryInput();
        }

        $input = new CategoryInput();
        $input->label = $existingCategory->getLabel();

        return $input;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Category) {
            return false;
        }

        return Category::class === $to && 'CategoryInput' == ($context['input']['class'] ?? null);
    }
}
