<?php

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\LineCart;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\LineCartDataProviderTrait;

/**
 * Tests for LineCart related stuff.
 */
class LineCartApiTest extends ApiTestCase
{
    use LineCartDataProviderTrait;

    /**
     * Test creates LineCart.
     *
     * @dataProvider LineCartCreationData
     */
    public function testPost($data, $wanted)
    {
        $this->loadFixtures([ProductFixtures::class, UserFixtures::class]);

        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $product = json_decode($client->request('GET', '/api/products/1')->getContent(), true);

        $response = $client->request('POST', '/api/line_carts', ['json' => [
            'quantity' => $data['quantity'],
            'product' => $product['reference'],
            'cartUser' => base64_encode($data['cartUser']),
        ]]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);
    }

    /**
     * Test Get Collection.
     *
     * @depends testPost
     */
    public function testGetCollection()
    {
        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $response = $client->request('GET', '/api/line_carts', [
            'query' => [
                'email' => base64_encode('user1@test.com'),
            ],
        ]);

        $this->assertNotEmpty(json_decode($response->getContent(), true)['hydra:member']);
        $this->assertEquals('hydra:Collection', json_decode($response->getContent(), true)['@type']);
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Test Get Item.
     */
    public function testGetItem()
    {
        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $response = $client->request('GET', '/api/line_carts/1');

        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEmpty(json_decode($response->getContent(), true));
    }

    /**
     * Test Patch.
     *
     * @dataProvider LineCartPatchData
     */
    public function testPatch($data, $wanted)
    {
        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $response = $client->request('PATCH', '/api/line_carts/1', ['json' => $data, 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (400 !== $wanted['statusCode']) {
            $this->assertJsonContains($data);
            $this->assertEquals($data['quantity'], json_decode($response->getContent(), true)['quantity']);
        }
    }

    /**
     * Test Delete.
     */
    public function testDelete()
    {
        $client = $this->createLoggedClient('user0@test.com', 'user0');
        $response = $client->request('DELETE', '/api/line_carts/1');

        $this->assertResponseStatusCodeSame(204);
    }
}
