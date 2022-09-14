<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ImageOutput;
use App\Entity\Image;

class ImageOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): ImageOutput
    {
        $imageOutput = new ImageOutput();
        $imageOutput->id = $object->getId();
        $imageOutput->name = $object->getName();

        return $imageOutput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ImageOutput::class === $to && $data instanceof Image;
    }
}
