<?php

namespace App\DataFixtures;

use App\Entity\LineCart;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LineCartFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(UserRepository $userRepository, ProductRepository $productRepository)
    {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $carts = [];

        $products = $this->productRepository->findAll();
        $users = $this->userRepository->findAll();

        for ($i = 0; $i < 100; ++$i) {
            do {
                $same = false;
                $lineCart = new LineCart();

                $lineCart->setQuantity(random_int(1, 10));
                $lineCart->setProduct($products[random_int(0, count($products) - 1)]);
                $lineCart->setCartUser($users[random_int(0, count($users) - 1)]);

                foreach ($carts as $cart) {
                    if ($lineCart->getProduct() == $cart->getProduct() && $lineCart->getCartUser() == $cart->getCartUser()) {
                        $same = true;
                    }
                }
            } while ($same);

            array_push($carts, $lineCart);
        }

        foreach ($carts as $cart) {
            $manager->persist($cart);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }

    public function getDependencies()
    {
        return [ProductFixtures::class, UserFixtures::class];
    }
}
