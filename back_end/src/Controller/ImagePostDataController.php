<?php

namespace App\Controller;

use App\Entity\Image;
use App\Services\Google\GoogleDrive;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImagePostDataController extends AbstractController
{
    public function __invoke(Image $data, Request $request, GoogleDrive $googleDrive)
    {
        if (strlen($request->getContent()) / 1000 > 1000) {
            return new Response(null, 413);
        }
        $data = $googleDrive->pushImage($data, $request->getContent());
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}
