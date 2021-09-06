<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\GiftCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=GiftCodeRepository::class)
 */
class GiftCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $code;

    /**
     * @ORM\OneToMany(targetEntity=Gift::class, mappedBy="code", orphanRemoval=true)
     */
    private ArrayCollection|PersistentCollection $gifts;

    #[Pure] public function __construct()
    {
        $this->gifts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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
            $gift->setCode($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        // set the owning side to null (unless already changed)
        if ($this->gifts->removeElement($gift) && $gift->getCode() === $this) {
            $gift->setCode(null);
        }

        return $this;
    }
}
