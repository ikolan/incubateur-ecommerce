<?php

namespace App\Tests\DataProvider;

use App\Entity\User;

trait ContactDataProviderTrait
{
    public static function contactCreation(): array
    {
        return [
            'validInput' => [
                [
                    'firstName' => 'Jean',
                    'lastName' => 'Durand',
                    'email' => 'jean.durand@test.com',
                    'subject' => 'Un message de Test',
                    'message' => 'Le contenu du message ......',
                ],
                [
                    'statusCode' => 201,
                ],
            ],
            'invalidEmail' => [
                [
                    'firstName' => 'Jean',
                    'lastName' => 'Durand',
                    'email' => 'An_invalid_email',
                    'subject' => 'Un message de Test',
                    'message' => 'Le contenu du message ......',
                ],
                [
                    'statusCode' => 422,
                ],
            ],
        ];
    }

    public static function contactObtention(): array
    {
        return [
            'authorizedSeller' => [
                function (User $user) {
                    return in_array('ROLE_SELLER', $user->getRoles());
                },
                [
                    'statusCode' => 200,
                ],
            ],
            'authorizedAdmin' => [
                function (User $user) {
                    return in_array('ROLE_ADMIN', $user->getRoles());
                },
                [
                    'statusCode' => 200,
                ],
            ],
            'unaurizedUser' => [
                function (User $user) {
                    return in_array('ROLE_USER', $user->getRoles()) &&
                        !in_array('ROLE_SELLER', $user->getRoles()) &&
                        !in_array('ROLE_ADMIN', $user->getRoles());
                },
                [
                    'statusCode' => 403,
                ],
            ],
            'unauthorizedAnon' => [
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }

    public static function contactDeletion(): array
    {
        return [
            'authorizedSeller' => [
                function (User $user) {
                    return in_array('ROLE_SELLER', $user->getRoles());
                },
                [
                    'statusCode' => 204,
                ],
            ],
            'authorizedAdmin' => [
                function (User $user) {
                    return in_array('ROLE_ADMIN', $user->getRoles());
                },
                [
                    'statusCode' => 204,
                ],
            ],
            'unaurizedUser' => [
                function (User $user) {
                    return in_array('ROLE_USER', $user->getRoles()) &&
                        !in_array('ROLE_SELLER', $user->getRoles()) &&
                        !in_array('ROLE_ADMIN', $user->getRoles());
                },
                [
                    'statusCode' => 403,
                ],
            ],
            'unauthorizedAnon' => [
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }
}
