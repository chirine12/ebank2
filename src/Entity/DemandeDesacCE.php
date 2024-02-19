<?php

namespace App\Entity;

use App\Repository\DemandeDesacCERepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeDesacCERepository::class)]

class DemandeDesacCE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raison = null;

    #[ORM\ManyToOne(inversedBy: 'demandeDesacCEs')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'demandeDesacCEs')]
    private ?Compteep $compteep = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCompteep(): ?Compteep
    {
        return $this->compteep;
    }

    public function setCompteep(?Compteep $compteep): static
    {
        $this->compteep = $compteep;

        return $this;
    }
}
