<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\AntecedentMedical;

class AntecedantMedicalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $antecedentMedical1 = new AntecedentMedical();
        $antecedentMedical1->setLibelle('Diabète');

        $antecedentMedical2 = new AntecedentMedical();
        $antecedentMedical2->setLibelle('Hypertension');

        $antecedentMedical3 = new AntecedentMedical();
        $antecedentMedical3->setLibelle('Allergie médicamenteuse');

        $antecedentMedical4 = new AntecedentMedical();
        $antecedentMedical4->setLibelle('AVC');

        $manager->persist($antecedentMedical1);
        $manager->persist($antecedentMedical2);
        $manager->persist($antecedentMedical3);
        $manager->persist($antecedentMedical4);

        $manager->flush();
    }
}
