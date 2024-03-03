<?php

namespace App\Entity;

use App\Repository\TypecreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(targetEntity: Credit::class, mappedBy: 'Typecredit')]
    private Collection $credits;

    #[ORM\OneToMany(targetEntity: Credit::class, mappedBy: 'Typecredit')]
    private Collection $credit;

    public function __construct()
    {
        $this->credits = new ArrayCollection();
        $this->credit = new ArrayCollection();
    }


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

    /**
     * @return Collection<int, Credit>
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): static
    {
        if (!$this->credits->contains($credit)) {
            $this->credits->add($credit);
            $credit->setTypecredit($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): static
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getTypecredit() === $this) {
                $credit->setTypecredit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Credit>
     */
    public function getCredit(): Collection
    {
        return $this->credit;
    }

    
    
    
}
