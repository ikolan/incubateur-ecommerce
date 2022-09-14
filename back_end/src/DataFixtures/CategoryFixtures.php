<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'PC de bureau',
            'PC Gamer',
            'PC portables',
            'PC portables Gamer',
        ];

        foreach ($categories as $i => $category) {
            $category = (new Category())
                ->setLabel($category);
            $manager->persist($category);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }
}
