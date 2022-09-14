<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\ProductInput;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;

final class ProductInputDataTransformerInitializer implements DataTransformerInterface
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
            $existingProduct = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
            $existingProduct->setName($data->name);
            $existingProduct->setReference($data->reference);
            $existingProduct->setPrice($data->price);
            $existingProduct->setTax($data->tax);
            $existingProduct->setDescription($data->description);
            $existingProduct->setDetailedDescription($data->detailedDescription);
            $existingProduct->setWeight($data->weight);
            $existingProduct->setStock($data->stock);
            $existingProduct->setFrontPage($data->frontPage);
            $existingProduct->setCategory($data->category);
            $existingProduct->setAddedAt(new \DateTimeImmutable('now'));
            if ($data->tags) {
                foreach ($data->tags as $key => $value) {
                    $existingProduct->addTag($this->tagsRepository->find(($value)));
                }
            }
            $existingProduct->setImage($data->image);

            return $existingProduct;
        } else {
            $product = new Product();
            $product->setName($data->name);
            $product->setReference($data->reference);
            $product->setPrice($data->price);
            $product->setTax($data->tax);
            $product->setDescription($data->description);
            $product->setDetailedDescription($data->detailedDescription);
            $product->setWeight($data->weight);
            $product->setStock($data->stock);
            $product->setFrontPage($data->frontPage);
            $product->setCategory($data->category);
            $product->setAddedAt(new \DateTimeImmutable('now'));
            if ($data->tags) {
                foreach ($data->tags as $key => $value) {
                    $product->addTag($this->tagsRepository->find(($value)));
                }
            }
            $product->setImage($data->image);

            return $product;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingProduct = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingProduct) {
            return new ProductInput();
        }

        $input = new ProductInput();
        $input->name = $existingProduct->getName();
        $input->reference = $existingProduct->getReference();
        $input->price = $existingProduct->getPrice();
        $input->tax = $existingProduct->getTax();
        $input->description = $existingProduct->getDescription();
        $input->detailedDescription = $existingProduct->getdetailedDescription();
        $input->weight = $existingProduct->getWeight();
        $input->stock = $existingProduct->getStock();
        $input->frontPage = $existingProduct->getFrontPage();
        $input->category = $existingProduct->getCategory();
        $input->tags = $existingProduct->getTags();
        $input->image = $existingProduct->getMainImage();

        return $input;
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

        return Product::class === $to && 'ProductInput' == ($context['input']['class'] ?? null);
    }
}
