<?php

namespace App\Controller;

use App\Entity\Image;
use App\Services\Google\GoogleDrive;
use Symfony\Component\HttpFoundation\Response;

class ImageGetDataController extends AbstractController
{
    public function __invoke(Image $data, GoogleDrive $googleDrive)
    {
        $image = $googleDrive->fetchImage($data);

        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', $image[0]);
        $response->setContent($image[1]);

        return $response;
    }
}
