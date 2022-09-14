<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class AddressFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 50; ++$i) {
            $address = new Address();

            $address->setTitle('Address #'.$i);
            $address->setNumber(random_int(0, 999));
            $address->setRoad($faker->words(random_int(1, 5), true));
            $address->setZipcode(random_int(10000, 99999));
            $address->setCity($faker->city());
            $address->setPhone($faker->phoneNumber());

            $manager->persist($address);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }
}
