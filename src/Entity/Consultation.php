<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\RendezVous;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation extends RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $temperature = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tension = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pouls = null;

    #[ORM\ManyToOne(targetEntity: Medecin::class, inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private $medecin;

    #[ORM\OneToOne(targetEntity: RendezVous::class, inversedBy: 'consultation')]
    #[ORM\JoinColumn(nullable: false)]
    private $rendezVous;

    public function __construct()
    {
        $this->setType(TypeEnum::CONSULTATION);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(?string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getTension(): ?string
    {
        return $this->tension;
    }

    public function setTension(?string $tension): static
    {
        $this->tension = $tension;

        return $this;
    }

    public function getPouls(): ?string
    {
        return $this->pouls;
    }

    public function setPouls(?string $pouls): static
    {
        $this->pouls = $pouls;

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

    public function getRendezVous(): ?RendezVous
    {
        return $this->rendezVous;
    }

    public function setRendezVous(?RendezVous $rendezVous): static
    {
        $this->rendezVous = $rendezVous;
        return $this;
    }
}
