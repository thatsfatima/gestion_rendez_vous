<?php

namespace App\DataFixtures;

use App\Entity\Medecin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MedecinFixtures extends FixtureInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        
        $medecins = [
            ['Diop', 'Fatme', 'medecin1', 'password', $specialites['Généraliste']],
            ['Gackou', 'Bana', 'medecin2', 'password', $specialites['Dentiste']],
            ['Sall', 'Ndiaya', 'medecin3', 'password', $specialites['Ophtalmologue']]
        ];

        $medecinList = [];
        foreach ($medecins as $medecinData) {
            $medecin = new Medecin($medecinData[2], $medecinData[3]);
            $medecin->setNom($medecinData[0])
                ->setPrenom($medecinData[1])
                ->setPassword($this->passwordHasher->hashPassword($medecin, $medecinData[3]))
                ->setSpecialite($medecinData[4])
                ->setTelephone('77' . rand(1000000, 9999999));
            $manager->persist($medecin);
            $medecinList[] = $medecin;
        }

        $manager->flush();
    }
}
