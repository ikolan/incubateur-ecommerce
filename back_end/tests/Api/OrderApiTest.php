<?php

namespace App\Tests\Api;

use App\DataFixtures\AddressFixtures;
use App\DataFixtures\OrderFixtures;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Address;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\OrderDataProviderTrait;

/**
 * Tests for category related stuff.
 */
class OrderApiTest extends ApiTestCase
{
    use OrderDataProviderTrait;

    /**
     * Test Order creations.
     *
     * @dataProvider orderCreationData
     */
    public function testPost($userCallback, array $data, array $wanted)
    {
        $this->loadFixtures([UserFixtures::class, OrderFixtures::class, AddressFixtures::class, ProductFixtures::class]);
        $this->activateAllUsers();

        $client = null === $userCallback ? $this->createClient() : $this->createLoggedClientByCallback($userCallback);
        $addressRepository = $this->getRepositoryFromClient($client, Address::class);
        $productRepository = $this->getRepositoryFromClient($client, Product::class);

        $addresses = $addressRepository->findAll();
        $data['address'] = '/api/addresses/'.$addresses[random_int(0, count($addresses) - 1)]->getId();

        $products = $productRepository->findAll();
        $data['items'] = array_map(function (array $item) use ($products) {
            $item['product'] = '/api/products/'.$products[random_int(0, count($products) - 1)]->getId();

            return $item;
        }, $data['items']);

        $response = $client->request('POST', '/api/orders', ['json' => $data]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);
    }

    /**
     * Test patch Order Status.
     */
    public function testPatchStatus()
    {
        $this->loadFixtures([UserFixtures::class, OrderFixtures::class, AddressFixtures::class]);

        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $user = json_decode($client->request('GET', 'api/users/1')->getContent());
        $order = $user->orders[0];

        $client->request('PATCH', "api/orders/$order->orderReference", ['json' => [
            'status' => 'api/statuses/2',
        ], 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertEquals('Payé', json_decode($client->getResponse()->getContent())->status->label);
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Test for posting a new return request.
     */
    public function testPostReturn()
    {
        $this->loadFixtures([OrderFixtures::class]);

        /** @var User */
        $connectedUser = null;
        $client = $this->createLoggedClientByCallback(function (User $user) use (&$connectedUser) {
            if (!$user->hasRole(['ROLE_ADMIN']) && !$user->hasRole(['ROLE_SELLER'])) {
                $connectedUser = $user;

                return true;
            }

            return false;
        });
        $orderRepository = $this->getRepositoryFromClient($client, Order::class);
        $order = $connectedUser->getOrders()[random_int(0, $connectedUser->getOrders()->count() - 1)];
        $data = [
            'reason' => 'Any reason...',
            'description' => 'Any description...',
        ];
        $response = $client->request('POST', '/api/orders/'.$order->getOrderReference().'/return', ['json' => $data]);

        $this->assertResponseStatusCodeSame(201);
        $order = $orderRepository->findBy(['orderReference' => $order->getOrderReference()])[0];
        $this->assertEquals($order->getOrderReturn()->getReason(), $data['reason']);
        $this->assertEquals($order->getOrderReturn()->getDescription(), $data['description']);
    }
}
