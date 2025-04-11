<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\RendezVous;
use App\Enum\TypeEnum;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation extends RendezVous
{

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resultat = null;

    public function __construct()
    {
        $this->setType(TypeEnum::PRESTATION);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(?string $resultat): static
    {
        $this->resultat = $resultat;
        return $this;
    }
}