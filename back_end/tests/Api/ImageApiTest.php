<?php

namespace App\Tests\Api;

use App\DataFixtures\UserFixtures;
use App\Entity\Image;
use App\Entity\User;
use App\Tests\ApiTestCase;

class ImageApiTest extends ApiTestCase
{
    public function testPost()
    {
        $this->loadFixtures([UserFixtures::class]);
        $imageName = 'myImage';

        $client = $this->createLoggedClientByCallback(function (User $user) {
            return $user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']);
        });
        $imageRepository = $this->getRepositoryFromClient($client, Image::class);
        $response = $client->request('POST', '/api/images', ['json' => [
            'name' => $imageName,
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals($responseContent['name'], $imageName);
        $this->assertEquals($responseContent['name'], $imageRepository->find($responseContent['id'])->getName());

        $response = $client->request('POST', '/api/images', ['json' => [
            'name' => $imageName,
        ]]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testDelete()
    {
        $this->loadFixtures([UserFixtures::class]);
        $imageName = 'myImage';

        $client = $this->createLoggedClientByCallback(function (User $user) {
            return $user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']);
        });
        $imageRepository = $this->getRepositoryFromClient($client, Image::class);
        $response = $client->request('POST', '/api/images', ['json' => [
            'name' => $imageName,
        ]]);
        $imageId = json_decode($response->getContent(), true)['id'];
        $response = $client->request('DELETE', '/api/images/'.$imageId);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull($imageRepository->find($imageId));
    }
}
