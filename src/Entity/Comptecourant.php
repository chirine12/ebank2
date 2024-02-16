<?php

namespace App\Entity;

use App\Repository\ComptecourantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComptecourantRepository::class)]
class Comptecourant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateouv = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Rib = null;

    #[ORM\Column]
    private ?float $solde = null;

    #[ORM\OneToOne(mappedBy: 'comptecourant', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateouv(): ?\DateTimeInterface
    {
        return $this->dateouv;
    }

    public function setDateouv(\DateTimeInterface $dateouv): static
    {
        $this->dateouv = $dateouv;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->Rib;
    }

    public function setRib(string $Rib): static
    {
        $this->Rib = $Rib;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): static
    {
        $this->solde = $solde;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        // set the owning side of the relation if necessary
        if ($client->getComptecourant() !== $this) {
            $client->setComptecourant($this);
        }

        $this->client = $client;

        return $this;
    }
}
