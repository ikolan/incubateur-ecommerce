<?php

namespace App\Tests\DataProvider;

trait ProductDataProviderTrait
{
    /**
     * Provide data for creating a new Category.
     */
    public static function ProductCreationData(): array
    {
        return [
            [
                [
                    'name' => 'Ordinateur',
                    'category' => '/api/categories/1',
                    'reference' => 'MSIRef1651As5161DfdD',
                    'price' => 1599,
                    'tax' => 15,
                    'description' => 'Une description pour un ordinateur',
                    'detailedDescription' => 'Une description looooooooooooooooooooooonnnnnnnnnnnnnnnnnnnnnnnnnnnnnguuee pour un ordinateur',
                    'weight' => 20,
                    'stock' => 15,
                    'frontPage' => true,
                ],
            ],
        ];
    }

    public static function ProductModificationData(): array
    {
        return [
            [
                [
                    'name' => 'PC Portable',
                ],
            ],
            [
                [
                    'name' => 'PC Gamer',
                    'price' => 35,
                ],
            ],
            [
                [
                    'description' => 'Une petite description pour changer',
                    'frontPage' => false,
                ],
            ],
        ];
    }

    public static function ProductFindByFilterData(): array
    {
        return [
            [
                [
                    'name' => 'Dell G15',
                    'category' => 'Processeurs',
                ],
                [
                    'statusCode' => 200,
                ],
            ],
            [
                [
                    'name' => 'Asus',
                    'category' => 'Processeurs',
                ],
                [
                    'statusCode' => 200,
                ],
            ],
            [
                [
                    'name' => 'iuesfhjdvbroihd',
                ],
                [
                    'statusCode' => 200,
                    'arraySize' => 0,
                ],
            ],
            ];
    }
}
