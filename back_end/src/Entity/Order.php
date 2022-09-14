<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AdminOrderStatusController;
use App\Controller\OrderCreationController;
use App\Controller\OrderPaymentLinkController;
use App\Controller\OrderReturnCreationController;
use App\Controller\OrderUpdateStatusController;
use App\Dto\OrderInput;
use App\Dto\OrderOutput;
use App\Dto\OrderReturnInput;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ApiResource(
 *     attributes={
 *         "security": "is_granted('IS_AUTHENTICATED_FULLY')"
 *     },
 *     collectionOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": OrderOutput::class
 *         },
 *         "post": {
 *             "method": "POST",
 *             "input": OrderInput::class,
 *             "output": false,
 *             "write": false,
 *             "controller": OrderCreationController::class
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": OrderOutput::class
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "input": OrderInput::class,
 *             "output": OrderOutput::class,
 *             "controller": AdminOrderStatusController::class
 *         },
 *         "updateStatus": {
 *             "method": "POST",
 *             "path": "/orders/{orderReference}/update-status",
 *             "status": 200,
 *             "controller": OrderUpdateStatusController::class,
 *             "input": false,
 *             "output": OrderOutput::class,
 *             "openapi_context": {
 *                 "summary": "Update the status of the order.",
 *                 "description": "Update the status of the order."
 *             }
 *         },
 *         "getPaymentLink": {
 *             "method": "GET",
 *             "path": "/orders/PaymentLink/{orderReference}",
 *             "controller": OrderPaymentLinkController::class
 *         },
 *         "return": {
 *             "method": "POST",
 *             "path": "/orders/{orderReference}/return",
 *             "input": OrderReturnInput::class,
 *             "output": OrderOutput::class,
 *             "controller": OrderReturnCreationController::class,
 *             "openapi_context": {
 *                 "summary": "Create an order return.",
 *                 "description": "Create an order return."
 *             }
 *         }
 *     }
 * )
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReturned;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiProperty(identifier=true)
     */
    private $orderReference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeId;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="r_order", orphanRemoval=true)
     */
    private $orderItems;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderUser;

    /**
     * @ORM\OneToOne(targetEntity=OrderReturn::class, mappedBy="order_r")
     */
    private $orderReturn;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsReturned(): ?bool
    {
        return $this->isReturned;
    }

    public function setIsReturned(bool $isReturned): self
    {
        $this->isReturned = $isReturned;

        return $this;
    }

    public function getOrderReference(): ?string
    {
        return $this->orderReference;
    }

    public function setOrderReference(string $orderReference): self
    {
        $this->orderReference = $orderReference;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setROrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        // set the owning side to null (unless already changed)
        if ($this->orderItems->removeElement($orderItem) && $orderItem->getROrder() === $this) {
            $orderItem->setROrder(null);
        }

        return $this;
    }

    public function getOrderUser(): ?User
    {
        return $this->orderUser;
    }

    public function setOrderUser(?User $orderUser): self
    {
        $this->orderUser = $orderUser;

        return $this;
    }

    public function getOrderReturn(): ?OrderReturn
    {
        return $this->orderReturn;
    }

    public function setOrderReturn(OrderReturn $orderReturn): self
    {
        // set the owning side of the relation if necessary
        if ($orderReturn->getOrderR() !== $this) {
            $orderReturn->setOrderR($this);
        }

        $this->orderReturn = $orderReturn;

        return $this;
    }
}
