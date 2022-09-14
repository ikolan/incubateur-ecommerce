<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;

final class ProductCreationInputDataTransformer implements DataTransformerInterface
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    public $categoryRepository;

    /**
     * @var \Doctrine\ORM\EntityRepository|mixed
     */
    public $tagsRepository;

    private $validator;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->categoryRepository = $em->getRepository(Category::class);
        $this->tagsRepository = $em->getRepository(Tags::class);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $this->validator->validate($data);

        if (isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $product = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        } else {
            $product = new Product();
        }

        $product->setName($data->name);
        $product->setReference($data->reference);
        $product->setPrice($data->price);
        $product->setTax($data->tax);
        $product->setDescription($data->description);
        $product->setDetailedDescription($data->detailedDescription);
        $product->setWeight($data->weight);
        $product->setStock($data->stock);
        $product->setFrontPage($data->frontPage);
        $product->setAddedAt(new \DateTimeImmutable('now'));

        $product->setCategory($this->categoryRepository->findOneBy(['id' => $data->category]));

        if ($data->tags) {
            foreach ($data->tags as $value) {
                $product->addTag($this->tagsRepository->findOneBy(['id' => $value]));
            }
        }

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Product) {
            return false;
        }

        return Product::class === $to && 'ProductCreationInput' == $context['input']['name'];
    }
}
