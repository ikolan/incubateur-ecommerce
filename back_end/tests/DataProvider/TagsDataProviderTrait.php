<?php

namespace App\Tests\DataProvider;

use App\Entity\User;

trait TagsDataProviderTrait
{
    /**
     * Provide data for creating a new tags.
     */
    public static function tagsCreationData(): array
    {
        return [
            'asAdmin' => [
                [
                    'label' => 'New Tag',
                ],
                function (User $user) {
                    return $user->hasRole(['ROLE_ADMIN']);
                },
                [
                    'statusCode' => 201,
                ],
            ],
            'asSeller' => [
                [
                    'label' => 'New Tag',
                ],
                function (User $user) {
                    return $user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']);
                },
                [
                    'statusCode' => 201,
                ],
            ],
            'asUser' => [
                [
                    'label' => 'New Tag',
                ],
                function (User $user) {
                    return $user->hasRole(['ROLE_USER']) && !$user->hasRole(['ROLE_ADMIN']) && !$user->hasRole(['ROLE_SELLER']);
                },
                [
                    'statusCode' => 403,
                ],
            ],
            'asAnon' => [
                [
                    'label' => 'New Tag',
                ],
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }

    /**
     * Provide data for patch a tags.
     */
    public static function tagsPatchData(): array
    {
        return [
            'asAdmin' => [
                [
                    'label' => 'New Tag',
                ],
                function (User $user) {
                    return $user->hasRole(['ROLE_ADMIN']);
                },
                [
                    'statusCode' => 200,
                ],
            ],
            'asSeller' => [
                [
                    'label' => 'New Tag',
                ],
                function (User $user) {
                    return $user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']);
                },
                [
                    'statusCode' => 200,
                ],
            ],
            'asUser' => [
                [
                    'label' => 'New Tag',
                ],
                function (User $user) {
                    return $user->hasRole(['ROLE_USER']) && !$user->hasRole(['ROLE_ADMIN']) && !$user->hasRole(['ROLE_SELLER']);
                },
                [
                    'statusCode' => 403,
                ],
            ],
            'asAnon' => [
                [
                    'label' => 'New Tag',
                ],
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }
}
