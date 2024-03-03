<?php

    namespace App\Entity;

    use App\Repository\ContratRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\HttpFoundation\File\File;
    use Symfony\Component\Validator\Constraints as Assert;
    use Vich\UploaderBundle\Mapping\Annotation as Vich;

    #[ORM\Entity(repositoryClass: ContratRepository::class)]
    #[Vich\Uploadable]
    class Contrat
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(type: Types::DATE_MUTABLE)]
        private ?\DateTimeInterface $datedeb = null;

        #[ORM\Column(type: Types::DATE_MUTABLE)]
        private ?\DateTimeInterface $datefin = null;

        #[ORM\Column(length: 255)]
        private ?string $signatureFile = null;

        #[ORM\Column(length: 255)]
        private ?string $type = null;

        #[ORM\ManyToOne(inversedBy: 'contrats')]
        private ?Client $client = null;

        #[ORM\Column]
        private ?int $phoneNumber = null;


        public function getId(): ?int
        {
            return $this->id;
        }

        public function getDatedeb(): ?\DateTimeInterface
        {
            return $this->datedeb;
        }

        public function setDatedeb(\DateTimeInterface $datedeb): self
        {
            $this->datedeb = $datedeb;

            return $this;
        }

        public function getDatefin(): ?\DateTimeInterface
        {
            return $this->datefin;
        }

        public function setDatefin(\DateTimeInterface $datefin): self
        {
            $this->datefin = $datefin;

            return $this;
        }

        public function getSignatureFile(): ?string
        {
            return $this->signatureFile;
        }

        public function setSignatureFile(string $signatureFile): static
        {
            $this->signatureFile = $signatureFile;

            return $this;
        }

        public function getPhoneNumber(): ?int
        {
            return $this->phoneNumber;
        }

        public function setPhoneNumber(int $phoneNumber): self
        {
            $this->phoneNumber = $phoneNumber;

            return $this;
        }
        public function getType(): ?string
        {
            return $this->type;
        }

        public function setType(string $type): self
        {
            $this->type = $type;

            return $this;
        }

        public function getClient(): ?Client
        {
            return $this->client;
        }

        public function setClient(?Client $client): self
        {
            $this->client = $client;

            return $this;
        }

       


    }
