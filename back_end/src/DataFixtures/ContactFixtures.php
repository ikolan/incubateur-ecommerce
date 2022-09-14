<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class ContactFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 100; ++$i) {
            $contact = new Contact();
            $contact->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setSubject($faker->words(random_int(10, 20), true))
                ->setMessage($faker->words(random_int(50, 600), true))
                ->setSentAt(new DateTimeImmutable($faker->date('Y-m-dTH:i:s')));
            $manager->persist($contact);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
