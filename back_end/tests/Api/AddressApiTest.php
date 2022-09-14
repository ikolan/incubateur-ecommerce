<?php

use App\DataFixtures\AddressFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\AddressDataProviderTrait;

/**
 * Tests for address related stuff.
 */
class AddressApiTest extends ApiTestCase
{
    use AddressDataProviderTrait;

    /**
     * Return all addresses except if its own by the specified user.
     */
    private function getOtherAddresses(AddressRepository $addressRepository, string $currentUserEmail): array
    {
        return array_values(array_filter($addressRepository->findAll(), function (Address $v) use ($currentUserEmail) {
            $isFromOtherOne = true;
            foreach ($v->getUsers() as $user) {
                if ($currentUserEmail === $user->getEmail()) {
                    $isFromOtherOne = false;
                }
            }

            return $isFromOtherOne;
        }));
    }

    /**
     * Test address obtention.
     */
    public function testGet()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();

        /** @var User */
        $connectedUser = null;

        $client = $this->createLoggedClientByCallback(function (User $user) use (&$connectedUser) {
            if (!$user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']) && count($user->getAddresses()) > 0) {
                $connectedUser = $user;

                return true;
            } else {
                return false;
            }
        });

        $address = $connectedUser->getAddresses()[0];
        $response = $client->request('GET', '/api/addresses/'.$address->getId());
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals($responseContent['id'], $address->getId());
    }

    /**
     * Test addresses obtention without auth.
     */
    public function testGetCollectionWithNoAuth()
    {
        $this->loadFixtures([UserFixtures::class]);
        $respones = $this->createClient()->request('GET', '/api/addresses');
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * Test main address obtention.
     */
    public function testGetMain()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();

        /** @var User */
        $connectedUser = null;

        $client = $this->createLoggedClientByCallback(function (User $user) use (&$connectedUser) {
            if (!$user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']) && null !== $user->getMainAddress()) {
                $connectedUser = $user;

                return true;
            } else {
                return false;
            }
        });
        $response = $client->request('GET', '/api/addresses/main');

        $this->assertResponseStatusCodeSame(200);
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals($responseContent['id'], $connectedUser->getMainAddress()->getId());
    }

    /**
     * Test addresses collection obtention.
     */
    public function testGetCollection()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();

        $client = $this->createLoggedClient('user0@test.com', 'user0');
        /** @var UserRepository */
        $userRepository = $this->getRepositoryFromClient($client, User::class);
        $user = $userRepository->findByEmail('user0@test.com');
        $response = $client->request('GET', '/api/addresses');

        $this->assertResponseStatusCodeSame(200);

        $responseContent = json_decode($response->getContent(), true)['hydra:member'];
        $this->assertEquals(count($responseContent), count($user->getAddresses()));
        foreach ($responseContent as $index => $address) {
            $this->assertEquals($user->getAddresses()[$index]->getId(), $address['id']);
        }
    }

    /**
     * Test the address cration.
     *
     * @dataProvider addressCreationData
     */
    public function testPost($input)
    {
        $this->loadFixtures([UserFixtures::class]);
        $client = $this->createLoggedClient('user0@test.com', 'user0');
        /** @var UserRepository */
        $userRepository = $this->getRepositoryFromClient($client, User::class);
        $user = $userRepository->findByEmail('user0@test.com');
        $userAddressCount = count($user->getAddresses());
        $response = $client->request('POST', '/api/addresses', ['json' => $input]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@type' => 'Address',
            'title' => $input['title'],
            'number' => $input['number'],
            'road' => $input['road'],
            'zipcode' => $input['zipcode'],
            'city' => $input['city'],
            'phone' => $input['phone'],
        ]);
        $user = $userRepository->findByEmail('user0@test.com');
        $this->assertEquals(count($user->getAddresses()), $userAddressCount + 1);
    }

    /**
     * Test to post an already existing address.
     */
    public function testPostAlreadyExist()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();
        $client = $this->createLoggedClient('user10@test.com', 'user10');

        /**
         * @var AddressRepository
         */
        $addressRepository = $this->getRepositoryFromClient($client, Address::class);
        $allAddresses = $addressRepository->findAll();

        /**
         * @var Address
         */
        $address = $allAddresses[random_int(0, count($allAddresses) - 1)];
        $response = $client->request('POST', '/api/addresses', ['json' => [
            'title' => $address->getTitle(),
            'number' => $address->getNumber(),
            'road' => $address->getRoad(),
            'zipcode' => $address->getZipcode(),
            'city' => $address->getCity(),
            'phone' => $address->getPhone(),
        ]]);

        $this->assertResponseStatusCodeSame(201);

        $newAddress = $addressRepository->find(json_decode($response->getContent(), true)['id']);

        $this->assertEquals($address, $newAddress);
    }

    /**
     * Test address creation with no authorization.
     *
     * @dataProvider addressCreationData
     */
    public function testPostWithNoAuth($input)
    {
        $this->loadFixtures([UserFixtures::class]);
        $response = $this->createClient()->request('POST', '/api/addresses', ['json' => $input]);
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * Test address modification.
     *
     * @dataProvider addressModificationData
     */
    public function testPatch($input)
    {
        $this->loadFixtures([AddressFixtures::class, UserFixtures::class]);

        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $addressRepository = $this->getRepositoryFromClient($client, Address::class);
        $addressId = $addressRepository->findAll()[0]->getId();
        $response = $client->request('PATCH', '/api/addresses/'.$addressId, ['json' => $input, 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains($input);

        $address = $addressRepository->find(json_decode($response->getContent(), true)['id']);

        foreach ($input as $key => $value) {
            $methodName = 'get'.ucwords($key);
            $this->assertEquals($value, $address->$methodName());
        }
    }

    /**
     * Test main address setter.
     */
    public function testPatchMain()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->activateAllUsers();

        /** @var User */
        $connectedUser = null;

        $client = $this->createLoggedClientByCallback(function (User $user) use (&$connectedUser) {
            if (!$user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']) && count($user->getAddresses()) > 1) {
                $connectedUser = $user;

                return true;
            } else {
                return false;
            }
        });
        $userAddresses = $connectedUser->getAddresses();
        $newMainAddress = $userAddresses[0]->getId() !== $connectedUser->getMainAddress()->getId() ? $userAddresses[0] : $userAddresses[1];
        /** @var UserRepository */
        $userRepository = $this->getRepositoryFromClient($client, User::class);

        $response = $client->request('PATCH', '/api/addresses/'.$newMainAddress->getId().'/main', ['headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals($userRepository->findByEmail($connectedUser->getEmail())->getMainAddress()->getId(), $newMainAddress->getId());
    }

    /**
     * Test other's address modification.
     *
     * @dataProvider addressModificationData
     */
    public function testPatchOnOther($input)
    {
        $this->loadFixtures([AddressFixtures::class, UserFixtures::class]);
        $this->activateAllUsers();

        $client = $this->createLoggedClient('user10@test.com', 'user10');
        $addressRepository = $this->getRepositoryFromClient($client, Address::class);

        $addresses = $this->getOtherAddresses($addressRepository, 'user10@test.com');

        $response = $client->request('PATCH', '/api/addresses/'.$addresses[0]->getId(), ['json' => $input, 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * Test other's address modification as Admin.
     *
     * @dataProvider addressModificationData
     */
    public function testPatchOnOtherAsAdmin($input)
    {
        $this->loadFixtures([AddressFixtures::class, UserFixtures::class]);
        $this->activateAllUsers();

        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $addressRepository = $this->getRepositoryFromClient($client, Address::class);

        $addresses = $this->getOtherAddresses($addressRepository, 'user0@test.com');

        $response = $client->request('PATCH', '/api/addresses/'.$addresses[0]->getId(), ['json' => $input, 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains($input);

        $address = $addressRepository->find(json_decode($response->getContent(), true)['id']);

        foreach ($input as $key => $value) {
            $methodName = 'get'.ucwords($key);
            $this->assertEquals($value, $address->$methodName());
        }
    }

    /**
     * Test for delete an address.
     */
    public function testDelete()
    {
        $this->loadFixtures([AddressFixtures::class, UserFixtures::class]);
        $this->activateAllUsers();

        /**
         * @var User
         */
        $connectedUser = null;
        $client = $this->createLoggedClientByCallback(function (User $user) use (&$connectedUser) {
            $result = $user->hasRole(['ROLE_ADMIN']) || $user->hasRole(['ROLE_SELLER']) || 0 === count($user->getAddresses()) ? false : true;
            if ($result) {
                $connectedUser = $user;
            }

            return $result;
        });

        /**
         * @var UserRepository
         */
        $userRepository = $this->getRepositoryFromClient($client, User::class);

        $addressesCount = count($connectedUser->getAddresses());
        $addressToDelete = $connectedUser->getAddresses()[0];

        $response = $client->request('DELETE', '/api/addresses/'.$addressToDelete->getId(), ['json' => []]);

        $connectedUser = $userRepository->findByEmail($connectedUser->getEmail());
        $this->assertResponseStatusCodeSame(204);
        $this->assertEquals(count($connectedUser->getAddresses()), $addressesCount - 1);
    }
}
