<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Medicament;

class MedicamentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $medicaments = [
            'PAR001' => 'Paracétamol',
            'IBS002' => 'Ibuprofène',
            'DOL003' => 'Doliprane',
            'AUG004' => 'Augmentin'
        ];

        $medList = [];
        foreach ($medicaments as $code => $nom) {
            $med = new Medicament();
            $med->setCode($code)
                ->setNom($nom);
            $manager->persist($med);
            $medList[] = $med;
        }

        $manager->flush();
    }
}