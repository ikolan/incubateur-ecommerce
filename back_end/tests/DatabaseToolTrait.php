<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

/**
 * Trait for database interaction.
 */
trait DatabaseToolTrait
{
    /**
     * @var AbstractDatabaseTool
     */
    private $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function loadFixtures(array $fixtures)
    {
        $this->databaseTool->loadFixtures($fixtures);
    }

    public function cleanDatabase()
    {
        $this->databaseTool->loadFixtures([]);
    }

    public function getEntityManagerFromClient(Client $client): EntityManagerInterface
    {
        return $client->getKernel()->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function getRepositoryFromClient(Client $client, string $class): ObjectRepository
    {
        return $this->getEntityManagerFromClient($client)->getRepository($class);
    }

    public function activateAllUsers()
    {
        $em = $this->getEntityManagerFromClient($this->createClient());
        $userRepository = $em->getRepository(User::class);

        foreach ($userRepository->findBy(['isActivated' => false]) as $user) {
            $user->setIsActivated(true);
            $user->setActivationKey(null);
        }
        $em->flush();
    }
}
