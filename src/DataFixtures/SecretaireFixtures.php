<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Secretaire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecretaireFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $secretaire = new Secretaire('admin0', 'admin0');
        $secretaire->setNom('Admin0')
            ->setPrenom('System0')
            ->setTelephone('775101010')
            ->setPassword($this->passwordHasher->hashPassword($secretaire, 'admin'));
        $manager->persist($secretaire);

        $manager->flush();
    }
}
