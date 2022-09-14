<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserActivationController;
use App\Controller\UserCreationController;
use App\Controller\UserDeactivationController;
use App\Controller\UserGetByResetKeyController;
use App\Controller\UserGetCollectionController;
use App\Controller\UserLoginController;
use App\Controller\UserPasswordResetKeyCreationController;
use App\Controller\UserPatchController;
use App\Controller\UserReactivationRequestController;
use App\Dto\UserCreationInput;
use App\Dto\UserLoginInput;
use App\Dto\UserOutput;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ApiResource(
 *     collectionOperations={
 *         "post": {
 *             "method": "POST",
 *             "controller": UserCreationController::class,
 *             "input": UserCreationInput::class,
 *             "output": UserOutput::class
 *         },
 *         "get": {
 *             "method": "GET",
 *             "output": UserOutput::class,
 *             "read": false,
 *             "controller": UserGetCollectionController::class,
 *             "security": "is_granted('ROLE_ADMIN')"
 *         },
 *         "login": {
 *             "method": "POST",
 *             "status": 200,
 *             "path": "/users/login",
 *             "controller": UserLoginController::class,
 *             "input": UserLoginInput::class,
 *             "output": false,
 *             "openapi_context": {
 *                 "summary": "Login.",
 *                 "description": "Login.",
 *                 "requestBody": {
 *                     "content": {
 *                         "application/json": {
 *                             "schema": {
 *                                 "type": "object",
 *                                 "properties": {
 *                                     "email": {"type": "string"},
 *                                     "password": {"type": "string"},
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 },
 *                 "responses": {
 *                     "200": {
 *                         "description": "The newly generated JWT.",
 *                         "content": {
 *                             "application/json": {
 *                                 "schema": {
 *                                     "type": "object",
 *                                     "properties": {
 *                                         "token": {"type": "string"}
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     },
 *                 }
 *             }
 *         },
 *         "activate": {
 *             "method": "POST",
 *             "status": 200,
 *             "path": "/users/activate",
 *             "controller": UserActivationController::class,
 *             "input": false,
 *             "output": UserOutput::class,
 *             "openapi_context": {
 *                 "summary": "Activate the user.",
 *                 "description": "Activate the user.",
 *                 "requestBody": {
 *                     "content": {
 *                         "application/json": {
 *                             "schema": {
 *                                 "type": "object",
 *                                 "properties": {
 *                                     "activationKey": {"type": "string"}
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 },
 *                 "responses": {
 *                     "200": {
 *                         "description": "The user has been activated",
 *                     },
 *                 }
 *             }
 *         },
 *         "getByResetKey": {
 *             "method": "GET",
 *             "path": "/users/by-reset-key/{resetKey}",
 *             "input": false,
 *             "output": UserOutput::class,
 *             "read": false,
 *             "write": false,
 *             "controller": UserGetByResetKeyController::class,
 *             "openapi_context": {
 *                 "summary": "Retrieve a user by its password reset key.",
 *                 "description": "Retrieve a user by its password reset key.",
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": UserOutput::class,
 *         },
 *         "patch": {
 *             "method": "PATCH",
 *             "output": false,
 *             "controller": UserPatchController::class,
 *         },
 *         "resetKey": {
 *             "method": "POST",
 *             "path": "/users/{id}/reset-key",
 *             "input": false,
 *             "output": false,
 *             "controller": UserPasswordResetKeyCreationController::class,
 *             "openapi_context": {
 *                 "summary": "Create a reset key for the password.",
 *                 "description": "Create a reset key for the password.",
 *             }
 *         },
 *         "deactivate": {
 *             "method": "POST",
 *             "status": 200,
 *             "path": "users/{id}/deactivate",
 *             "input": false,
 *             "output": false,
 *             "controller": UserDeactivationController::class,
 *             "openapi_context": {
 *                 "summary": "Deactivate the user.",
 *                 "description": "Deactivate the user.",
 *             }
 *         },
 *         "reactivationRequest": {
 *             "method": "POST",
 *             "status": 200,
 *             "path": "/users/{id}/reactivation-request",
 *             "input": false,
 *             "output": UserOutput::class,
 *             "controller": UserReactivationRequestController::class,
 *             "openapi_context": {
 *                 "summary": "Send a request for reactivate the user.",
 *                 "description": "Send a request for reactivate the user.",
 *             }
 *         }
 *     }
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActivated;

    /**
     * @ORM\Column(type="uuid", length=255, nullable=true, unique=true)
     */
    private $activationKey;

    /**
     * @ORM\Column(type="uuid", length=255, nullable=true, unique=true)
     */
    private $resetKey;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity=Address::class, inversedBy="users")
     */
    private $addresses;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mainAddress;

    /**
     * @ORM\OneToMany(targetEntity=LineCart::class, mappedBy="cartUser", orphanRemoval=true)
     */
    private $cartLines;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="orderUser")
     * @ORM\JoinColumn(nullable=true)
     */
    private $orders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeId;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->cartLines = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

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

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getActivationKey(): ?string
    {
        return $this->activationKey;
    }

    public function setActivationKey(?string $activationKey): self
    {
        $this->activationKey = $activationKey;

        return $this;
    }

    public function getResetKey(): ?string
    {
        return $this->resetKey;
    }

    public function setResetKey(?string $resetKey): self
    {
        $this->resetKey = $resetKey;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $result = [];

        foreach ($this->roles as $role) {
            $result[] = $role->getLabel();
        }

        return $result;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @param string[] $roles
     */
    public function hasRole(array $roles): bool
    {
        $hasRole = 0;

        foreach ($roles as $role) {
            if (in_array($role, $this->getRoles())) {
                ++$hasRole;
            }
        }

        return $hasRole === count($roles);
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * Return true if the user is linked to an address.
     */
    public function isLinkedToAddressId($addressId): bool
    {
        $addressesIds = array_map(function (Address $a) {
            return $a->getId();
        }, $this->getAddresses()->toArray());

        return in_array($addressId, $addressesIds);
    }

    public function getMainAddress(): ?Address
    {
        return $this->mainAddress;
    }

    public function setMainAddress(?Address $mainAddress): self
    {
        $this->mainAddress = $mainAddress;

        return $this;
    }

    /**
     * @return Collection<int, LineCart>
     */
    public function getCartLines(): Collection
    {
        return $this->cartLines;
    }

    public function addCartLine(LineCart $cartLine): self
    {
        if (!$this->cartLines->contains($cartLine)) {
            $this->cartLines[] = $cartLine;
            $cartLine->setCartUser($this);
        }

        return $this;
    }

    public function removeCartLine(LineCart $cartLine): self
    {
        // set the owning side to null (unless already changed)
        if ($this->cartLines->removeElement($cartLine) && $cartLine->getCartUser() === $this) {
            $cartLine->setCartUser(null);
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
            $order->setOrderUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getOrderUser() === $this) {
                $order->setOrderUser(null);
            }
        }

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(?string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }
}
