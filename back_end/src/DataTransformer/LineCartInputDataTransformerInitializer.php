<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\LineCartInput;
use App\Entity\Category;
use App\Entity\LineCart;
use App\Entity\Tags;
use App\Repository\LineCartRepository;
use Doctrine\ORM\EntityManagerInterface;

final class LineCartInputDataTransformerInitializer implements DataTransformerInterface
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

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em, LineCartRepository $lineCartRepository)
    {
        $this->validator = $validator;
        $this->categoryRepository = $em->getRepository(Category::class);
        $this->tagsRepository = $em->getRepository(Tags::class);
        $this->lineCartRepository = $lineCartRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $this->validator->validate($data);
        // $existingLineCart = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? $this->lineCartRepository->findCartByUserAndProduct($data->product, $data->cartUser);

        if (isset($data->product) && isset($data->cartUser)) {
            $existingLineCart = $this->lineCartRepository->findCartByUserAndProduct($data->product, $data->cartUser)[0] ?? null;
        } else {
            $existingLineCart = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        }

        if ($existingLineCart) {
            $existingLineCart->setQuantity($data->quantity);

            return $existingLineCart;
        } else {
            $LineCart = new LineCart();
            $LineCart->setQuantity($data->quantity);
            $LineCart->setProduct($data->product);
            $LineCart->setCartUser($data->cartUser);

            return $LineCart;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        $existingLineCart = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingLineCart) {
            return new LineCartInput();
        }

        $input = new LineCartInput();
        $input->name = $existingLineCart->getQuantity();
        $input->reference = $existingLineCart->getProduct();
        $input->price = $existingLineCart->getCartUser();

        return $input;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof LineCart) {
            return false;
        }

        return LineCart::class === $to && ('LineCartCreationInput' === $context['input']['name'] || 'LineCartInput' === $context['input']['name']);
    }
}
