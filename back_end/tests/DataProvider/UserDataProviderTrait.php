<?php

namespace App\Tests\DataProvider;

use App\Entity\User;

trait UserDataProviderTrait
{
    /**
     * Provide data for obtaining a collection of users.
     */
    public static function userCollectionObtentionData(): array
    {
        return [
            'asAdmin' => [
                function (User $user) {
                    return $user->hasRole(['ROLE_ADMIN']);
                },
                [
                    'statusCode' => 200,
                ],
            ],
            'asSeller' => [
                function (User $user) {
                    return !$user->hasRole(['ROLE_ADMIN']) && $user->hasRole(['ROLE_SELLER']);
                },
                [
                    'statusCode' => 403,
                ],
            ],
            'asUser' => [
                function (User $user) {
                    return !$user->hasRole(['ROLE_ADMIN']) && !$user->hasRole(['ROLE_SELLER']) && $user->hasRole(['ROLE_USER']);
                },
                [
                    'statusCode' => 403,
                ],
            ],
            'notConnected' => [
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }

    /**
     * Provide data for creating a new user.
     */
    public static function userCreationData(): array
    {
        return [
            [
                [
                    'firstName' => 'Jean',
                    'lastName' => 'Durand',
                    'phone' => '0123456789',
                    'birthDate' => '2000-01-01',
                    'email' => 'jean.durand@test.com',
                    'password' => 'JeanDurand12345&',
                ],
            ],
            [
                [
                    'firstName' => 'Mark',
                    'lastName' => 'Test',
                    'phone' => '+33 1 23 45 67 89',
                    'birthDate' => '1988-05-05',
                    'email' => 'mark-test@test.com',
                    'password' => 'Mark-99999',
                ],
            ],
        ];
    }

    /**
     * Provide data for a valid login.
     */
    public static function userLoginData(): array
    {
        return [
            'valid' => [
                [
                    'email' => 'user0@test.com',
                    'password' => 'user0',
                ],
                [
                    'statusCode' => 200,
                ],
            ],
            'invalid' => [
                [
                    'email' => 'invalid@test.com',
                    'password' => 'invalid',
                ],
                [
                    'statusCode' => 422,
                ],
            ],
            'partiallyInvalid' => [
                [
                    'email' => 'user0@test.com',
                    'password' => 'invalidToo',
                ],
                [
                    'statusCode' => 422,
                ],
            ],
        ];
    }
}
