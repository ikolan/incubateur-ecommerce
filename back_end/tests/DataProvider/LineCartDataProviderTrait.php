<?php

namespace App\Tests\DataProvider;

trait LineCartDataProviderTrait
{
    /**
     * Provide data for creating a new LineCart.
     */
    public static function LineCartCreationData(): array
    {
        return [
            [
                [
                    'quantity' => 2,
                    'cartUser' => 'user0@test.com',
                ],
                [
                    'statusCode' => 201,
                ],
            ],
            [
                [
                    'quantity' => 3,
                    'cartUser' => 'user1@test.com',
                ],
                [
                    'statusCode' => 201,
                ],
            ],
        ];
    }

    /**
     * Provides Data for LineCart Patch.
     */
    public static function LineCartPatchData(): array
    {
        return [
            [
                [
                    'quantity' => 5,
                ],
                [
                    'statusCode' => 200,
                ],
                ],
            [
                [
                    'quantity' => 'cinq',
                ],
                [
                    'statusCode' => 400,
                ],
            ],
        ];
    }
}
