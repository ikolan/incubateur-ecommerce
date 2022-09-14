<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $status = [
            'Non payé',
            'Payé',
            'En cours de livraison',
            'Livré',
            'Annulé',
            'Remboursé',
        ];

        foreach ($status as $i => $status) {
            $newStatus = (new Status())
                ->setLabel($status);
            $manager->persist($newStatus);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }
}
