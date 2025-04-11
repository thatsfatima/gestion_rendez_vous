<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $patientRole = $manager->getRepository(Role::class)->findOneBy(['libelle' => 'ROLE_PATIENT']);
        // $user = new User('patient', 'passer');
        // $user->setNom('Fatima');
        // $user->setPrenom('MBG');
        // $user->setPassword($this->passwordHasher->hashPassword($user, 'passer'));
        // $user->setTelephone('771234567');
        // $user->setRoles(['ROLE_USER', 'ROLE_PATIENT']);
        // $manager->persist($user);

        $manager->flush();
    }
}
