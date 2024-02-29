<?php

namespace App\Entity;

use App\Repository\BeneficiaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: BeneficiaireRepository::class)]
#[UniqueEntity(fields: ["Rib"], message: "Ce RIB existe déjà.")]
class Beneficiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom doit contenir au moins  3  caractères',
        maxMessage: 'Le nom ne peut pas contenir plus de 100 caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le nom doit uniquement contenir des lettres'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le prénom doit contenir au moins {{ 3}} caractères',
        maxMessage: 'Le prénom ne peut pas contenir plus de {{100 }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le prénom doit uniquement contenir des lettres'
    )]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Rib = null;

    #[ORM\ManyToOne(inversedBy: 'benef')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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