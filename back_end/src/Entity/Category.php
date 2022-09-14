<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CategoryDeletionController;
use App\Dto\CategoryInput;
use App\Dto\CategoryOutput;
use App\Dto\CategoryPutInput;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "post": {
 *             "method": "POST",
 *             "input": CategoryInput::class,
 *             "output": CategoryOutput::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         },
 *         "get": {
 *             "method": "GET",
 *             "output": CategoryOutput::class
 *         }
 *
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": CategoryOutput::class
 *         },
 *         "put": {
 *             "method": "PUT",
 *             "input": CategoryPutInput::class,
 *             "output": CategoryOutput::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "input": CategoryInput::class,
 *             "output": CategoryOutput::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         },
 *         "delete": {
 *             "method": "DELETE",
 *             "controller": CategoryDeletionController::class,
 *             "security": "is_granted('ROLE_SELLER') or is_granted('ROLE_ADMIN')"
 *         }
 *     }
 * )
 */
class Category
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
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
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
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        // set the owning side to null (unless already changed)
        if ($this->products->removeElement($product) && $product->getCategory() === $this) {
            $product->setCategory(null);
        }

        return $this;
    }
}
