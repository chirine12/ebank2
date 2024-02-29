<?php

namespace App\Entity;

use App\Repository\InvestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestRepository::class)]
class Invest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $idclient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crypto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    #[ORM\Column(nullable: true)]
    private ?int $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdclient(): ?int
    {
        return $this->idclient;
    }

    public function setIdclient(?int $idclient): static
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getCrypto(): ?string
    {
        return $this->crypto;
    }

    public function setCrypto(?string $crypto): static
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
