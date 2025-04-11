<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\TypeEnum;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type_rv", type: "string")]
#[ORM\DiscriminatorMap([
    "consultation" => Consultation::class,
    "prestation" => Prestation::class
])]
abstract class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date;

    #[ORM\Column(enumType: TypeEnum::class)]
    private TypeEnum $type = TypeEnum::CONSULTATION;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    private ?Medecin $medecin = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'demande'; 
    // demande, valide, annule

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?TypeEnum
    {
        return $this->type;
    }

    public function setType(TypeEnum $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;
        return $this;
    }

    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }

    public function setMedecin(?Medecin $medecin): static
    {
        $this->medecin = $medecin;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function peutEtreAnnule(): bool
    {
        $now = new \DateTime();
        $interval = $now->diff($this->date);
        $heures = $interval->days * 24 + $interval->h;
        
        return $heures >= 48;
    }
}