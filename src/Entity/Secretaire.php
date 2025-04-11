<?php

namespace App\Entity;

use App\Repository\SecretaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecretaireRepository::class)]
class Secretaire extends User
{
    public function __construct($login, $password)
    {
        parent::__construct($login, $password);
        $this->setRoles(['ROLE_SECRETAIRE']);
    }
}