<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\LineCartRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var LineCartRepository
     */
    private $lineCartRepository;

    /**
     * @var StatusRepository
     */
    private $statusRepository;

    public function __construct(UserRepository $userRepository, LineCartRepository $lineCartRepository, StatusRepository $statusRepository)
    {
        $this->userRepository = $userRepository;
        $this->lineCartRepository = $lineCartRepository;
        $this->statusRepository = $statusRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            if (null !== $user->getMainAddress() || count($user->getAddresses()) > 0) {
                $user_address = $user->getMainAddress() ?? $user->getAddresses()[0];
                $user_cart = $this->lineCartRepository->findBy(['cartUser' => $user->getId()]);

                $order = new Order();
                $totalPrice = 0;
                if (count($user_cart) > 0) {
                    foreach ($user_cart as $cart) {
                        $orderItem = new OrderItem();
                        $orderItem->setQuantity($cart->getQuantity());
                        $orderItem->setUnitPrice($cart->getQuantity() * $cart->getProduct()->getPrice());
                        $orderItem->setProduct($cart->getProduct());
                        $order->addOrderItem($orderItem);

                        $totalPrice += $cart->getQuantity() * $cart->getProduct()->getPrice();

                        $manager->persist($orderItem);
                    }
                }
                $order->setPrice($totalPrice);
                $order->setIsReturned(false);
                $order->setOrderReference($this->generateOrderRef());
                $order->setCreatedAt(new \DateTimeImmutable('now'));
                $order->setStatus($this->statusRepository->findAll()[0]);
                $user_address->addOrder($order);
                $user->addOrder($order);

                $manager->persist($order);
            }
        }

        // for ($i=0; $i < 10; $i++) {
        //     $randomInt = random_int($users[0]->getId(), $users[count($users) - 1]->getId());
        //     $user = $this->userRepository->find($randomInt);
        //     $user_cart = $this->lineCartRepository->findBy(array('cartUser' => $user->getId()));

        //     $order = new Order();
        //     $totalPrice = 0;
        //     if (count($user_cart) > 0) {
        //         foreach ($user_cart as $cart) {
        //             $orderItem = new OrderItem();
        //             $orderItem->setQuantity($cart->getQuantity());
        //             $orderItem->setUnitPrice($cart->getQuantity() * $cart->getProduct()->getPrice());
        //             $orderItem->setProduct($cart->getProduct());
        //             $order->addOrderItem($orderItem);

        //             $totalPrice += $cart->getQuantity() * $cart->getProduct()->getPrice();

        //             $manager->persist($orderItem);
        //         }
        //     }
        //     $order->setPrice($totalPrice);
        //     $order->setIsReturned(false);
        //     $order->setOrderReference($this->generateOrderRef());
        //     $order->setCreatedAt(new \DateTimeImmutable('now'));
        //     $order->setStatus($this->statusRepository->findAll()[0]);
        //     if ($user->getMainAddress()) {
        //         $user->getMainAddress()->addOrder($order);
        //     } else {
        //         $user_addresses = $user->getAddresses();
        //         dd($user_addresses[0]);
        //         $user_addresses[random_int(0, count($user_addresses) - 1)]->addOrder($order);
        //     }

        //     $manager->persist($order);
        // }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['Ordertest'];
    }

    public function getDependencies()
    {
        return [StatusFixtures::class, UserFixtures::class, LineCartFixtures::class, AddressFixtures::class];
    }

    public function generateOrderRef()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
        $randomString = 'Order-';

        for ($i = 0; $i < 30; ++$i) {
            $index = random_int(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
