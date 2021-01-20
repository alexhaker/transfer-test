<?php

namespace App\Entity;

use App\Repository\TransferRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransferRepository::class)
 */
class MoneyTransfer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="transfers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sourceWallet;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $destinationWallet;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\Column(type="integer")
     */
    private $commission;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceWallet(): ?Wallet
    {
        return $this->sourceWallet;
    }

    public function setSourceWallet(?Wallet $sourceWallet): self
    {
        $this->sourceWallet = $sourceWallet;

        return $this;
    }

    public function getDestinationWallet(): ?Wallet
    {
        return $this->destinationWallet;
    }

    public function setDestinationWallet(?Wallet $destinationWallet): self
    {
        $this->destinationWallet = $destinationWallet;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCommission(): ?int
    {
        return $this->commission;
    }

    public function setCommission(int $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
