<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ChequeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChequeRepository::class)]
class Cheque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank(message:"Numero du cheque est necessaire")]
    private ?string $num = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank(message:"Numero du compte est necessaire")]
    #[Assert\Length(min:11,max:11),]
    private ?string $numcompte = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Montant est necessaire")]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
   
    private ?string $signature = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signatureId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signerId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdfSansSignature = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getNumcompte(): ?string
    {
        return $this->numcompte;
    }

    public function setNumcompte(string $numcompte): static
    {
        $this->numcompte = $numcompte;

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

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): static
    {
        $this->signature = $signature;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSignatureId(): ?string
    {
        return $this->signatureId;
    }

    public function setSignatureId(?string $signatureId): static
    {
        $this->signatureId = $signatureId;

        return $this;
    }

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function setDocumentId(?string $documentId): static
    {
        $this->documentId = $documentId;

        return $this;
    }

    public function getSignerId(): ?string
    {
        return $this->signerId;
    }

    public function setSignerId(?string $signerId): static
    {
        $this->signerId = $signerId;

        return $this;
    }

    public function getPdfSansSignature(): ?string
    {
        return $this->pdfSansSignature;
    }

    public function setPdfSansSignature(?string $pdfSansSignature): static
    {
        $this->pdfSansSignature = $pdfSansSignature;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }
}
