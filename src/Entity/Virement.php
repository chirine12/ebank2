<?php

namespace App\Entity;

use App\Repository\VirementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VirementRepository::class)]
class Virement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $source = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\Length(
        exactMessage: "Le destinataire doit contenir exactement 11 chiffres.",
        min: 11,
        max: 11,
    )]
   
    private ?string $destinataire = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 5,
        minMessage: "Le motif doit contenir au moins 5  caractères",
        // Vous pouvez aussi spécifier une longueur max si nécessaire
        max: 255,
        maxMessage: "Le motif ne peut pas être plus long que  255  caractères"
    )]
    private ?string $motif = null;

    #[ORM\ManyToOne(inversedBy: 'virement')]
    private ?Client $client = null;

    #[ORM\Column]
    
    #[Assert\Positive(
        message: "Le montant doit être positif."
    )]
    private ?float $montant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }
}
