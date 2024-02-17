<?php

namespace App\Entity;

use App\Repository\CompteepRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CompteepRepository::class)]
class Compteep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Rib = null;

    #[ORM\Column]
    private ?float $solde = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateouv = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description ne doit pas etre vide')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Description doit avoir au min{ limit }} caractheres',
        maxMessage: 'Description ne doit pas avir plus que  {{ limit }} caractheres'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-z]+$/',
        message: 'Description doit avoir seulement des lettres'
    )]
    private ?string $description = null;


    #[ORM\ManyToOne(inversedBy: 'compteep')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
