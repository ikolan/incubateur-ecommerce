<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Dto\OrderInput;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Status;
use App\Repository\StatusRepository;
use App\Services\Stripe\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class OrderInputDataTransformerInitializer implements DataTransformerInitializerInterface
{
    /** @var StatusRepository */
    private $statusRepository;

    public function __construct(EntityManagerInterface $em, Stripe $stripe)
    {
        $this->statusRepository = $em->getRepository(Status::class);
        $this->stripe = $stripe;
    }

    public function transform($object, string $to, array $context = [])
    {
        if (isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $order = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
            $order->setStatus($object->status);
        } else {
            $order = new Order();
            $order->setPrice($object->price);
            $order->setIsReturned($object->isReturned);
            $order->setStatus(null === $object->status ? $this->statusRepository->findOneBy(['label' => 'Non payé']) : $object->status);
            $order->setAddress($object->address);
            $order->setOrderUser($object->user);
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setOrderReference(preg_replace('/-/', '', Uuid::v4()));

            foreach ($object->items as $item) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($item->product);
                $orderItem->setQuantity($item->quantity);
                $orderItem->setUnitPrice($item->unitPrice);
                $order->addOrderItem($orderItem);
            }
        }

        return $order;
    }

    public function initialize(string $inputClass, array $context = [])
    {
        $existingOrder = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;

        if (!$existingOrder) {
            return new OrderInput();
        }

        $orderInput = new OrderInput();
        $orderInput->price = $existingOrder->getPrice();
        $orderInput->isReturned = $existingOrder->getIsReturned();
        $orderInput->status = $existingOrder->getStatus();
        $orderInput->address = $existingOrder->getAddress();
        $orderInput->user = $existingOrder->getOrderUser();
        $orderInput->items = [];

        foreach ($existingOrder->getOrderItems()->toArray() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($item->getProduct());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setUnitPrice($item->getUnitPrice());
            $orderInput->items[] = $orderItem;
        }

        return $orderInput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Order) {
            return false;
        }

        return Order::class === $to && OrderInput::class == $context['input']['class'];
    }
}
