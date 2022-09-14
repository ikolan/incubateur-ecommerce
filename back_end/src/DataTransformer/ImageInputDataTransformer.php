<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ImageInput;
use App\Entity\Image;

class ImageInputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        $image = new Image();
        $image->setName($object->name);

        return $image;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Image) {
            return false;
        }

        return Image::class === $to && ImageInput::class === $context['input']['class'];
    }
}
