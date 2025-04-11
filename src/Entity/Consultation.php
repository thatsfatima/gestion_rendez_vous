<?php

namespace App\Entity;

use App\Enum\TypeEnum;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation extends RendezVous
{
    #[ORM\Column(nullable: true)]
    private ?float $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?string $tension = null;

    #[ORM\Column(nullable: true)]
    private ?int $pouls = null;

    #[ORM\OneToOne(mappedBy: 'consultation', cascade: ['persist', 'remove'])]
    private ?Ordonnance $ordonnance = null;

    public function __construct()
    {
        $this->setType(TypeEnum::PRESTATION);
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): static
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

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): static
    {
        if ($ordonnance === null && $this->ordonnance !== null) {
            $this->ordonnance->setConsultation(null);
        }

        if ($ordonnance !== null && $ordonnance->getConsultation() !== $this) {
            $ordonnance->setConsultation($this);
        }

        $this->ordonnance = $ordonnance;
        return $this;
    }

}