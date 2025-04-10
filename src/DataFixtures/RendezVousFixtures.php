<?php

namespace App\DataFixtures;

use App\Entity\RendezVous;
use App\Entity\Patient;
use App\Enum\TypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RendezVousFixtures extends FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $rendezvous = new RendezVous();
            $rendezvous->setDate(new \DateTime());
            $rendezvous->setType(TypeEnum::CONSULTATION);
            $rendezvous->setPatient($this->getReference('patient_' . $i));
            $manager->persist($rendezvous);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PatientFixtures::class,
        ];
    }
}