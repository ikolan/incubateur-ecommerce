<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\LineCartCreationController;
use App\Controller\LineCartDeleteCollectionController;
use App\Controller\LineCartGetCollectionController;
use App\Dto\LineCartInput;
use App\Dto\LineCartOutput;
use App\Repository\LineCartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LineCartRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "post": {
 *             "method": "POST",
 *             "input": false,
 *             "controller": LineCartCreationController::class,
 *             "output": LineCartOutput::class,
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         },
 *         "get": {
 *             "method": "GET",
 *             "output": LineCartOutput::class,
 *             "controller": LineCartGetCollectionController::class,
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         },
 *         "delete": {
 *             "method": "DELETE",
 *             "controller": LineCartDeleteCollectionController::class,
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": LineCartOutput::class,
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "input": LineCartInput::class,
 *             "output": LineCartOutput::class,
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         },
 *         "delete": {
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         }
 *     }
 * )
 */
class LineCart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cartLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cartUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCartUser(): ?User
    {
        return $this->cartUser;
    }

    public function setCartUser(?User $cartUser): self
    {
        $this->cartUser = $cartUser;

        return $this;
    }
}
