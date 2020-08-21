<?php

namespace App\Entity;

use App\Repository\CryptoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CryptoRepository::class)
 * @UniqueEntity("Title")
 * @UniqueEntity("shortcode")
 */
class Crypto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $Title;

    /**
     * @ORM\OneToMany(targetEntity=Mouvement::class, mappedBy="Crypto", orphanRemoval=true)
     */
    private $mouvements;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $shortcode;

    public function __construct()
    {
        $this->mouvements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    /**
     * @return Collection|Mouvement[]
     */
    public function getMouvements(): Collection
    {
        return $this->mouvements;
    }

    public function addMouvement(Mouvement $mouvement): self
    {
        if (!$this->mouvements->contains($mouvement)) {
            $this->mouvements[] = $mouvement;
            $mouvement->setCrypto($this);
        }

        return $this;
    }

    public function removeMouvement(Mouvement $mouvement): self
    {
        if ($this->mouvements->contains($mouvement)) {
            $this->mouvements->removeElement($mouvement);
            // set the owning side to null (unless already changed)
            if ($mouvement->getCrypto() === $this) {
                $mouvement->setCrypto(null);
            }
        }

        return $this;
    }

    public function getShortcode(): ?string
    {
        return $this->shortcode;
    }

    public function setShortcode(string $shortcode): self
    {
        $this->shortcode = $shortcode;

        return $this;
    }
}
