<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Consultation;
use App\Entity\Medecin;
use App\Entity\RendezVous;
use App\Entity\Patient;

class ConsultationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $medecin = new Medecin('medecin', 'medecin');
        $medecin->setNom('NomMedecin');
        $medecin->setPrenom('PrenomMedecin');
        $manager->persist($medecin);

        $consultation = new Consultation();
        $consultation->setTemperature('37.5');
        $consultation->setTension('120:8');
        $consultation->setPouls('80');
        $consultation->setMedecin($medecin);
        $manager->persist($consultation);

        $manager->flush();
    }
}
