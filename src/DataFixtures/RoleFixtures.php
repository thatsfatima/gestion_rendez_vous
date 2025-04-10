<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Role;

class RoleFixtures extends Fixture
{
    public const ROLE_PATIENT = 'ROLE_PATIENT';

    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setLibelle(self::ROLE_PATIENT);
        $manager->persist($role);

        $manager->flush();
    }
}
