<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Specialite;

class SpecialiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $specialites = [
            'Généraliste' => null,
            'Dentiste' => null,
            'Ophtalmologue' => null,
            'Cardiologue' => null
        ];

        foreach ($specialites as $nom => &$specialite) {
            $spec = new Specialite();
            $spec->setLibelle($nom);
            $manager->persist($spec);
            $specialite = $spec;
        }

        $manager->flush();
    }
}
