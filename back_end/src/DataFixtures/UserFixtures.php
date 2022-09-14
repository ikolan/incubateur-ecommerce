<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\RoleRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $em)
    {
        $this->hasher = $hasher;
        $this->addressRepository = $em->getRepository(Address::class);
        $this->roleRepository = $em->getRepository(Role::class);
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        $roleAdmin = $this->roleRepository->findBy(['label' => 'ROLE_ADMIN'])[0];
        $roleSeller = $this->roleRepository->findBy(['label' => 'ROLE_SELLER'])[0];
        $roleUser = $this->roleRepository->findBy(['label' => 'ROLE_USER'])[0];

        for ($i = 0; $i < 100; ++$i) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail('user'.$i.'@test.com');
            $user->setPassword($this->hasher->hashPassword($user, 'user'.$i));
            $user->setBirthDate(new DateTime($faker->date()));
            $user->setPhone($faker->phoneNumber());

            if ($i < 3) {
                $user->addRole($roleAdmin);
            }

            if ($i < 6) {
                $user->addRole($roleSeller);
            }

            $user->addRole($roleUser);

            $activated = ($i < 6) ? 1 : random_int(0, 1);
            if (1 === $activated) {
                $user->setIsActivated(true);
            } else {
                $user->setIsActivated(false);
                $user->setActivationKey(Uuid::v4());
            }

            $addressCount = random_int(0, 5);
            if ($addressCount > 0) {
                for ($j = 0; $j < $addressCount; ++$j) {
                    $address = $this->addressRepository->findAll()[random_int(0, 49)];
                    $user->addAddress($address);
                }

                $userAddresses = $user->getAddresses();
                $user->setMainAddress($userAddresses[random_int(0, count($userAddresses) - 1)]);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [AddressFixtures::class, RoleFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }
}
