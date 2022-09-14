<?php

namespace App\Tests\DataProvider;

use App\Entity\User;

trait CategoryDataProviderTrait
{
    /**
     * Provide data for creating a new Category.
     */
    public static function categoryCreationData(): array
    {
        return [
            'asAdmin' => [
                [
                    'label' => 'New Category',
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
                    'label' => 'New Category',
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
                    'label' => 'New Category',
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
                    'label' => 'New Category',
                ],
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }

    /**
     * Provide data for patch a category.
     */
    public static function categoryPatchData(): array
    {
        return [
            'asAdmin' => [
                [
                    'label' => 'New Category',
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
                    'label' => 'New Category',
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
                    'label' => 'New Category',
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
                    'label' => 'New Category',
                ],
                null,
                [
                    'statusCode' => 401,
                ],
            ],
        ];
    }
}
