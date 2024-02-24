<?php

namespace App\Entity;

use App\Repository\CreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CreditRepository::class)]
class Credit
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Type("alpha", message: "Le champ ne doit contenir que des lettres")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Assert\NotBlank]
    #[Assert\Type("numeric", message: "Le champ ne doit contenir que des chiffres")]
    #[ORM\Column]
    private ?float $montant = null;

    #[Assert\NotBlank]
    #[Assert\Type("numeric", message: "Le champ ne doit contenir que des chiffres")]
    #[ORM\Column]
    private ?float $payement = null;

    #[Assert\NotBlank]
    #[Assert\Type("numeric", message: "Le champ ne doit contenir que des chiffres")]
    #[ORM\Column]
    private ?int $duree = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedeb = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datefin = null;

    
    #[ORM\ManyToOne(inversedBy: 'credit')]
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPayement(): ?float
    {
        return $this->payement;
    }

    public function setPayement(float $payement): static
    {
        $this->payement = $payement;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDatedeb(\DateTimeInterface $datedeb): static
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

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
