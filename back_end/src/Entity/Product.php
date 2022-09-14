<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\ProductDeletionController;
use App\Controller\ProductGetCollectionController;
use App\Controller\ProductGetCollectionPriceSlice;
use App\Dto\ProductCreationInput;
use App\Dto\ProductInput;
use App\Dto\ProductOutput;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "post": {
 *             "method": "POST",
 *             "input": ProductCreationInput::class,
 *             "output": ProductOutput::class
 *         },
 *         "get": {
 *             "method": "GET",
 *             "output": ProductOutput::class,
 *             "controller": ProductGetCollectionController::class
 *         },
 *         "priceSlice": {
 *             "method": "GET",
 *             "path": "/products/price-slice",
 *             "read": false,
 *             "write": false,
 *             "output": false,
 *             "controller": ProductGetCollectionPriceSlice::class
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": ProductOutput::class,
 *             "openapi_context": {
 *                 "responses": {
 *                     "404": {
 *                         "description": "Aucun produit retrouvé."
 *                     }
 *                 }
 *             }
 *         },
 *         "put": {
 *             "method": "PUT",
 *             "input": ProductInput::class,
 *             "output": ProductOutput::class
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "input": ProductInput::class,
 *             "output": ProductOutput::class
 *         },
 *         "delete": {
 *             "method": "DELETE",
 *             "output": false,
 *             "controller": ProductDeletionController::class,
 *         }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "ipartial", "category.label": "exact", "tags.label": "ipartial"})
 * @ApiFilter(RangeFilter::class, properties={"stock", "price"})
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $tax;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $detailedDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $addedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $frontPage;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tags")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="products")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="products")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted = false;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="product")
     */
    private $orderItems;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTax(): ?int
    {
        return $this->tax;
    }

    public function setTax(int $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDetailedDescription(): ?string
    {
        return $this->detailedDescription;
    }

    public function setDetailedDescription(string $detailedDescription): self
    {
        $this->detailedDescription = $detailedDescription;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getFrontPage(): ?bool
    {
        return $this->frontPage;
    }

    public function setFrontPage(bool $frontPage): self
    {
        $this->frontPage = $frontPage;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }
}
