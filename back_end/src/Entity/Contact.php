<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ContactCreationController;
use App\Dto\ContactCreationInput;
use App\Dto\ContactOutput;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": ContactOutput::class,
 *             "security": "is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')",
 *             "order": {"sentAt": "DESC"}
 *         },
 *         "post": {
 *             "method": "POST",
 *             "input": ContactCreationInput::class,
 *             "output": ContactOutput::class,
 *             "controller": ContactCreationController::class
 *         }
 *     },
 *     itemOperations={
 *         "get": {
 *             "method": "GET",
 *             "output": ContactOutput::class,
 *             "security": "is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         },
 *         "delete": {
 *             "security": "is_granted('ROLE_ADMIN') or is_granted('ROLE_SELLER')"
 *         }
 *     }
 * )
 */
class Contact
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $sentAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }
}
