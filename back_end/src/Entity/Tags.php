<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\TagsCreationInput;
use App\Dto\TagsOutput;
use App\Dto\TagsPutInput;
use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "post": {
 *             "method": "POST",
 *             "input": TagsCreationInput::class,
 *             "output": TagsOutput::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         },
 *         "get": {
 *             "method": "GET",
 *             "output": TagsOutput::class
 *         }
 *
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": TagsOutput::class
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "input": TagsPutInput::class,
 *             "output": TagsOutput::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         },
 *         "put": {
 *             "method": "PUT",
 *             "input": TagsPutInput::class,
 *             "output": TagsOutput::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         },
 *         "delete": {
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         }
 *     }
 * )
 */
class Tags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="tags")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addTag($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeTag($this);
        }

        return $this;
    }
}
