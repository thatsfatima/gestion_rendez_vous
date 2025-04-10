<?php

namespace App\DataFixtures;

use App\Entity\Medecin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MedecinFixtures extends FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $medecin = new Medecin();
            $medecin->setNom('Medecin ' . $i);
            if ($i % 2 == 0) {
                $medecin->setEstSpecialise(true);
            } else {
                $medecin->setEstSpecialise(false);
                $medecin->setSpecialite('Specialite ' . $i);
            }
            $manager->persist($medecin);
        }
        $manager->flush();
    }
}
