<?php

namespace App\Tests\Api;

use App\DataFixtures\RoleFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\UserDataProviderTrait;

/**
 * Tests for Users related stuff.
 */
class UserApiTest extends ApiTestCase
{
    use UserDataProviderTrait;

    /**
     * Test the collection obtention.
     *
     * @param $userCallback mixed Function to choose the user for the connected user
     * @param $wanted mixed Result wanted
     * @dataProvider userCollectionObtentionData
     */
    public function testGetCollection($userCallback, $wanted)
    {
        $this->loadFixtures([UserFixtures::class]);

        $client = null === $userCallback ? $this->createClient() : $this->createLoggedClientByCallback($userCallback);
        $userRepository = $this->getRepositoryFromClient($client, User::class);
        $response = $client->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (200 === $wanted['statusCode']) {
            $responseContent = json_decode($response->getContent(), true);
            foreach ($responseContent['hydra:member'] as $user) {
                $this->assertNotEquals(null, $userRepository->findOneBy([
                    'firstName' => $user['firstName'],
                    'lastName' => $user['lastName'],
                    'email' => $user['email'],
                    'birthDate' => new \DateTimeImmutable($user['birthDate']),
                ]));
            }
        }
    }

    /**
     * Test the user creation.
     *
     * @param $user mixed User to create
     * @dataProvider userCreationData
     */
    public function testPost($user)
    {
        $this->loadFixtures([RoleFixtures::class]);

        $response = $this->createClient()->request('POST', '/api/users', ['json' => $user]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(User::class);
        $this->assertJsonContains([
            '@type' => 'User',
            'email' => $user['email'],
            'firstName' => $user['firstName'],
            'lastName' => $user['lastName'],
            'birthDate' => (new \DateTimeImmutable($user['birthDate']))->format(\DateTime::ATOM),
            'phone' => $user['phone'],
        ]);
    }

    /**
     * Test the user login.
     *
     * @param $loginData mixed Information for process a login
     * @param $wanted mixed Result wanted
     * @dataProvider userLoginData
     */
    public function testLogin($loginData, $wanted)
    {
        $this->loadFixtures([UserFixtures::class]);

        $responce = $this->createClient()->request('POST', '/api/users/login', ['json' => [
            'email' => $loginData['email'],
            'password' => $loginData['password'],
        ]]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if ($wanted['statusCode']) {
            $this->assertMatchesJsonSchema([
                'token' => 'string',
            ]);
        }
    }

    /**
     * Test user activation.
     *
     * @depends testPost
     */
    public function testActivation()
    {
        $this->loadFixtures([RoleFixtures::class]);

        $client = $this->createClient();
        $userRepository = $this->getEntityManagerFromClient($client)->getRepository(User::class);
        $userData = $this->userCreationData()[0][0];

        $response = $client->request('POST', '/api/users', ['json' => $userData]);
        /**
         * @var User
         */
        $user = $userRepository->findOneBy(['email' => json_decode($response->getContent(), true)['email']]);

        $response = $client->request('POST', '/api/users/activate', ['json' => [
            'activationKey' => $user->getActivationKey(),
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesResourceItemJsonSchema(User::class);

        /**
         * @var User
         */
        $user = $userRepository->findOneBy(['email' => json_decode($response->getContent(), true)['email']]);

        $this->assertEquals($user->getIsActivated(), true);
        $this->assertNull($user->getActivationKey());
    }

    /**
     * Test user activation.
     *
     * @depends testPost
     * @depends testActivation
     */
    public function testDeactivation()
    {
        $this->loadFixtures([RoleFixtures::class]);

        $client = $this->createClient();
        $userRepository = $this->getEntityManagerFromClient($client)->getRepository(User::class);
        $userData = $this->userCreationData()[0][0];

        //Create the user
        $response = $client->request('POST', '/api/users', ['json' => $userData]);
        /**
         * @var User
         */
        $userId = $userRepository->findAll()[0]->getId();

        $user = $userRepository->find($userId);

        //Activate the user
        $response = $client->request('POST', '/api/users/activate', ['json' => [
            'activationKey' => $user->getActivationKey(),
        ]]);

        //Deactivate the user
        $response = $client->request('POST', '/api/users/1/deactivate');
        $user = $userRepository->findAll()[0];

        $this->assertResponseStatusCodeSame(200);

        $this->assertEquals($user->getIsActivated(), false);
        $this->assertNotNull($user->getIsActivated());
    }

    /**
     * Test email modification.
     */
    public function testEmailPatch()
    {
        $this->loadFixtures([UserFixtures::class]);

        $client = $this->createClient();
        $userRepository = $this->getEntityManagerFromClient($client)->getRepository(User::class);
        $userId = $userRepository->findAll()[0]->getId();
        $newEmail = 'new-email@test.com';

        $response = $client->request('PATCH', '/api/users/'.$userId, ['json' => [
            'email' => $newEmail,
        ], 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ],
        ]);

        $this->assertResponseStatusCodeSame(200);

        /**
         * @var User
         */
        $user = $userRepository->find($userId);

        $this->assertEquals($user->getEmail(), $newEmail);
    }

    /**
     * Test email modification with already use email address.
     */
    public function testEmailPatchWithAlreadyExistOne()
    {
        $this->loadFixtures([UserFixtures::class]);

        $client = $this->createClient();
        /**
         * @var UserRepository
         */
        $userRepository = $this->getEntityManagerFromClient($client)->getRepository(User::class);
        $userId = $userRepository->findByEmail('user0@test.com')->getId();

        $response = $client->request('PATCH', '/api/users/'.$userId, ['json' => [
            'email' => 'user1@test.com',
        ], 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ],
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    /**
     * Test password modification.
     *
     * @depends testLogin
     */
    public function testPasswordPatch()
    {
        $this->loadFixtures([UserFixtures::class]);

        $client = $this->createClient();
        $userRepository = $this->getEntityManagerFromClient($client)->getRepository(User::class);
        $userId = $userRepository->findBy(['email' => 'user0@test.com'])[0]->getId();
        $newPassword = 'NewPassword-12345';

        $response = $client->request('PATCH', '/api/users/'.$userId, ['json' => [
            'oldpassword' => 'user0',
            'newpassword' => $newPassword,
        ], 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame(200);

        $response = $client->request('POST', '/api/users/login', ['json' => [
            'email' => 'user0@test.com',
            'password' => $newPassword,
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesJsonSchema([
            'token' => 'string',
        ]);
    }

    /**
     * Test first name & last name modification.
     */
    public function testNamesPatch()
    {
        $this->loadFixtures([UserFixtures::class]);

        $client = $this->createClient();
        $userRepository = $this->getEntityManagerFromClient($client)->getRepository(User::class);
        $userId = $userRepository->findBy(['email' => 'user0@test.com'])[0]->getId();

        $response = $client->request('PATCH', '/api/users/'.$userId, ['json' => [
            'firstName' => 'Jean',
            'lastName' => 'Du Test',
        ], 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame(200);

        /** @var User */
        $user = $userRepository->find($userId);

        $this->assertEquals($user->getFirstName(), 'Jean');
        $this->assertEquals($user->getLastName(), 'Du Test');
    }

    /**
     * Test password reset key generation.
     */
    public function testResetKeyGeneration()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();

        $client = $this->createLoggedClient('user0@test.com', 'user0');
        /** @var UserRepository */
        $userRepository = $this->getRepositoryFromClient($client, User::class);
        $user = $userRepository->findByEmail('user0@test.com');
        $response = $client->request('POST', '/api/users/'.$user->getId().'/reset-key');

        $this->assertResponseStatusCodeSame(204);
        $user = $userRepository->findByEmail('user0@test.com');
        $this->assertNotEquals($user->getResetKey(), null);
    }

    /**
     * Test get user by reset key.
     *
     * @depends testResetKeyGeneration
     */
    public function testGetByResetKey()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();

        /** @var User */
        $connectedUser = null;
        /** @var UserRepository */
        $userRepository = $this->getRepositoryFromClient($this->createClient(), User::class);
        $users = $userRepository->findAll();
        $connectedUser = $users[random_int(0, count($users) - 1)];
        $client = $this->createLoggedClientByCallback(function (User $user) use ($connectedUser) {
            return $user->getId() === $connectedUser->getId();
        });
        $client->request('POST', '/api/users/'.$connectedUser->getId().'/reset-key');
        $connectedUser = $userRepository->findByEmail($connectedUser->getEmail());
        $response = $client->request('GET', '/api/users/by-reset-key/'.$connectedUser->getResetKey());

        $this->assertResponseStatusCodeSame(200);
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals($responseContent['email'], $connectedUser->getEmail());
    }
}
