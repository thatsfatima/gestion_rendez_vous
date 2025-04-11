<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ResponsablePrestation;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResponsablePrestationFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $responsable = new ResponsablePrestation('responsable0', 'password');
        $responsable->setNom('Thiam')
            ->setPrenom('Sophie')
            ->setPassword($this->passwordHasher->hashPassword($responsable, 'password'))
            ->setTelephone('70' . rand(1000000, 9999999));
        $manager->persist($responsable);

        $manager->flush();
    }
}
