<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: AntecedentMedical::class, cascade: ['persist', 'remove'])]
    private Collection $antecedentsMedicaux;

    public function __construct()
    {
        $this->antecedentsMedicaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAntecedentsMedicaux(): Collection
    {
        return $this->antecedentsMedicaux;
    }

    public function addAntecedentMedical(AntecedentMedical $antecedentMedical): static
    {
        if (!$this->antecedentsMedicaux->contains($antecedentMedical)) {
            $this->antecedentsMedicaux[] = $antecedentMedical;
            $antecedentMedical->setPatient($this);
        }

        return $this;
    }

    public function removeAntecedentMedical(AntecedentMedical $antecedentMedical): static
    {
        if ($this->antecedentsMedicaux->removeElement($antecedentMedical)) {
            if ($antecedentMedical->getPatient() === $this) {
                $antecedentMedical->setPatient(null);
            }
        }

        return $this;
    }
}
