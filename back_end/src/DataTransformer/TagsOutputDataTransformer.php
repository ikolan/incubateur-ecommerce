<?php

// src/DataTransformer/BookOutputDataTransformer.php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\TagsOutput;
use App\Entity\Tags;

final class TagsOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new TagsOutput();
        $output->id = $data->getId();
        $output->label = $data->getLabel();

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return TagsOutput::class === $to && $data instanceof Tags;
    }
}
