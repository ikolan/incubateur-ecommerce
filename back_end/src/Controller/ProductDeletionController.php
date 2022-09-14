<?php

namespace App\Controller;

use App\Entity\Product;

class ProductDeletionController extends AbstractController
{
    public function __invoke(Product $data)
    {
        if ($data->getOrderItems()->count() > 0) {
            $data->setIsDeleted(true);
            $this->em->flush();
        } else {
            $this->productRepository->remove($data);
        }
    }
}
