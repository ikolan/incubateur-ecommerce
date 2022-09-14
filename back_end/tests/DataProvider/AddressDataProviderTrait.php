<?php

namespace App\Tests\DataProvider;

trait AddressDataProviderTrait
{
    public static function addressCreationData(): array
    {
        return [
            [
                [
                    'title' => 'Maison',
                    'number' => 42,
                    'road' => 'rue du Test',
                    'zipcode' => '59200',
                    'city' => 'Ville',
                    'phone' => '0123456789',
                ],
            ],
        ];
    }

    public static function addressModificationData(): array
    {
        return [
            [
                [
                    'title' => 'New title',
                ],
            ],
            [
                [
                    'number' => 42,
                    'road' => 'Rue du test',
                ],
            ],
            [
                [
                    'title' => 'Maison',
                    'number' => 42,
                    'road' => 'rue du Test',
                    'zipcode' => '59200',
                    'city' => 'Ville',
                    'phone' => '0123456789',
                ],
            ],
        ];
    }
}
