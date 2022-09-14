<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tags = [
            'Ryzen 3',
            'Ryzen 5',
            'Ryzen 7',
            'Ryzen 9',
            'Ryzen',
            'Intel',
            'AMD',
            'Radeon',
            'Nvidia',
            'Core I3',
            'Core I5',
            'Core I7',
            'Core I9',
            '2 Go',
            '4 Go',
            '8 Go',
            '16 Go',
            '32 Go',
            '256 Go de stockage',
            '512 Go de stockage',
            '1 To de stockage',
            'Puissant',
            'Compact',
            'Rapide',
            'Multithreaded',
            '2 Coeurs',
            '4 Coeurs',
            '8 Coeurs',
            'Nvidia GTX',
            'Nvidia RTX',
            'Radeon RX',
        ];

        foreach ($tags as $i => $tag) {
            $tag = (new Tags())
                ->setLabel($tag);
            $manager->persist($tag);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }
}
