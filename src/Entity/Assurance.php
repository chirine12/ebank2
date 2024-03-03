<?php

namespace App\Entity;

use App\Repository\AssuranceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type(type: 'string', message: 'Le délais doit être une chaîne de caractères.')]
    private ?string $delais = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive(message: 'Le montant doit être positif.')]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'assurances')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDelais(): ?string
    {
        return $this->delais;
    }

    public function setDelais(string $delais): static
    {
        $this->delais = $delais;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
