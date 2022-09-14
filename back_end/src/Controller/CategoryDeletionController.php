<?php

namespace App\Controller;

use App\Entity\Category;

class CategoryDeletionController extends AbstractController
{
    public function __invoke(Category $data)
    {
        foreach ($data->getProducts() as $product) {
            $product->setCategory(null);
        }

        $this->em->flush();

        return $data;
    }
}
