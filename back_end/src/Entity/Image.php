<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\ImageDeletionController;
use App\Controller\ImageGetDataController;
use App\Controller\ImagePostDataController;
use App\Dto\ImageInput;
use App\Dto\ImageOutput;
use App\IdGenerator\UuidGenerator;
use App\Repository\ImageRepository;
use App\Validator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ApiResource(
 *     attributes={
 *         "security": "is_granted('ROLE_SELLER')"
 *     },
 *     collectionOperations={
 *         "get",
 *         "post": {
 *             "input": ImageInput::class,
 *             "output": ImageOutput::class,
 *         },
 *     },
 *     itemOperations={
 *         "get": {
 *             "output": ImageOutput::class,
 *         },
 *         "getData": {
 *             "method": "GET",
 *             "path": "/images/{id}/data",
 *             "security": "is_granted('PUBLIC_ACCESS')",
 *             "output": false,
 *             "controller": ImageGetDataController::class,
 *             "cache_headers": {
 *                 "max_age": 3600,
 *                 "shared_max_age": 3600,
 *                 "vary": {"Authorization"},
 *             },
 *             "openapi_context": {
 *                 "summary": "Get image data.",
 *                 "description": "Get image data.",
 *             }
 *         },
 *         "postData": {
 *             "method": "POST",
 *             "path": "/images/{id}/data",
 *             "input": false,
 *             "output": ImageOutput::class,
 *             "controller": ImagePostDataController::class,
 *             "input_formats": {
 *                 "png": {"image/png"},
 *                 "jpeg": {"image/jpeg"}
 *             },
 *             "openapi_context": {
 *                 "summary": "Send image data.",
 *                 "description": "Send image data.",
 *             }
 *         },
 *         "delete": {
 *             "controller": ImageDeletionController::class,
 *         }
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $googleFileId;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Validator\NotAlreadyExistImageName
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="image")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGoogleFileId(): ?string
    {
        return $this->googleFileId;
    }

    public function setGoogleFileId(?string $googleFileId): self
    {
        $this->googleFileId = $googleFileId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
}
