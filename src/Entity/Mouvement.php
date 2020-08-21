<?php

namespace App\Entity;

use App\Repository\MouvementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MouvementRepository::class)
 */
class Mouvement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Crypto::class, inversedBy="mouvements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Crypto;

    /**
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cashback;

    /**
     * @ORM\Column(type="boolean")
     */
    private $brave;

    /**
     * @ORM\Column(type="boolean")
     */
    private $earn;

    /**
     * @ORM\Column(type="date")
     */
    private $date_made;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrypto(): ?Crypto
    {
        return $this->Crypto;
    }

    public function setCrypto(?Crypto $Crypto): self
    {
        $this->Crypto = $Crypto;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCashback(): ?bool
    {
        return $this->cashback;
    }

    public function setCashback(bool $cashback): self
    {
        $this->cashback = $cashback;

        return $this;
    }

    public function getBrave(): ?bool
    {
        return $this->brave;
    }

    public function setBrave(bool $brave): self
    {
        $this->brave = $brave;

        return $this;
    }

    public function getEarn(): ?bool
    {
        return $this->earn;
    }

    public function setEarn(bool $earn): self
    {
        $this->earn = $earn;

        return $this;
    }

    public function getDateMade(): ?\DateTimeInterface
    {
        return $this->date_made;
    }

    public function setDateMade(\DateTimeInterface $date_made): self
    {
        $this->date_made = $date_made;

        return $this;
    }
}
