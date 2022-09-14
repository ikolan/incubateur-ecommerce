<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AddressCollectionController;
use App\Controller\AddressCreationController;
use App\Controller\AddressDeletionController;
use App\Controller\AddressModificationController;
use App\Controller\AddressObtentionController;
use App\Controller\MainAddressObtentionController;
use App\Controller\MainAddressSetterController;
use App\Dto\AddressCreationInput;
use App\Dto\AddressDeletionInput;
use App\Dto\AddressInput;
use App\Dto\AddressOutput;
use App\IdGenerator\UuidGenerator;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 * @ApiResource(
 *     attributes={
 *         "security": "is_granted('IS_AUTHENTICATED_FULLY')"
 *     },
 *     collectionOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": AddressOutput::class,
 *             "controller": AddressCollectionController::class,
 *             "openapi_context": {
 *                 "summary": "Retrieves the collection of Address resources of the connected user.",
 *                 "description": "Retrieves the collection of Address resources of the connected user."
 *             }
 *         },
 *         "getMain": {
 *             "method": "GET",
 *             "path": "/addresses/main",
 *             "output": AddressOutput::class,
 *             "controller": MainAddressObtentionController::class,
 *             "openapi_context": {
 *                 "summary": "Retrieves the main Address resources of the connected user.",
 *                 "description": "Retrieves the main Address resources of the connected user."
 *             }
 *         },
 *         "post": {
 *             "method": "POST",
 *             "input": AddressCreationInput::class,
 *             "output": AddressOutput::class,
 *             "write": false,
 *             "controller": AddressCreationController::class
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": AddressOutput::class,
 *             "controller": AddressObtentionController::class
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "input": AddressInput::class,
 *             "output": AddressOutput::class,
 *             "controller": AddressModificationController::class
 *         },
 *         "patchMain": {
 *             "method": "PATCH",
 *             "path": "/addresses/{id}/main",
 *             "input": false,
 *             "output": AddressOutput::class,
 *             "controller": MainAddressSetterController::class
 *         },
 *         "delete": {
 *             "method": "DELETE",
 *             "input": AddressDeletionInput::class,
 *             "output": false,
 *             "controller": AddressDeletionController::class,
 *             "write": false
 *         }
 *     }
 * )
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @ORM\Column(type="uuid", unique=true)
     * @Groups({"tests"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tests"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"tests"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tests"})
     */
    private $road;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tests"})
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tests"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"tests"})
     */
    private $phone;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="addresses")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="address")
     */
    private $orders;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    /**
     * Copy the current address to a new one.
     */
    public function newCopy()
    {
        return (new Address())
            ->setTitle($this->getTitle())
            ->setNumber($this->getNumber())
            ->setRoad($this->getRoad())
            ->setZipcode($this->getZipcode())
            ->setCity($this->getCity())
            ->setPhone($this->getPhone());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getRoad(): ?string
    {
        return $this->road;
    }

    public function setRoad(string $road): self
    {
        $this->road = $road;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAddress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAddress($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setAddress($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        // set the owning side to null (unless already changed)
        if ($this->orders->removeElement($order) && $order->getAddress() === $this) {
            $order->setAddress(null);
        }

        return $this;
    }

    /**
     * Return true if this address have relation with more of $n user or more of 0 order.
     *
     * @param $n The max user ammount
     */
    public function dependOnSomething(int $n = 0): bool
    {
        return count($this->getUsers()) > $n || count($this->getOrders()) > 0;
    }
}
