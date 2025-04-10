<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PatientFixtures extends FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $patient = new Patient();
            $patient->setNom('Patient ' . $i);
            $patient->setPrenom('Prenom ' . $i);
            $patient->setCode(rand(1000, 2000));
            $patient->setAntecedentsMedicaux('Antecedents ' . rand(1, 4));
            $manager->persist($patient);
        }
        $manager->flush();
    }
}