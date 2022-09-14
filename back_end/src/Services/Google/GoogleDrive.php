<?php

namespace App\Services\Google;

use App\Entity\Image;
use Google\Service\Drive\DriveFile;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Class to comunicate with Google Drive.
 */
class GoogleDrive
{
    /** @var ContainerBagInterface */
    private $params;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * Get the service instance for Google Drive.
     *
     * @throws \Google\Exception
     */
    public function getService(): \Google_Service_Drive
    {
        $client = new \Google_Client();
        $client->setApplicationName('Ecommerce');
        $client->setAuthConfig($this->params->get('kernel.project_dir').$_ENV['GOOGLE_AUTH_CONFIG']);
        $client->setScopes('https://www.googleapis.com/auth/drive');

        return new \Google_Service_Drive($client);
    }

    /**
     * Send an image to Google Drive.
     *
     * @param Image  $image The image metadata (The google id will be filled)
     * @param string $data  the data of the image
     *
     * @throws \Google\Exception
     */
    public function pushImage(Image $image, string $data): Image
    {
        $service = $this->getService();
        $file = new DriveFile();
        $file->setName($image->getId());
        $file->setParents([$_ENV['GOOGLE_DRIVE_PARENT_ID']]);

        $sendedFile = $service->files->create($file, ['data' => $data, 'fields' => 'id']);
        $image->setGoogleFileId($sendedFile->getId());

        return $image;
    }

    /**
     * Get an image from Google Drive.
     *
     * @return string[]|null the mime type (index 0) & the data (index 1) of the image
     */
    public function fetchImage(Image $image): ?array
    {
        if (null === $image->getGoogleFileId()) {
            return null;
        }

        $service = $this->getService();
        $fileMetadata = $service->files->get($image->getGoogleFileId(), ['fields' => 'size,mimeType']);
        $fileSize = intval($fileMetadata->getSize());
        $fileMimeType = $fileMetadata->getMimeType();

        return [
            $fileMimeType,
            $service->files->get($image->getGoogleFileId(), ['alt' => 'media'])->getBody()->read($fileSize),
        ];
    }

    /**
     * Delete an image from Google Drive.
     *
     * @param Image $image The image metadata (The google id will be cleared)
     *
     * @throws \Google\Exception
     */
    public function deleteImage(Image $image): ?Image
    {
        if (null === $image->getGoogleFileId()) {
            return null;
        }

        $service = $this->getService();
        try {
            $service->files->delete($image->getGoogleFileId());
        } catch (\Exception $e) {
        }
        $image->setGoogleFileId(null);

        return $image;
    }
}
