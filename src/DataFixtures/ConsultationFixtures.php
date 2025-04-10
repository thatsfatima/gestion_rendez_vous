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
        $medecin = new Medecin();
        $medecin->setNom('Doe');
        $medecin->setPrenom('John');
        $manager->persist($medecin);

        $rendezVous = $manager->getRepository(RendezVous::class)->findOneBy(['id' => 1]);

        $consultation = new Consultation();
        $consultation->setTemperature('37.5');
        $consultation->setTension('120/80');
        $consultation->setPouls('80');
        $consultation->setMedecin($medecin);
        $consultation->setRendezVous($rendezVous);
        $manager->persist($consultation);

        $manager->flush();
    }
}
