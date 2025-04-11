<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\AntecedantMedical;

class AntecedantMedicalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $antecedants = [
            'Diabète' => null,
            'Hypertension' => null,
            'Hépatite' => null,
            'Asthme' => null
        ];

        foreach ($antecedants as $nom => &$antecedant) {
            $ant = new AntecedantMedical();
            $ant->setLibelle($nom);
            $manager->persist($ant);
            $antecedant = $ant;
        }

        $manager->flush();
    }
}
