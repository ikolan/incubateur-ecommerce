<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as ApiPlatformApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;

abstract class ApiTestCase extends ApiPlatformApiTestCase
{
    use DatabaseToolTrait;

    /**
     * Create a client with a login token preloaded.
     */
    public function createLoggedClient(string $email, string $password): Client
    {
        $response = $this->createClient()->request('POST', '/api/users/login', ['json' => [
            'email' => $email,
            'password' => $password,
        ]]);

        $token = json_decode($response->getContent(), true)['token'];

        return $this->createClient([], ['headers' => ['Authorization' => 'Bearer '.$token]]);
    }

    /**
     * Create a client with a logged user selected by a callback.
     */
    public function createLoggedClientByCallback($callback): Client
    {
        $users = $this->getEntityManagerFromClient($this->createClient())
                    ->getRepository(User::class)->findAll();

        /** @var User */
        $selectedUser = null;

        foreach ($users as $user) {
            if ($callback($user)) {
                $selectedUser = $user;
                break;
            }
        }

        $email = $selectedUser->getEmail();
        $password = explode('@', $email)[0];

        return $this->createLoggedClient($email, $password);
    }
}
