<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $roles = [
            'ROLE_USER',
            'ROLE_ADMIN',
            'ROLE_SELLER',
        ];

        foreach ($roles as $i => $role) {
            $role = (new Role())
                ->setLabel($role);
            $manager->persist($role);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['default', 'test', 'Ordertest'];
    }
}
