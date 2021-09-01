<?php

namespace App\Entity;

use App\Repository\GiftRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GiftRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Gift
{
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?\DateTimeImmutable $createdAt = null;
    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private ?string $description;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;
    /**
     * @ORM\Column(type="float")
     */
    private ?float $price;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?\DateTimeImmutable $updatedAt = null;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $uuid;

    /**
     * @ORM\ManyToOne(targetEntity=Factory::class, inversedBy="gifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Factory $factory;

    /**
     * @ORM\ManyToOne(targetEntity=Receiver::class, inversedBy="gifts")
     */
    private ?Receiver $receiver;

    /**
     * @ORM\ManyToOne(targetEntity=GiftCode::class, inversedBy="gifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?GiftCode $code;


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
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
        $this->setUpdatedAt(new \DateTimeImmutable());
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
    }

    public function getFactory(): ?Factory
    {
        return $this->factory;
    }

    public function setFactory(?Factory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    public function getReceiver(): ?Receiver
    {
        return $this->receiver;
    }

    public function setReceiver(?Receiver $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getCode(): ?GiftCode
    {
        return $this->code;
    }

    public function setCode(?GiftCode $code): self
    {
        $this->code = $code;

        return $this;
    }

}
