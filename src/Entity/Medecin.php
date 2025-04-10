<?php

namespace App\Entity;

use App\Repository\MedecinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedecinRepository::class)]
class Medecin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $estSpecialise = null;
    
    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Specialite::class, cascade: ['persist'])]
    private ?array $specialites = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function isEstSpecialise(): ?bool
    {
        return $this->estSpecialise;
    }
    
    public function setEstSpecialise(bool $estSpecialise): static
    {
        $this->estSpecialise = $estSpecialise;
        
        return $this;
    }
    
    public function getSpecialites(): ?array
    {
        return $this->specialites;
    }
    
    public function setSpecialites(?array $specialites): static
    {
        $this->specialites = $specialites;

        return $this;
    }

    public function addSpecialite(Specialite $specialite): static
    {
        if (!$this->specialites->contains($specialite)) {
            $this->specialites[] = $specialite;
            $specialite->setMedecin($this);
        }

        return $this;
    }

    public function removeSpecialite(Specialite $specialite): static
    {
        if ($this->specialites->removeElement($specialite)) {
            // set the owning side to null (unless already changed)
            if ($specialite->getMedecin() === $this) {
                $specialite->setMedecin(null);
            }
        }

        return $this;
    }
}