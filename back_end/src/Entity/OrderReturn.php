<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\OrderReturnOutput;
use App\Repository\OrderReturnRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderReturnRepository::class)
 * @ApiResource(
 *     attributes={
 *         "security": "is_granted('IS_AUTHENTICATED_FULLY')"
 *     },
 *     collectionOperations={
 *         "get": {
             "security": "is_granted('ROLE_ADMIN')",
 *             "output": OrderReturnOutput::class,
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "output": OrderReturnOutput::class,
 *         },
 *         "delete": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *         }
 *     }
 * )
 */
class OrderReturn
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
    private $reason;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, inversedBy="orderReturn")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_r;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getOrderR(): ?Order
    {
        return $this->order_r;
    }

    public function setOrderR(Order $order_r): self
    {
        $this->order_r = $order_r;

        return $this;
    }
}
