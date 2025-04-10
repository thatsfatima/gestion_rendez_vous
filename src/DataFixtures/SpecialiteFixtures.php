<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Specialite;

class SpecialiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $specialite1 = new Specialite();
        $specialite1->setLibelle('Ophtamologue');

        $specialite2 = new Specialite();
        $specialite2->setLibelle('Dentiste');

        $specialite3 = new Specialite();
        $specialite3->setLibelle('Diabetologue');

        $specialite4 = new Specialite();
        $specialite4->setLibelle('Rhumatologue');

        $manager->persist($specialite1);
        $manager->persist($specialite2);
        $manager->persist($specialite3);
        $manager->persist($specialite4);

        $manager->flush();
    }
}
