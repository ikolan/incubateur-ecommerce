<?php

namespace App\Tests\Api;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\CategoryDataProviderTrait;

/**
 * Tests for category related stuff.
 */
class CategoryApiTest extends ApiTestCase
{
    use CategoryDataProviderTrait;

    /**
     * Test creates Category.
     *
     * @param $data mixed Data to post
     * @param $user mixed Callback to get the user
     * @param $wanted mixed Result wanted
     * @dataProvider categoryCreationData
     */
    public function testPost($data, $user, $wanted)
    {
        $this->loadFixtures([UserFixtures::class]);
        $client = null === $user ? $this->createClient() : $this->createLoggedClientByCallback($user);
        $response = $client->request('POST', '/api/categories', ['json' => $data]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (201 === $wanted['statusCode']) {
            $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');
            $this->assertMatchesResourceItemJsonSchema(Category::class);
            $this->assertJsonContains([
                '@type' => 'Category',
                'label' => $data['label'],
            ]);
        }
    }

    /**
     * Test Category Collection Obtention.
     */
    public function testGetCollection()
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $client = $this->createClient();
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getRepositoryFromClient($client, Category::class);
        $response = $client->request('GET', '/api/categories');

        $this->assertResponseStatusCodeSame(200);
        $responseContent = json_decode($response->getContent(), true);
        foreach ($responseContent['hydra:member'] as $member) {
            $this->assertNotNull($categoryRepository->findOneBy([
                'label' => $member['label'],
            ]));
        }
    }

    /**
     * Test Category Obtention.
     */
    public function testGet()
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $client = $this->createClient();
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getRepositoryFromClient($client, Category::class);
        $categories = $categoryRepository->findAll();
        $testedCategory = $categories[random_int(0, count($categories) - 1)];
        $response = $client->request('GET', '/api/categories/'.$testedCategory->getId());

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'label' => $testedCategory->getLabel(),
        ]);
    }

    /**
     * test Category Patch.
     *
     * @param $data mixed Data to patch
     * @param $user mixed Callback to get the user
     * @param $wanted mixed Result wanted
     * @dataProvider categoryPatchData
     */
    public function testPatch($data, $user, $wanted)
    {
        $this->loadFixtures([CategoryFixtures::class, UserFixtures::class]);
        $client = null === $user ? $this->createClient() : $this->createLoggedClientByCallback($user);
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getRepositoryFromClient($client, Category::class);
        $categories = $categoryRepository->findAll();
        $testedCategory = $categories[random_int(0, count($categories) - 1)];
        $response = $client->request('PATCH', '/api/categories/'.$testedCategory->getId(), ['json' => $data, 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (200 === $wanted['statusCode']) {
            $this->assertJsonContains($data);
        }
    }
}
