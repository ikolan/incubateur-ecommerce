<?php

// src/DataTransformer/ProductOutputDataTransformer.php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ProductOutput;
use App\Entity\Product;

final class ProductOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new ProductOutput();
        $output->id = $data->getId();
        $output->name = $data->getName();
        $output->reference = $data->getReference();
        $output->price = $data->getPrice();
        $output->tax = $data->getTax();
        $output->description = $data->getDescription();
        $output->detailedDescription = $data->getDetailedDescription();
        $output->weight = $data->getWeight();
        $output->stock = $data->getStock();
        $output->frontPage = $data->getFrontPage();
        $output->category = $data->getCategory();
        $output->tags = $data->getTags();
        $output->image = $data->getImage();
        $output->isDeleted = $data->isDeleted();

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ProductOutput::class === $to && $data instanceof Product;
    }
}
