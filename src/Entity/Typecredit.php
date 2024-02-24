<?php

namespace App\Entity;

use App\Repository\TypecreditRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypecreditRepository::class)]
class Typecredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Type("alpha", message: "Le champ ne doit contenir que des lettres")]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?float $taux = null;

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

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): static
    {
        $this->taux = $taux;

        return $this;
    }
}
