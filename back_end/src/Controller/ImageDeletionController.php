<?php

namespace App\Controller;

use App\Entity\Image;
use App\Services\Google\GoogleDrive;

class ImageDeletionController extends AbstractController
{
    public function __invoke(Image $data, GoogleDrive $googleDrive)
    {
        foreach ($data->getProducts() as $product) {
            $product->setImage(null);
        }

        if (null !== $data->getGoogleFileId()) {
            $data = $googleDrive->deleteImage($data);
        }
        $this->imageRepository->remove($data);
        $this->em->flush();
    }
}
