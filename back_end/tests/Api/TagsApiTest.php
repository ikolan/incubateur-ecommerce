<?php

namespace App\Tests\Api;

use App\DataFixtures\TagFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Category;
use App\Entity\Tags;
use App\Repository\CategoryRepository;
use App\Repository\TagsRepository;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\TagsDataProviderTrait;

/**
 * Tests for category related stuff.
 */
class TagsApiTest extends ApiTestCase
{
    use TagsDataProviderTrait;

    /**
     * Test creates tags.
     *
     * @param $data mixed Data to post
     * @param $user mixed Callback to get the user
     * @param $wanted mixed Result wanted
     * @dataProvider tagsCreationData
     */
    public function testPost($data, $user, $wanted)
    {
        $this->loadFixtures([UserFixtures::class]);
        $client = null === $user ? $this->createClient() : $this->createLoggedClientByCallback($user);
        $response = $client->request('POST', '/api/tags', ['json' => $data]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (201 === $wanted['statusCode']) {
            $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');
            $this->assertMatchesResourceItemJsonSchema(Category::class);
            $this->assertJsonContains([
                '@type' => 'Tags',
                'label' => $data['label'],
            ]);
        }
    }

    /**
     * Test tags Collection Obtention.
     */
    public function testGetCollection()
    {
        $this->loadFixtures([TagFixtures::class]);
        $client = $this->createClient();
        /** @var TagsRepository $tagRepository */
        $tagsRepository = $this->getRepositoryFromClient($client, Tags::class);
        $response = $client->request('GET', '/api/tags');

        $this->assertResponseStatusCodeSame(200);
        $responseContent = json_decode($response->getContent(), true);
        foreach ($responseContent['hydra:member'] as $member) {
            $this->assertNotNull($tagsRepository->findOneBy([
                'label' => $member['label'],
            ]));
        }
    }

    /**
     * Test tags Obtention.
     */
    public function testGet()
    {
        $this->loadFixtures([TagFixtures::class]);
        $client = $this->createClient();
        /** @var CategoryRepository $tagsRepository */
        $tagsRepository = $this->getRepositoryFromClient($client, Tags::class);
        $tags = $tagsRepository->findAll();
        $testedTag = $tags[random_int(0, count($tags) - 1)];
        $response = $client->request('GET', '/api/tags/'.$testedTag->getId());

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'label' => $testedTag->getLabel(),
        ]);
    }

    /**
     * test tags Patch.
     *
     * @param $data mixed Data to patch
     * @param $user mixed Callback to get the user
     * @param $wanted mixed Result wanted
     * @dataProvider tagsPatchData
     */
    public function testPatch($data, $user, $wanted)
    {
        $this->loadFixtures([TagFixtures::class, UserFixtures::class]);
        $client = null === $user ? $this->createClient() : $this->createLoggedClientByCallback($user);
        /** @var TagsRepository $tagsRepository */
        $tagsRepository = $this->getRepositoryFromClient($client, Tags::class);
        $tags = $tagsRepository->findAll();
        $testedTag = $tags[random_int(0, count($tags) - 1)];
        $response = $client->request('PATCH', '/api/tags/'.$testedTag->getId(), ['json' => $data, 'headers' => [
            'Content-Type' => 'application/merge-patch+json',
        ]]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (200 === $wanted['statusCode']) {
            $this->assertJsonContains($data);
        }
    }
}
