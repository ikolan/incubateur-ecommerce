<?php

namespace App\Tests\Api;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\ProductDataProviderTrait;

/**
 * Tests for address related stuff.
 */
class ProductApiTest extends ApiTestCase
{
    use ProductDataProviderTrait;

    /**
     * Test creates Product.
     *
     * @dataProvider ProductCreationData
     */
    public function testPost($data)
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $client = $this->createClient();

        /**
         * @var CategoryRepository
         */
        $categoryRepository = $this->getRepositoryFromClient($client, Category::class);
        $category = json_decode($client->request('GET', $data['category'])->getContent(), true);

        $response = $client->request('POST', '/api/products', ['json' => $data]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'Product',
            'name' => $data['name'],
            'category' => [
                'label' => $category['label'],
            ],
            'reference' => $data['reference'],
            'price' => $data['price'],
            'tax' => $data['tax'],
            'description' => $data['description'],
            'detailedDescription' => $data['detailedDescription'],
            'weight' => $data['weight'],
            'stock' => $data['stock'],
            'frontPage' => $data['frontPage'],
        ]);
    }

    /**
     * Test Get Product Collection.
     */
    public function testGetCollection()
    {
        $client = $this->createClient();

        $response = $client->request('GET', '/api/products');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Test Get Product.
     */
    public function testGet()
    {
        $client = $this->createClient();

        $response = $client->request('GET', '/api/products/1');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Test patch Product.
     *
     * @dataProvider ProductModificationData
     */
    public function testPatch($input)
    {
        $client = $this->createClient();
        $productRepository = $this->getRepositoryFromClient($client, Product::class);

        $response = $client->request('PATCH', '/api/products/1', ['json' => $input, 'headers' => [
            'Content-Type' => 'application/merge-patch+json', ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains($input);

        $product = $productRepository->find(1);

        foreach ($input as $key => $value) {
            $methodName = 'get'.ucwords($key);
            $this->assertEquals($value, $product->$methodName());
        }
    }

    /**
     * @dataProvider ProductFindByFilterData
     */
    public function testGetbyFilters($data, $wanted)
    {
        $this->loadFixtures([ProductFixtures::class]);
        $client = $this->createClient();
        $filters = '?';
        foreach ($data as $key => $value) {
            $filters .= $key.'='.$value.'&';
        }

        $response = $client->request('GET', "api/products$filters");
        $this->assertResponseStatusCodeSame($wanted['statusCode']);
        (isset($wanted['arraySize']) ?
        $this->assertEquals($wanted['arraySize'], json_decode($response->getContent(), true)['hydra:totalItems'])
        :
        ''
        );
    }

    public function testNoProductFound()
    {
        $this->loadFixtures([ProductFixtures::class]);
        $client = $this->createClient();

        $requests = ['0'];
        foreach ($requests as $request) {
            $response = $client->request('GET', "/api/products/$request");
            $this->assertResponseStatusCodeSame(404);
        }
    }
}
