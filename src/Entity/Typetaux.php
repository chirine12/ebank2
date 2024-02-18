<?php

namespace App\Entity;

use App\Repository\TypetauxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypetauxRepository::class)]
class Typetaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Type ne doit pas être vide')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Type doit avoir au moins {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^\D+$/',
        message: 'Type ne doit contenir que des lettres'
    )]
    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Taux ne doit pas être vide')]
    #[Assert\Type(
        type: 'numeric',
        message: 'Taux doit être un nombre'
    )]
    private ?float $taux = null;

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
