<?php

namespace App\Entity;

use App\Repository\ResponsablePrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsablePrestationRepository::class)]
class ResponsablePrestation extends User
{
    public function __construct($login, $password)
    {
        parent::__construct($login, $password);
        $this->setRoles(['ROLE_RESPONSABLE']);
    }
}