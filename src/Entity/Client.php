<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

   

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $daten = null;

    #[ORM\Column(length: 255)]
    private ?string $addresse = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datenaissance = null;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?comptecourant $comptecourant = null;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: compteep::class, mappedBy: 'client')]
    private Collection $compteep;

    #[ORM\OneToMany(targetEntity: virement::class, mappedBy: 'client')]
    private Collection $virement;

    #[ORM\OneToMany(targetEntity: assurance::class, mappedBy: 'client')]
    private Collection $assurance;

    #[ORM\OneToMany(targetEntity: credit::class, mappedBy: 'client')]
    private Collection $credit;

    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'client')]
    private Collection $contrat;

    public function __construct()
    {
        $this->compteep = new ArrayCollection();
        $this->virement = new ArrayCollection();
        $this->assurance = new ArrayCollection();
        $this->credit = new ArrayCollection();
        $this->contrat = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

   

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getDaten(): ?string
    {
        return $this->daten;
    }

    public function setDaten(string $daten): static
    {
        $this->daten = $daten;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(string $addresse): static
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): static
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getComptecourant(): ?comptecourant
    {
        return $this->comptecourant;
    }

    public function setComptecourant(comptecourant $comptecourant): static
    {
        $this->comptecourant = $comptecourant;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, compteep>
     */
    public function getCompteep(): Collection
    {
        return $this->compteep;
    }

    public function addCompteep(compteep $compteep): static
    {
        if (!$this->compteep->contains($compteep)) {
            $this->compteep->add($compteep);
            $compteep->setClient($this);
        }

        return $this;
    }

    public function removeCompteep(compteep $compteep): static
    {
        if ($this->compteep->removeElement($compteep)) {
            // set the owning side to null (unless already changed)
            if ($compteep->getClient() === $this) {
                $compteep->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, virement>
     */
    public function getVirement(): Collection
    {
        return $this->virement;
    }

    public function addVirement(virement $virement): static
    {
        if (!$this->virement->contains($virement)) {
            $this->virement->add($virement);
            $virement->setClient($this);
        }

        return $this;
    }

    public function removeVirement(virement $virement): static
    {
        if ($this->virement->removeElement($virement)) {
            // set the owning side to null (unless already changed)
            if ($virement->getClient() === $this) {
                $virement->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, assurance>
     */
    public function getAssurance(): Collection
    {
        return $this->assurance;
    }

    public function addAssurance(assurance $assurance): static
    {
        if (!$this->assurance->contains($assurance)) {
            $this->assurance->add($assurance);
            $assurance->setClient($this);
        }

        return $this;
    }

    public function removeAssurance(assurance $assurance): static
    {
        if ($this->assurance->removeElement($assurance)) {
            // set the owning side to null (unless already changed)
            if ($assurance->getClient() === $this) {
                $assurance->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, credit>
     */
    public function getCredit(): Collection
    {
        return $this->credit;
    }

    public function addCredit(credit $credit): static
    {
        if (!$this->credit->contains($credit)) {
            $this->credit->add($credit);
            $credit->setClient($this);
        }

        return $this;
    }

    public function removeCredit(credit $credit): static
    {
        if ($this->credit->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getClient() === $this) {
                $credit->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrat(): Collection
    {
        return $this->contrat;
    }

    public function addContrat(Contrat $contrat): static
    {
        if (!$this->contrat->contains($contrat)) {
            $this->contrat->add($contrat);
            $contrat->setClient($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrat->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getClient() === $this) {
                $contrat->setClient(null);
            }
        }

        return $this;
    }
}
