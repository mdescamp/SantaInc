<?php

namespace App\Entity;

use App\Repository\ReceiverRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=ReceiverRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Receiver
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $country;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $createdAt = null;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $firstName;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $lastName;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $updatedAt = null;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $uuid;

    /**
     * @ORM\OneToMany(targetEntity=Gift::class, mappedBy="receiver")
     */
    private $gifts;

    #[Pure] public function __construct()
    {
        $this->gifts = new ArrayCollection();
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = ucfirst($firstName);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = ucfirst($lastName);

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable());
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
    }

    /**
     * @return Collection|Gift[]
     */
    public function getGifts(): Collection
    {
        return $this->gifts;
    }

    public function addGift(Gift $gift): self
    {
        if (!$this->gifts->contains($gift)) {
            $this->gifts[] = $gift;
            $gift->setReceiver($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        // set the owning side to null (unless already changed)
        if ($this->gifts->removeElement($gift) && $gift->getReceiver() === $this) {
            $gift->setReceiver(null);
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->lastName . ' ' . $this->firstName;
    }
}
