<?php

namespace App\Tests\Api;

use App\DataFixtures\ContactFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Tests\ApiTestCase;
use App\Tests\DataProvider\ContactDataProviderTrait;
use DateTimeImmutable;

/**
 * Tests for contact messages.
 */
class ContactApiTest extends ApiTestCase
{
    use ContactDataProviderTrait;

    /**
     * Test contact message sending.
     *
     * @dataProvider contactCreation
     */
    public function testPost($input, $wanted)
    {
        $this->loadFixtures([UserFixtures::class]);

        $client = $this->createClient();
        /** @var ContactRepository */
        $contactRepository = $this->getRepositoryFromClient($client, Contact::class);
        $response = $client->request('POST', '/api/contacts', ['json' => $input]);

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (201 === $wanted['statusCode']) {
            $this->assertResponseHasHeader('Content-Type', 'application/json');
            $this->assertJsonContains($input);
            $this->assertNotNull($contactRepository->findOneBy($input));
        }
    }

    /**
     * Test contact message obtention.
     *
     * @dataProvider contactObtention
     */
    public function testGet($userCallback, $wanted)
    {
        $this->loadFixtures([UserFixtures::class, ContactFixtures::class]);

        $client = null !== $userCallback ? $this->createLoggedClientByCallback($userCallback) : $this->createClient();
        /** @var ContactRepository */
        $contactRepository = $this->getRepositoryFromClient($client, Contact::class);
        $response = $client->request('GET', '/api/contacts');

        $this->assertResponseStatusCodeSame($wanted['statusCode']);

        if (200 === $wanted['statusCode']) {
            $responseContent = json_decode($response->getContent(), true)['hydra:member'];
            foreach ($responseContent as $responseMember) {
                $this->assertNotNull($contactRepository->findOneBy([
                    'firstName' => $responseMember['firstName'],
                    'lastName' => $responseMember['lastName'],
                    'email' => $responseMember['email'],
                    'subject' => $responseMember['subject'],
                    'message' => $responseMember['message'],
                    'sentAt' => new DateTimeImmutable($responseMember['sentAt']),
                ]));
            }
        }
    }

    /**
     * Test contact message deletion.
     *
     * @dataProvider contactDeletion
     */
    public function testDelete($userCallback, $wanted)
    {
        $this->loadFixtures([UserFixtures::class, ContactFixtures::class]);

        $client = null !== $userCallback ? $this->createLoggedClientByCallback($userCallback) : $this->createClient();
        /** @var ContactRepository */
        $contactRepository = $this->getRepositoryFromClient($client, Contact::class);
        $contacts = $contactRepository->findAll();
        $contact = $contacts[random_int(0, count($contacts) - 1)];
        $response = $client->request('DELETE', '/api/contacts/'.$contact->getId());

        $this->assertResponseStatusCodeSame($wanted['statusCode']);
    }
}
