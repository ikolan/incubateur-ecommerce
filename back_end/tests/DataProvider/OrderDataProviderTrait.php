<?php

namespace App\Tests\DataProvider;

use App\Entity\User;

trait OrderDataProviderTrait
{
    public static function orderCreationData(): array
    {
        return [
            'asAnon' => [
                'userCallback' => null,
                'data' => [
                    'price' => 30000,
                    'isReturned' => false,
                    'items' => [
                        [
                            'quantity' => 1,
                            'unitPrice' => 20000,
                        ],
                        [
                            'quantity' => 1,
                            'unitPrice' => 10000,
                        ],
                    ],
                ],
                'wanted' => [
                    'statusCode' => 401,
                ],
            ],
            'asAdmin' => [
                'userCallback' => function (User $user) {
                    return $user->hasRole(['ROLE_ADMIN']);
                },
                'data' => [
                    'price' => 30000,
                    'isReturned' => false,
                    'items' => [
                        [
                            'quantity' => 1,
                            'unitPrice' => 20000,
                        ],
                        [
                            'quantity' => 1,
                            'unitPrice' => 10000,
                        ],
                    ],
                ],
                'wanted' => [
                    'statusCode' => 200,
                ],
            ],
            'asSeller' => [
                'userCallback' => function (User $user) {
                    return $user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']);
                },
                'data' => [
                    'price' => 30000,
                    'isReturned' => false,
                    'items' => [
                        [
                            'quantity' => 1,
                            'unitPrice' => 20000,
                        ],
                        [
                            'quantity' => 1,
                            'unitPrice' => 10000,
                        ],
                    ],
                ],
                'wanted' => [
                    'statusCode' => 200,
                ],
            ],
            'asUser' => [
                'userCallback' => function (User $user) {
                    return $user->hasRole(['ROLE_USER']) && !$user->hasRole(['ROLE_SELLER']) && !$user->hasRole(['ROLE_ADMIN']);
                },
                'data' => [
                    'price' => 30000,
                    'isReturned' => false,
                    'items' => [
                        [
                            'quantity' => 1,
                            'unitPrice' => 20000,
                        ],
                        [
                            'quantity' => 1,
                            'unitPrice' => 10000,
                        ],
                    ],
                ],
                'wanted' => [
                    'statusCode' => 200,
                ],
            ],
        ];
    }
}
