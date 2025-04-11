<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $code = null;
    
    #[ORM\ManyToMany(targetEntity: AntecedantMedical::class, inversedBy: 'patients')]
    private Collection $antecedantsMedicaux;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class)]
    private Collection $rendezVous;

    public function __construct($login, $password)
    {
        parent::__construct($login, $password);
        $this->antecedantsMedicaux = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
        $this->setRoles(['ROLE_PATIENT']);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getAntecedantsMedicaux(): Collection
    {
        return $this->antecedantsMedicaux;
    }

    public function addAntecedantMedical(AntecedantMedical $antecedantMedical): static
    {
        if (!$this->antecedantsMedicaux->contains($antecedantMedical)) {
            $this->antecedantsMedicaux->add($antecedantMedical);
        }

        return $this;
    }

    public function removeAntecedantMedical(AntecedantMedical $antecedantMedical): static
    {
        $this->antecedantsMedicaux->removeElement($antecedantMedical);
        return $this;
    }

    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->rendezVous->contains($rendezVou)) {
            $this->rendezVous->add($rendezVou);
            $rendezVou->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        if ($this->rendezVous->removeElement($rendezVou)) {
            if ($rendezVou->getPatient() === $this) {
                $rendezVou->setPatient(null);
            }
        }

        return $this;
    }
}